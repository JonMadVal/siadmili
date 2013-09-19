<?php
/*
 * Nombre       :   usuariosController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que hará el mantenimiento de la tabla Admin
 */

class usuariosController extends Controller 
{
    private $_user;
    private $_levels;
    private $_paginador;

    public function __construct() 
    {
        parent::__construct();
        $this->_user= $this->loadModel('users');
        $this->_levels = $this->loadModel('level');
        $this->_paginador = new Paginador();
    }

    /**
     * Cargará la vista por defecto.
     */
    public function index() 
    {
        if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            $this->_acl->acceso('admin_access');
            $this->_acl->acceso('view_user');
            $dataUser = $this->_user->getUserById(Session::get('userID'));
            if (is_array($dataUser) && count($dataUser)) {
                $this->_view->assign('dataUser', $dataUser);
            }
            $this->_view->setCssPublic(array('jquery.alerts'));
            $this->_view->setJs(array('funciones'));
            $this->_view->setJsPlugin(array('jquery.alerts'));
            $this->_view->assign('_validation', 'TRUE');
            $level = $this->_levels->getLevels();
            if (is_array($level) && count($level)) {
                $this->_view->assign('_level', $level);
            }

            // Código para agregar un nuevo usuario
            if ($this->getInt('grabar') && $this->getInt('grabar') == "1") {
                $this->_acl->acceso('add_user');
                if ($this->getTexto('txtNombre')) {
                    if ($this->getTexto('txtAPaterno')) {
                        if ($this->getTexto('txtAMaterno')) {
                            if ($this->getTexto('txtUsername')) {
                                if ($this->_user->verifyUsername($this->getSql('txtUsername'))) {
                                    if ($this->getTexto('txtPass')) {
                                        if (strlen($_POST['txtPass']) >= 6) {
                                            if ($this->getTexto('txtRePass')) {
                                                if (strlen($_POST['txtRePass']) >= 6) {
                                                    if ($_POST['txtPass'] == $_POST['txtRePass']) {
                                                        if ($this->getPostParam('txtEmail')) {
                                                            if ($this->validarEmail($this->getPostParam('txtEmail'))) {
                                                                if ($this->_user->verifyEmail($this->getSql('txtEmail'))) {
                                                                    if ($this->getInt('drdRole') != 0){
                                                                        $data = array(
                                                                            'username' => $this->getSql('txtUsername'),
                                                                            'pass' => Hash::getHash('sha1', $this->getSql('txtPass'), HASH_KEY),
                                                                            'nombres' => $this->getSql('txtNombre'),
                                                                            'apaterno' => $this->getSql('txtAPaterno'),
                                                                            'amaterno' => $this->getSql('txtAMaterno'),
                                                                            'email' => $this->getPostParam('txtEmail'),
                                                                            'telefono' => $this->getSql('txtTel'),
                                                                            'role' => $this->getInt('drdRole'),
                                                                            'comentario' => $this->getAlphaNum('txtComentario')
                                                                        );
                                                                        if ($this->_user->addUser($data)) {
                                                                            $this->_view->assign('_exito', 'Se agreg&oacute; el nuevo usuario correctamente.');
                                                                        } else {
                                                                            $this->_view->assign('_error', 'No se pudo agregar el nuevo usuario. Por favor verifique los datos.');
                                                                        }
                                                                    } else {
                                                                        $this->_view->assign('_error', 'Debe seleccionar el role del usuario.');
                                                                    }
                                                                } else {
                                                                    $this->_view->assign('_error', 'Email ya se encuentra registrado.');
                                                                }                                                         
                                                            } else {
                                                                $this->_view->assign('_error', 'Ingrese un email v&aacute;lido.');
                                                            }
                                                        } else {
                                                            $this->_view->assign('_error', 'Ingrese el email del usuario.');
                                                        }                                                    
                                                    } else {
                                                        $this->_view->assign('_error', 'Los password deben coincidir.');
                                                    }
                                                } else {
                                                    $this->_view->assign('_error', 'El password debe tener como m&iacute;nimo 6 caracteres.');
                                                }
                                            } else {
                                                $this->_view->assign('_error', 'Repetir el password del usuario.');
                                            }
                                        } else {
                                            $this->_view->assign('_error', 'El password debe tener como m&iacute;nimo 6 caracteres.');
                                        }
                                    } else {
                                        $this->_view->assign('_error', 'Ingrese el password del usuario.');
                                    }
                                } else {
                                    $this->_view->assign('_error', 'Username ya se encuentra registrado.');
                                }
                            } else {
                                $this->_view->assign('_error', 'Ingrese el username del usuario.');
                            }
                        } else {
                            $this->_view->assign('_error', 'Ingrese el apallido materno del usuario.');
                        }
                    } else {
                        $this->_view->assign('_error', 'Ingrese el apellido paterno del usuario.');
                    }
                } else {
                    $this->_view->assign('_error', 'Ingrese el nombre del usuario.');         
                }                
            }

            // Código para editar un usuario
            if ($this->getInt('grabar') && $this->getInt('grabar') == "2") {
                $this->_acl->acceso('edit_user');
                if ($this->getTexto('txtEditNombre')) {
                    if ($this->getTexto('txtEditAPaterno')) {
                        if ($this->getTexto('txtEditAMaterno')) {
                            if ($this->getTexto('txtEditUsername')) {
                                if ($this->getTexto('txtEditUsername') != $this->getTexto('hdUsername')) {
                                    if ($this->_user->verifyUsername($this->getSql('txtEditUsername'))) {
                                        if ($this->getPostParam('txtEditEmail')) {
                                            if ($this->validarEmail($this->getPostParam('txtEditEmail'))) {
                                                if ($this->getTexto('txtEditEmail') != $this->getTexto('hdEmail')) {                                                
                                                    if ($this->_user->verifyEmail($this->getSql('txtEditEmail'))) {
                                                        $this->getEditUser();
                                                    } else {
                                                        $this->_view->assign('_error', 'Email ya se encuentra registrado.');
                                                    }                                                         
                                                } else {
                                                    $this->getEditUser();
                                                }
                                            } else {
                                                $this->_view->assign('_error', 'Ingrese un email v&aacute;lido.');
                                            }                                            
                                        } else {
                                            $this->_view->assign('_error', 'Ingrese el email del usuario.');
                                        }  
                                    } else {
                                        $this->_view->assign('_error', 'Username ya se encuentra registrado.');
                                    }
                                } else {
                                    if ($this->getPostParam('txtEditEmail')) {
                                        if ($this->validarEmail($this->getPostParam('txtEditEmail'))) {
                                            if ($this->getTexto('txtEditEmail') != $this->getTexto('hdEmail')) {                                                
                                                if ($this->_user->verifyEmail($this->getSql('txtEditEmail'))) {
                                                    $this->getEditUser();
                                                } else {
                                                    $this->_view->assign('_error', 'Email ya se encuentra registrado.');
                                                }                                                         
                                            } else {
                                                $this->getEditUser();
                                            }
                                        } else {
                                            $this->_view->assign('_error', 'Ingrese un email v&aacute;lido.');
                                        }                                            
                                    } else {
                                        $this->_view->assign('_error', 'Ingrese el email del usuario.');
                                    }  
                                }
                            } else {
                                $this->_view->assign('_error', 'Ingrese el username del usuario.');
                            }
                        } else {
                            $this->_view->assign('_error', 'Ingrese el apallido materno del usuario.');
                        }
                    } else {
                        $this->_view->assign('_error', 'Ingrese el apellido paterno del usuario.');
                    }
                } else {
                    $this->_view->assign('_error', 'Ingrese el nombre del usuario.');         
                }
            }

            $this->_view->assign('users', $this->_paginador->paginar($this->_user->getUsers()));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador_ajax'));
            $this->_view->assign('titulo', APP_NAME . ' - Mantenimiento de Usuarios');
            $this->_view->renderizar('index', 'Mantenimiento');                      
        }
    }
    
    private function getEditUser() 
    {
        if ($this->getInt('drdEditRole') != 0){
            $data = array(
                'userID' => $this->getInt('hdUserId'),
                'username' => $this->getSql('txtEditUsername'),
                'nombres' => $this->getSql('txtEditNombre'),
                'apaterno' => $this->getSql('txtEditAPaterno'),
                'amaterno' => $this->getSql('txtEditAMaterno'),
                'email' => $this->getPostParam('txtEditEmail'),
                'telefono' => $this->getSql('txtEditTel'),
                'role' => $this->getInt('drdEditRole'),
                'comentario' => $this->getAlphaNum('txtEditComentario')
            );
            if ($this->_user->editUser($data)) {
                $this->_view->assign('_exito', 'Se edit&oacute; el usuario correctamente.');
            } else {
                $this->_view->assign('_error', 'No se pudo editar el usuario. Por favor verifique los datos.');
            }
        } else {
            $this->_view->assign('_error', 'Debe seleccionar el role del usuario.');
        }
    }


    /**
     * Mostrar los datos del usuario para realizar la paginación a través de ajax
     *
     * @param int $_POST
     *   Número de página a mostrar en la paginación.
     *
     * @return
     *   Carga la vista con los datos del usuario.
     */
    public function displayUser() 
    {        
        $page = $this->getInt('page');
        $nombre = $this->getSql('nombre');
        $apaterno = $this->getSql('apaterno');
        $amaterno = $this->getSql('amaterno');
        $condicion = "";
        
        if ($nombre) {
            $condicion .= " AND `nombres` LIKE '" . $nombre . "%' ";
        }
        
        if ($apaterno) {
            $condicion .= " AND `apaterno` LIKE '" . $apaterno . "%'";
        }
        
        if ($amaterno) {
            $condicion .= " AND `amaterno` LIKE '" . $amaterno . "%'";
        }
        
        $registros = $this->getInt('registros');
        
        $users = $this->_user->getUsers($condicion);
        if (is_array($users) && count($users)) {
            $this->_view->assign('users', $this->_paginador->paginar($users, $page, $registros));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador_ajax'));
            $this->_view->renderizar('displayUser', FALSE, FALSE, TRUE);            
        }
    }
    
    /**
     * Método que a través del role enviado vía ajax permitirá validar si el role a añadir existe o no.
     */
    public function verifyUsername() 
    {       
        if ($this->_user->verifyUsername($this->getSql('username'))) {
            $valid = 'true';
        } else {
            $valid = 'false';
        }
        echo $valid;
    }
    
    /**
     * Método que a través del role enviado vía ajax permitirá validar si el role a añadir existe o no.
     */
    public function verifyEmail() 
    {       
        if ($this->_user->verifyEmail($this->getPostParam('email'))) {
            $valid = 'true';
        } else {
            $valid = 'false';
        }
        echo $valid;
    }

    /**
     * Obtener la extensión ingresada como parámetro.
     *
     * @param str $str
     *   String de donde obtendremos la extensión.
     *
     * @return
     *   String con la extensión del $str.
     */
    public function _getExtension($str) 
    {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }

        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }
    
    /**
     * Cargar los datos del usuario para editar
     *
     * @param int $_POST['id']
     *   Id del usuario a editar.
     *
     * @return
     *   Mostramos a través de Json los datos del usuario a editar.
     */
    public function getUser() 
    {
        $userid = $this->getInt('userid');
        $result = $this->_user->getUserById($userid);
        echo json_encode($result);
    }

    /**
     * Eliminar de la base de datos uno más usuarios.
     *
     * @param array $_POST['idRole']
     *   Array con los Id de o los roles a eliminar.
     *
     * @return
     *   Nos redirige al método index enviando un mensaje de acuerdo a si se eliminó o no 
     *   el registro.
     */
    public function deleteUsers() 
    {
        $this->_acl->acceso('del_user');
        if (isset($_POST['idUser']) && count($_POST['idUser']) > 0) {
            $errores = array();
            $exitos = array();
            foreach ($_POST['idUser'] as $user) {
                $result = $this->_user->deleteUser($user);
                if ($result == NULL) {
                    $errores[] = 'No se pudo eliminar el usuario ' . $user;
                } else {
                    $exitos[] = 'Se elimin&oacute; el usuario ' . $user;
                }
            }
            $this->_view->assign('_errores', $errores);
            $this->_view->assign('_exitos', $exitos);
        } else {
            $this->_view->assign('_error', 'No ha seleccionado usuario a eliminar.');
        }
        $this->index();
    }
    
    /**
     * Eliminar un usuario específico
     * 
     * @param int $id Este parámetro es enviado vía ajax y contiene el ID del usuario a eliminar
     * 
     * @return str Devuelve un string 0 si no se eliminó o 1 si lo realizó. Esto se devuelve a la 
     * función ajax.
     */
    public function deleteUser() 
    {
        if ($this->getInt('id')) {   
            $result = $this->_user->deleteUser($this->getInt('id'));
            if ($result == NULL) {
                echo '0';
            } else {
                echo '1';
            }
        }
    }

    /**
     * Eliminar de la base de datos un usuario específico a través de ajax.
     *
     * @param int $userId
     *   Id del usuario a mostrar sus permisos.
     *
     * @return
     *   Listado con los permisos del usuario de acuerdo a su role.
     */
    public function permisos($userId, $pagina = FALSE, $registros = FALSE) 
    {
        if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            $this->_acl->acceso('admin_access');
            $this->_acl->acceso('view_perm');
            
            $dataUser = $this->_user->getUserById(Session::get('userID'));
            if (is_array($dataUser) && count($dataUser)) {
                $this->_view->assign('dataUser', $dataUser);
            }
            
            $id = $this->filtrarInt($userId);
            if (!$id) $this->redirect('usuarios');

            if (!$this->filtrarInt($pagina)) {
                $pagina = FALSE;
            } else {
                $pagina = (int) $pagina;
            }
            
            if ($this->filtrarInt($registros)) {
                $registros = $this->filtrarInt($registros);
            }

            if ($this->getInt('guardar') == 1) {
                $this->_acl->acceso('edit_perm');
                $values = array_keys($_POST);
                $replace = array();
                $eliminar = array();

                for ($i = 0; $i < count($values); $i++) {
                    if (substr($values[$i], 0, 5) == 'perm_') {
                        if (strstr(substr($values[$i], -2), '_')) {
                            $id_permiso = substr($values[$i], -1);
                        } else {
                            $id_permiso = substr($values[$i], -2);
                        }
                        if ($_POST[$values[$i]] == 'x') {
                            $eliminar[] = array(
                                'userID' => $id,
                                'id_permiso' => $id_permiso
                            );
                        } else {
                            if ($_POST[$values[$i]] == 1) {
                                $v = 1;
                            } else {
                                $v = 0;
                            }
                            $replace[] = array(
                                'userID' => $id,
                                'id_permiso' => $id_permiso,
                                'valor' => $v
                            );
                        }
                    }
                }
                
                for ($i = 0; $i < count($eliminar); $i++) {
                    $this->_user->eliminarPermiso($eliminar[$i]['userID'], $eliminar[$i]['id_permiso']);
                }

                for ($i = 0; $i < count($replace); $i++) {
                    $this->_user->editarPermiso($replace[$i]['userID'], $replace[$i]['id_permiso'], $replace[$i]['valor']);
                }
            }

            $permisosUsuario = $this->_user->getPermisosUsuario($id);
            $permisosRole = $this->_user->getPermisosRole($id);
            
            $this->_view->setJs(array('fnPermisos'));
            $this->_view->assign('titulo', APP_NAME . ' - Permisos de Usuarios');
            $this->_view->setCssPublic(array('jquery.alerts'));
            $this->_view->setJsPlugin(array('jquery.alerts'));
            
            // Verificamos los permisos que tiene asignado el usuario de acuerdo a su role
            if (count($permisosUsuario)) {
                $result = array_keys($permisosUsuario);
                $this->_view->assign('permisos', $this->_paginador->paginar($result, $pagina, $registros));
                $this->_view->assign('usuario', $permisosUsuario);
            } 
            
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador', 'usuarios/permisos/' . $id));            
            $this->_view->assign('role', $permisosRole);
            $this->_view->assign('info', $this->_user->getUserById($id));
            $this->_view->renderizar('permisos', 'Mantenimiento');
        }
    }
    
    /**
     * Permite configurar la cuenta del usuario logueado
     * 
     * @param int $userId
     *   Id del usuario
     */
    public function configUser()
    {
        if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            $dataUser = $this->_user->getUserById(Session::get('userID'));
            if (is_array($dataUser) && count($dataUser)) {
                $this->_view->assign('dataUser', $dataUser);
            }
            //$this->_view->setCssPublic(array('jquery.alerts'));
            //$this->_view->setJs(array('funciones'));
            //$this->_view->setJsPlugin(array('jquery.alerts'));
            //$this->_view->assign('_validation', 'TRUE');
            $level = $this->_levels->getLevels();
            if (is_array($level) && count($level)) {
                $this->_view->assign('_level', $level);
            }
            
            //$this->_view->assign('users', $this->_paginador->paginar($this->_user->getUsers()));
            //$this->_view->assign('paginacion', $this->_paginador->getView('paginador_ajax'));
            $this->_view->assign('titulo', APP_NAME . ' - Configuración de Cuenta');
            $this->_view->renderizar('configUser', 'Mantenimiento');                      
        }
    }
    
    /*
    private function _editConfigUser()
    {
        if ($this->getInt('drdRole') != 0) {
            $data = array(
                'username'  =>  $this->getSql('txtLogin'),                                                        
                'nombres'   =>  $this->getSql('txtNombres'),
                'apaterno'  =>  $this->getSql('txtAPaterno'),
                'amaterno'  =>  $this->getSql('txtAMaterno'),
                'email'     =>  $this->getSql('txtEmail'),
                'telefono'  =>  $this->getSql('txtTelefono'),
                'role'      =>  $this->getInt('drdRole'),
                'comentario'    => $this->getSql('txtComentario'),
                'userID'    => Session::get('userID')
            );
            
            // Si vamos a subir un avatar 
            if (!empty($_FILES['txtAvatar']['name']) && $_FILES['txtAvatar']['name'] != '') {
                $avatar = $this->getTexto('hdAvatar');
                $ruta = ROOT . 'public' . DS . 'images' . DS . 'users' . DS;
                if (!empty($avatar) && $avatar != 'avatar.png') {
                    unlink($ruta . $this->getTexto('hdAvatar'));
                    unlink($ruta . 'avatar' . DS . 'thumb_' . $this->getTexto('hdAvatar'));
                }
                $imagen = '';                
                $upload = new upload($_FILES['txtAvatar'], 'es_ES');
                $upload->file_max_size = '1048576';
                $upload->allowed = array('image/*');
                $upload->file_new_name_body = 'upl_' . uniqid();
                $upload->process($ruta);

                if ($upload->processed) {
                    $imagen = $upload->file_dst_name;
                    $thumb = new upload($upload->file_dst_pathname);
                    $thumb->image_resize = true;
                    $thumb->image_x = 64;
                    $thumb->image_y = 64;
                    $thumb->image_ratio = true;
                    $thumb->file_name_body_pre = 'thumb_';
                    $thumb->process($ruta . 'avatar' . DS);
                } else {
                    $this->_view->assign('_error', 'No se pudo agregar el avatar.');                     
                }
                $data['avatar'] = $imagen;
                Session::set('avatar', $imagen);
            }

            if ($this->getTexto('txtPass')) {
                if (strlen($this->getTexto('txtPass')) >= 6 && strlen($this->getTexto('txtRePass')) >= 6 && $this->getTexto('txtPass') == $this->getTexto('txtRePass')) {
                    $data['pass'] = Hash::getHash('sha1', $this->getSql('txtPass'), HASH_KEY);
                } else {
                    $this->_view->assign('_error', 'Verifique que haya ingresado correctamente los password.');
                }
            } 

            if ($this->_user->editUser($data)) {
                $this->_view->assign('_exito', 'Se edit&oacute; su configuraci&oacute;n correctamente.');
            } else {
                $this->_view->assign('_error', 'No se pudo editar su configuraci&oacute;n. Por favor verifique');
            }      
        } else {
            $this->_view->assign('_error', 'No ha seleccionado el role del usuario.'); 
        }
    }*/
}
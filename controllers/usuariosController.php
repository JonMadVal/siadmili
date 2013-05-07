<?php

/*
 * Nombre       :   usuariosController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que hará el mantenimiento de la tabla Admin
 */

class usuariosController extends Controller 
{
    private $_login;
    private $_levels;
    private $_paginador;

    public function __construct() 
    {
        parent::__construct();
        $this->_login = $this->loadModel('users');
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
            $this->_view->setCssPublic(array('jquery.alerts'));
            $this->_view->setJs(array('funciones'));
            $this->_view->setJsPlugin(array('jquery.alerts', 'jquery.form'));
            $level = $this->_levels->getLevels();
            if (is_array($level) && count($level)) {
                $this->_view->assign('_level', $level);
            }
            
            $this->_view->assign('users', $this->_paginador->paginar($this->_login->getUsers()));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador_ajax'));
            $this->_view->assign('titulo', APP_NAME . ' - Mantenimiento de Usuarios');
            $this->_view->renderizar('index', 'Mantenimiento');
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
        
        $users = $this->_login->getUsers($condicion);
        if (is_array($users) && count($users)) {
            $this->_view->assign('users', $this->_paginador->paginar($users, $page, $registros));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador_ajax'));
            $this->_view->renderizar('displayUser', FALSE, FALSE, TRUE);            
        }
    }

    /**
     * Realiza la validación de los datos del nuevo usuario, también verifica si 
     * se va a subir una imagen y crear un thumbnail y finalmente nos mostrará un 
     * mensaje de acuerdo a la respuesta obtenida.
     *
     * @param $_POST
     *   Datos del nuevo usuario.
     *
     * @return
     *   Mensaje de error en caso no pase las validaciones y éxito en caso se haya
     *   ingresado al nuevo usuario.
     */
    public function verifyAddUser() 
    {
        //$this->_view->datos = $_POST;

        if (!$this->getPostParam('name')) {
            echo 'Debe introducir el nombre del usuario.<br />';
            exit;
        }

        if (!$this->getPostParam('apaterno')) {
            echo 'Debe introducir el apellido paterno del usuario.<br />';
            exit;
        }

        if (!$this->getPostParam('amaterno')) {
            echo 'Debe introducir el apellido materno del usuario.<br />';
            exit;
        }

        if (!$this->getPostParam('login')) {
            echo 'Debe introducir el login del usuario.<br />';
            exit;
        }

        $login = $this->getPostParam('login');
        if (!$this->_login->getUserByUsername($login)) {
            echo 'El nombre de usuario ya esta siendo utilizado.<br />';
            exit;
        }

        if (!$this->getPostParam('password')) {
            echo 'Debe introducir el password del usuario.<br />';
            exit;
        }

        $password = $this->getPostParam('password');
        if (strlen($password) < 6) {
            echo 'El password debe contener al menos 6 caracteres.<br />';
            exit;
        }

        if (!$this->getPostParam('re-password')) {
            echo 'Debe repetir el password.<br />';
            exit;
        }

        $re_password = $this->getPostParam('re-password');
        if ($password != $re_password) {
            echo 'Los password no coinciden.<br />';
            exit;
        }

        if (!$this->getPostParam('email')) {
            echo 'Debe introducir el email del usuario.<br />';
            exit;
        }

        $email = $this->getPostParam('email');
        if (!$this->validarEmail($email)) {
            echo 'El email ingresado no es v&aacute;lido.<br />';
            exit;
        }

        if (!$this->_login->getUserByEmail($email)) {
            echo 'El email ingresado se encuentrada registrado.<br />';
            exit;
        }

        if ($_POST['level'] == '0') {
            echo 'Debe seleccionar el nivel del usuario.<br />';
            exit;
        }

        $imagen = '';
        if (isset($_FILES['avatar']['name'])) {
            $ruta = ROOT . 'public' . DS . 'images' . DS . 'users' . DS;
            $upload = new upload($_FILES['avatar'], 'es_ES');
            $upload->file_max_size = '1048576';
            $upload->allowed = array('image/*');
            $upload->file_new_name_body = 'upl_' . uniqid();
            $upload->process($ruta);

            if ($upload->processed) {
                $imagen = $upload->file_dst_name;
                $thumb = new upload($upload->file_dst_pathname);
                $thumb->image_resize = true;
                $thumb->image_x = 100;
                $thumb->image_y = 70;
                $thumb->image_ratio = true;
                $thumb->file_name_body_pre = 'thumb_';
                $thumb->process($ruta . 'thumb' . DS);
            } else {
                echo 'No se pudo agregar su imagen, verifique.<br />';
                exit;
            }
        }

        $name = $this->getPostParam('name');
        $apaterno = $this->getPostParam('apaterno');
        $amaterno = $this->getPostParam('amaterno');
        $password = Hash::getHash('sha1', $this->getSql('password'), HASH_KEY);
        $telefono = $this->getPostParam('telefono');
        $level = $_POST['level'];
        $comments = $this->getTexto('comments');

        $insert = $this->_login->insertUser($login, $password, $name, $apaterno, $amaterno, $email, $telefono, $imagen, $level, $comments);
        if ($insert) {
            echo 'Se ingres&oacute; correctamente al nuevo usuario';
            exit;
        } else {
            echo 'No se puedo agregar el nuevo usuario';
            exit;
        }
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
    public function _getExtension($str) {
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
    public function editUser() {
        $id = $this->filtrarInt($_POST['id']);
        $result = $this->_login->getUserById($id);
        if (!$result) {
            exit;
        }
        if (is_array($result)) {
            foreach ($result as $user) {
                echo json_encode($user);
            }
        }
    }

    /**
     * Realiza la validación de los datos del usuario a editar, también verifica si 
     * se va a subir una imagen y crear un thumbnail y eliminará en caso ya tenga 
     * otro avatar y finalmente nos mostrará un mensaje de acuerdo a la respuesta obtenida.
     *
     * @param $_POST
     *   Datos del usuario a editar.
     *
     * @return
     *   Mensaje de error en caso no pase las validaciones y éxito en caso se haya
     *   editado al usuario.
     */
    public function verifyEditUser() {
        if (!$this->getPostParam('nameEdit')) {
            echo 'Debe introducir el nombre del usuario.<br />';
            exit;
        }

        if (!$this->getPostParam('apaternoEdit')) {
            echo 'Debe introducir el apellido paterno del usuario.<br />';
            exit;
        }

        if (!$this->getPostParam('amaternoEdit')) {
            echo 'Debe introducir el apellido materno del usuario.<br />';
            exit;
        }

        if (!$this->getPostParam('loginEdit')) {
            echo 'Debe introducir el login del usuario.<br />';
            exit;
        }

        if (!$this->getPostParam('emailEdit')) {
            echo 'Debe introducir el email del usuario.<br />';
            exit;
        }

        $email = $this->getPostParam('emailEdit');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'El email ingresado no es v&aacute;lido.<br />';
            exit;
        }

        if ($_POST['levelEdit'] == '0') {
            echo 'Debe seleccionar el nivel del usuario.<br />';
            exit;
        }

        //$imagen = '';
        $imagen = $_POST['hdAvatar'];
        if (isset($_FILES['avatarEdit']['name'])) {
            if (!empty($imagen)) {
                $ruta_avatar_origin = ROOT . 'public' . DS . 'images' . DS . 'users' . DS . $imagen;
                $ruta_thumbnail = ROOT . 'public' . DS . 'images' . DS . 'users' . DS . 'thumb' . DS . 'thumb_' . $imagen;
                unlink($ruta_avatar_origin);
                unlink($ruta_thumbnail);
            }
            $ruta = ROOT . 'public' . DS . 'images' . DS . 'users' . DS;
            $upload = new upload($_FILES['avatarEdit'], 'es_ES');
            $upload->file_max_size = '1048576';
            $upload->allowed = array('image/*');
            $upload->file_new_name_body = 'upl_' . uniqid();
            $upload->process($ruta);

            if ($upload->processed) {
                $imagen = $upload->file_dst_name;
                $thumb = new upload($upload->file_dst_pathname);
                $thumb->image_resize = true;
                $thumb->image_x = 100;
                $thumb->image_y = 70;
                $thumb->image_ratio = true;
                $thumb->file_name_body_pre = 'thumb_';
                $thumb->process($ruta . 'thumb' . DS);
            } else {
                echo 'No se pudo editar su imagen, verifique. <br />';
                exit;
            }
        }

        $id = $this->getPostParam('id');
        $name = $this->getPostParam('nameEdit');
        $apaterno = $this->getPostParam('apaternoEdit');
        $amaterno = $this->getPostParam('amaternoEdit');
        $login = $this->getPostParam('loginEdit');
        $hdLogin = $this->getPostParam('hdLogin');
        $hdEmail = $this->getPostParam('hdEmail');
        $telefono = $this->getPostParam('telefonoEdit');
        $level = $_POST['levelEdit'];
        $comments = $this->getPostParam('commentsEdit');
        // Cuando no se ha modificado el login
        if ($login == $hdLogin) {
            // Cuando no se ha modificado el email
            if ($email == $hdEmail) {
                $result = $this->_login->editUserById($name, $apaterno, $amaterno, $email, $telefono, $imagen, $login, $level, $comments, $id);
                if (!$result) {
                    echo "No se pudo editar el usuario. Por favor vuelva a intentarlo.";
                    exit;
                } else {
                    echo "El usuario se edit&oacute; satisfactoriamente.";
                    exit;
                }
            } else { // Si se esta modificando el email
                if (!$this->_login->getUserByEmail($email)) {
                    echo "No se puede editar el usuario porque el email ya se encuentra registrado";
                    exit;
                } else {
                    $result = $this->_login->editUserById($name, $apaterno, $amaterno, $email, $telefono, $imagen, $login, $level, $comments, $id);
                    if (!$result) {
                        echo "No se pudo editar el usuario. Por favor vuelva a intentarlo.";
                        exit;
                    } else {
                        echo "El usuario se edit&oacute; satisfactoriamente.";
                        exit;
                    }
                }
            }
        } else { // Si login se ha modificado
            if ($email == $hdEmail) { // Si el email es el mismo
                if (!$this->_login->getUserByUsername($login)) {
                    echo "No se puede editar el usuario porque el nombre de usuario esta siendo utilizado";
                    exit;
                } else { // El login a modificar no esta registrado
                    $result = $this->_login->editUserById($name, $apaterno, $amaterno, $email, $telefono, $imagen, $login, $level, $comments, $id);
                    if (!$result) {
                        echo "No se pudo editar el usuario. Por favor vuelva a intentarlo.";
                        exit;
                    } else {
                        echo "El usuario se edit&oacute; satisfactoriamente.";
                        exit;
                    }
                }
            } else { // Si se va a modificar también el email
                if (!$this->_login->getUserByEmail($email) || !$this->_login->getUserByUsername($login)) {
                    echo "No se puede editar el usuario porque el nombre de usuario o el email se encuentran registrados.";
                    exit;
                } else {
                    $result = $this->_login->editUserById($name, $apaterno, $amaterno, $email, $telefono, $imagen, $login, $level, $comments, $id);
                    if (!$result) {
                        echo "No se pudo editar el usuario. Por favor vuelva a intentarlo.";
                        exit;
                    } else {
                        echo "El usuario se edit&oacute; satisfactoriamente.";
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Eliminar de la base de datos un usuario específico a través de ajax.
     *
     * @param int $_POST['id']
     *   Id del usuario a eliminar.
     *
     * @return
     *   Mostrará 0 en caso falle o 1 en caso se haya eliminado.
     */
    public function deleteUser() {
        $id = $this->filtrarInt($_POST['id']);
        $result = $this->_login->deleteUser($id);
        if (!$result) {
            echo '0';
        } else {
            echo '1';
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
    public function permisos($userId, $pagina = FALSE) 
    {
        if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            $id = $this->filtrarInt($userId);
            if (!$id) $this->redirect('usuarios');

            if (!$this->filtrarInt($pagina)) {
                $pagina = FALSE;
            } else {
                $pagina = (int) $pagina;
            }

            if ($this->getInt('guardar') == 1) {
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
                    $this->_login->eliminarPermiso($eliminar[$i]['userID'], $eliminar[$i]['id_permiso']);
                }

                for ($i = 0; $i < count($replace); $i++) {
                    $this->_login->editarPermiso($replace[$i]['userID'], $replace[$i]['id_permiso'], $replace[$i]['valor']);
                }
            }

            $permisosUsuario = $this->_login->getPermisosUsuario($id);
            $permisosRole = $this->_login->getPermisosRole($id);

            if (!$permisosUsuario || !$permisosRole) {
                $this->redirect('usuarios');
            }
            
            $result = array_keys($permisosUsuario);
            $this->_view->setJs(array('fnPermisos'));
            $this->_view->assign('titulo', APP_NAME . ' - Permisos de Usuarios');
            $this->_view->assign('permisos', $this->_paginador->paginar($result, $pagina));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador', 'usuarios/permisos/' . $id));
            $this->_view->assign('usuario', $permisosUsuario);
            $this->_view->assign('role', $permisosRole);
            $this->_view->assign('info', $this->_login->getUserById($id));
            $this->_view->renderizar('permisos', 'Mantenimiento');
        }
    }

}
<?php

/*
 * Nombre       :   aclController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que gestionar las listas de acceso al sistema
 */

class aclController extends Controller 
{

    private $_aclm;
    private $_paginador;

    public function __construct() 
    {
        parent::__construct();
        $this->_aclm = $this->loadModel('acl');
        $this->_paginador = new Paginador();
    }

    public function index($pagina = FALSE) 
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            if (!$this->filtrarInt($pagina)) {
                $pagina = false;
            } else {
                $pagina = (int) $pagina;
            }

            if ($this->getInt('grabar') && $this->getInt('grabar') == "1") {
                if (!$this->getTexto('txtRole')) {
                    $this->_view->assign('_error', 'No ha ingresado el nombre del role.');
                } else if (!$this->_aclm->getRoleByUsername($this->getSql('txtRole'))) {
                    $this->_view->assign('_error', 'El role ingresado ya existe.');
                } else if (!$this->_aclm->insertRole($this->getSql('txtRole'))) {
                    $this->_view->assign('_error', 'Ha ocurrido un error al agregar el nuevo role.');
                } else {
                    $this->_view->assign('_exito', 'Se agreg&oacute; el nuevo role.');
                }
            }

            if ($this->getInt('grabar') && $this->getInt('grabar') == "2") {
                if (!$this->getTexto('txtEditRole')) {
                    $this->_view->assign('_error', 'No ha ingresado el nombre del role.');
                } else if (!$this->_aclm->getRoleByUsername($this->getSql('txtEditRole'))) {
                    $this->_view->assign('_error', 'El role ingresado ya existe.');
                } else if (!$this->_aclm->editRoleById($this->getSql('txtEditRole'), $this->getInt('roleID'))) {
                    $this->_view->assign('_error', 'Ha ocurrido un error al agregar el nuevo role.');
                } else {
                    $this->_view->assign('_exito', 'Se edit&oacute; el role.');
                }
            }
            
            $roles = $this->_aclm->getRoles();
            if (count($roles) && is_array($roles)) {
                if (count($roles) > 10) {
                    $this->_view->assign('_roles', $this->_paginador->paginar($roles, $pagina));
                    $this->_view->assign('paginacion', $this->_paginador->getView('paginador', 'acl/index'));
                } else {
                    $this->_view->assign('_roles', $roles);
                }
            }
            $this->_view->setCssPublic(array('jquery.alerts'));
            $this->_view->setJs(array('funciones'));
            $this->_view->setJsPlugin(array('jquery.alerts'));
            $this->_view->assign('_validation', 'TRUE');
            $this->_view->assign('titulo', APP_NAME . ' - Administraci&oacute;n de Roles');
            $this->_view->renderizar('index', FALSE, 'role');
        }
    }

    /**
     * Nos muestra los permisos asignados a cada role. También nos permite modificar el estado de los permisos
     * @param int $roleID   Id del role a mostrar los permisos
     * @param int $pagina   Página a mostrar
     */
    public function permisosRole($roleID, $pagina = FALSE) 
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            $id = $this->filtrarInt($roleID);
            if (!$id) {
                $this->redirect('acl');
            }

            $row = $this->_aclm->getRole($id);

            if (!$row) {
                $this->redirect('acl');
            }

            if (!$this->filtrarInt($pagina)) {
                $pagina = false;
            } else {
                $pagina = (int) $pagina;
            }

            if ($this->getInt('guardar') == 1) {
                $values = array_keys($_POST);
                $replace = array();
                $eliminar = array();
                for ($i = 0; $i < count($values); $i++) {
                    if (substr($values[$i], 0, 5) == 'perm_') {
                        // Permite verificar que el id del permiso tenga dos dígitos
                        if (strstr(substr($values[$i], -2), '_')) {
                            $id_permiso = substr($values[$i], -1);
                        } else {
                            $id_permiso = substr($values[$i], -2);
                        }

                        if ($_POST[$values[$i]] == 'x') {
                            $eliminar[] = array(
                                'role' => $id,
                                'permiso' => $id_permiso
                            );
                        } else {
                            if ($_POST[$values[$i]] == 1) {
                                $v = 1;
                            } else {
                                $v = 0;
                            }
                            $replace[] = array(
                                'role' => $id,
                                'permiso' => $id_permiso,
                                'valor' => $v
                            );
                        }
                    }
                }

                for ($i = 0; $i < count($eliminar); $i++) {
                    $this->_aclm->eliminarPermisoRole($eliminar[$i]['role'], $eliminar[$i]['permiso']);
                }

                for ($i = 0; $i < count($replace); $i++) {
                    $this->_aclm->editarPermisoRole($replace[$i]['role'], $replace[$i]['permiso'], $replace[$i]['valor']);
                }
            }
            $this->_view->assign('titulo', APP_NAME . ' - Administraci&oacute;n de Permisos de Role');
            $this->_view->assign('roleID', $id);
            $this->_view->assign('role', $row);
            $this->_view->assign('permisos', $this->_paginador->paginar($this->_aclm->getPermisosRole($id), $pagina));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador', 'acl/permisosRole/' . $id));
            $this->_view->setCssPublic(array('jquery.alerts'));
            $this->_view->setJs(array('fnPermisosRole'));
            $this->_view->setJsPlugin(array('jquery.alerts'));
            $this->_view->renderizar('permisosRole', FALSE, 'role');
        }
    }

    /**
     * Método que a través del role enviado vía ajax permitirá validar si el role a añadir existe o no.
     */
    public function verifyExistRole() 
    {
        if ($this->_aclm->getRoleByUsername($this->getSql('role'))) {
            $valid = 'true';
        } else {
            $valid = 'false';
        }
        echo $valid;
    }

    /**
     * Cargar los datos del role para editar
     *
     * @param int $_POST['id']
     *   Id del role a editar.
     *
     * @return
     *   Mostramos a través de Json los datos del role a editar.
     */
    public function getRole() 
    {
        $id = $this->getInt('role');
        $result = $this->_aclm->getRoleById($id);
        echo json_encode($result);
    }

    /**
     * Eliminar de la base de datos uno más roles.
     *
     * @param array $_POST['idRole']
     *   Array con los Id de o los roles a eliminar.
     *
     * @return
     *   Nos redirige al método index enviando un mensaje de acuerdo a si se eliminó o no 
     *   el registro.
     */
    public function deleteRoles() 
    {
        if (isset($_POST['idRole']) && count($_POST['idRole']) > 0) {
            $errores = array();
            $exitos = array();
            foreach ($_POST['idRole'] as $role) {
                $result = $this->_aclm->deleteRole($role);
                if ($result == NULL) {
                    $errores[] = 'No se pudo eliminar el role ' . $role;
                } else {
                    $exitos[] = 'Se elimin&oacute; el role ' . $role;
                }
            }
            $this->_view->assign('_errores', $errores);
            $this->_view->assign('_exitos', $exitos);
        } else {
            $this->_view->assign('_error', 'No ha seleccionado role a eliminar.');
        }
        $this->index();
    }
    
    /**
     * Eliminar un role específico
     * 
     * @param int $id Este parámetro es enviado vía ajax y contiene el ID del role a eliminar
     * 
     * @return str Devuelve un string 0 si no se eliminó o 1 si lo realizó. Esto se devuelve a la 
     * función ajax.
     */
    public function deleteRole() 
    {
        if ($this->getInt('id')) {   
            $result = $this->_aclm->deleteRole($this->getInt('id'));
            if ($result == NULL) {
                echo '0';
            } else {
                echo '1';
            }
        }
    }

    /**
     * Listar los permisos.
     *
     * @param int Spagina
     *  Número de página a listar.
     *
     * @return
     *   Carga la vista con los permisos disponibles.
     */
    public function permisos($pagina = FALSE) 
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            if (!$this->filtrarInt($pagina)) {
                $pagina = false;
            } else {
                $pagina = (int) $pagina;
            }
            
            if ($this->getInt('grabar') && $this->getInt('grabar') == "1") {     
                if ($this->getTexto('txtPermiso')) {
                    if ($this->_aclm->verifyPermiso($this->getSql('txtPermiso'))) {
                        if ($this->getTexto('txtKey')) {
                            if ($this->_aclm->verifyKey($this->getSql('txtKey'))) {
                                if (!$this->_aclm->insertPermiso($this->getSql('txtPermiso'), $this->getSql('txtKey'))) {
                                    $this->_view->assign('_error', 'No se pudo agregar el nuevo permiso. Por favor verifique los datos.');                    
                                } else {
                                    $this->_view->assign('_exito', 'Se agreg&oacute; el nuevo permiso correctamente..');      
                                }
                            } else {
                                $this->_view->assign('_error', 'La clave ingresada ya existe.');
                            }
                        } else {
                            $this->_view->assign('_error', 'No ha ingresado la clave del permiso.');
                        }
                    } else {
                        $this->_view->assign('_error', 'El permiso ingresado ya existe.');
                    }
                } else {
                    $this->_view->assign('_error', 'No ha ingresado el nombre del permiso.');
                }
            }
            
            if ($this->getInt('grabar') && $this->getInt('grabar') == "2") {
                if ($this->getTexto('txtEditPermiso')) {
                    if ($this->getTexto('txtEditPermiso') == $this->getTexto('hd_permiso')) {
                        if ($this->getTexto('txtEditKey')) {
                            if ($this->getTexto('txtEditKey') == $this->getTexto('hd_key')) {
                                if (!$this->_aclm->editPermiso($this->getSql('txtEditPermiso'), $this->getSql('txtEditKey'), $this->getInt('permisoID'))) {
                                    $this->_view->assign('_error', 'No se pudo editar el permiso. Por favor verifique los datos.');                    
                                } else {
                                    $this->_view->assign('_exito', 'Se edit&oacute; el permiso correctamente..');      
                                }
                            } else {
                                if (!$this->_aclm->verifyKey($this->getSql('txtEditKey'))) {
                                    $this->_view->assign('_error', 'La clave ingresado ya existe.');
                                } else {
                                    if (!$this->_aclm->editPermiso($this->getSql('txtEditPermiso'), $this->getSql('txtEditKey'), $this->getInt('permisoID'))) {
                                        $this->_view->assign('_error', 'No se pudo editar el permiso. Por favor verifique los datos.');                    
                                    } else {
                                        $this->_view->assign('_exito', 'Se edit&oacute; el permiso correctamente..');      
                                    }
                                }
                            }
                        } else {
                            $this->_view->assign('_error', 'No ha ingresado la clave del permiso.');
                        }
                    } else {
                        if (!$this->_aclm->verifyPermiso($this->getSql('txtEditPermiso'))) {
                            $this->_view->assign('_error', 'El permiso ingresado ya existe.');    
                        } else {
                            if (!$this->_aclm->editPermiso($this->getSql('txtEditPermiso'), $this->getSql('txtEditKey'), $this->getInt('permisoID'))) {
                                $this->_view->assign('_error', 'No se pudo editar el permiso. Por favor verifique los datos.');                    
                            } else {
                                $this->_view->assign('_exito', 'Se edit&oacute; el permiso correctamente..');      
                            }
                        }
                    }
                } else {
                    $this->_view->assign('_error', 'No ha ingresado el nombre del permiso.');     
                }
            }
            
            $permisos = $this->_aclm->getPermisos();
            if (count($permisos) && is_array($permisos)) {
                if (count($permisos) > 10) {
                    $this->_view->assign('_permisos', $this->_paginador->paginar($permisos, $pagina));
                    $this->_view->assign('paginacion', $this->_paginador->getView('paginador', 'acl/permisos'));
                } else {
                    $this->_view->assign('_permisos', $permisos);
                }
            }
            $this->_view->setCssPublic(array('jquery.alerts'));
            $this->_view->setJs(array('fnPermisos'));
            $this->_view->setJsPlugin(array('jquery.alerts'));
            $this->_view->assign('_validation', 'TRUE');
            $this->_view->assign('titulo', APP_NAME . ' - Administraci&oacute;n de Permisos');
            $this->_view->renderizar('permisos', FALSE, 'permiso');
        }
    }
    
    /**
     * Método que a través del permiso enviado vía ajax permitirá validar si el permiso a añadir existe o no.
     */
    public function verifyPermiso() 
    {
        if ($this->_aclm->verifyPermiso($this->getSql('permiso'))) {
            $valid = 'true';
        } else {
            $valid = 'false';
        }
        echo $valid;
    }
    
    /**
     * Validamos si el key ingresado existe ya en la base de datos
     */
    public function verifyKey()
    {
        if ($this->_aclm->verifyKey($this->getSql('key'))) {
            $valid = 'true';
        } else {
            $valid = ' false';
        }
        echo $valid;
    }
    
    /**
     * Obtenemos vía ajax los datos de un permiso específico
     */
    public function getPermiso() 
    {
        $id = $this->getInt('id');
        $result = $this->_aclm->getPermisoById($id) ;
        echo json_encode($result);
    }
    
    /**
     * Eliminar vía ajax un registro
     */
    public function deletePermiso() 
    {
        if ($this->getInt('id')) {   
            $result = $this->_aclm->deletePermiso($this->getInt('id'));
            if ($result == NULL) {
                echo '0';
            } else {
                echo '1';
            }
        }
    }
    
    public function deletePermisos() 
    {
        if (isset($_POST['idPermiso']) && count($_POST['idPermiso']) > 0) {
            $errores = array();
            $exitos = array();
            foreach ($_POST['idPermiso'] as $perm) {
                $result = $this->_aclm->deletePermiso($perm);
                if ($result == NULL) {
                    $errores[] = 'No se pudo eliminar el permiso ' . $perm;
                } else {
                    $exitos[] = 'Se elimin&oacute; el permiso ' . $perm;
                }
            }
            $this->_view->assign('_errores', $errores);
            $this->_view->assign('_exitos', $exitos);
        } else {
            $this->_view->assign('_error', 'No ha seleccionado permiso a eliminar.');
        }
        $this->permisos();
    }
}
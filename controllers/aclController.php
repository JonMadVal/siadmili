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
            $this->_view->setJs(array('funciones'));
            $this->_view->assign('_validation', 'TRUE');
            $this->_view->assign('titulo', APP_NAME . ' - Administraci&oacute;n de Roles');
            $this->_view->assign('menu_left_active', 'role');            
            $this->_view->renderizar('index');
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
            $this->_view->assign('menu_left_active', 'role');
            $this->_view->assign('permisos', $this->_paginador->paginar($this->_aclm->getPermisosRole($id), $pagina));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador', 'acl/permisosRole/' . $id));
            $this->_view->setJs(array('fnPermisosRole'));
            $this->_view->renderizar('permisosRole');
        }
    }

    /**
     * Método que a través del role enviado vía ajax permitirá validar si el role a añadir existe o no.
     */
    public function verifyExistRole() 
    {
        if ($this->_aclm->getRoleByUsername($this->getAlphaNum('role'))) {
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
     * Eliminar de la base de datos un role específico a través de ajax.
     *
     * @param int $_POST['roleID']
     *   Id del role a eliminar.
     *
     * @return
     *   Nos redirige al método index enviando un mensaje de acuerdo a si se eliminó o no 
     *   el registro.
     */
    public function deleteRole() 
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
        } else if ($this->getInt('roleID')) {
                $result = $this->_aclm->deleteRole($this->getInt('roleID'));
                if ($result == NULL) {
                    $this->_view->assign('_error', 'No se pudo eliminar el role. Por favor vuelva a intentarlo.');
                } else {
                    $this->_view->assign('_exito', 'Se elimin&oacute; el role.');
                }
        } else {
            $this->_view->assign('_error', 'No ha seleccionado role a eliminar.');
        } 
        $this->index();
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
                $pagina = FALSE;
            } else {
                $pagina = (int) $pagina;
            }
            $paginador = new Paginador();
            $result = $this->_acl->getPermisos();
            $limite = '10';
            $this->_view->assign('permisos', $paginador->paginar($result, $pagina, $limite));
            $this->_view->assign('paginacion', $paginador->getView('paginador', 'acl/permisos'));
            $this->_view->setJs(array('fnPermisos'));
            $this->_view->setCssPublic(array('jquery.alerts', 'ui-darkness/jquery-ui-1.8.18.custom'));
            $this->_view->setJsPublic(array('funciones', 'clockp', 'clockh', 'jquery-ui-1.8.18.custom.min', 'jquery.alerts', 'jquery.form'));
            $this->_view->assign('titulo', APP_NAME . ' - Listas de Acceso');
            $this->_view->renderizar('permisos');
        }
    }

    /**
     * Guardar un nuevo permiso o editar permiso existente.
     *
     * @param int Spagina
     *  Número de página a listar.
     *
     * @return
     *   Carga la vista con los permisos disponibles.
     */
    public function setPermiso() 
    {
        if (isset($_POST['optEdit']) && $_POST['optEdit'] == '1') {
            // Verificamos el txtPermisoEdit tenga dato y que no este registrado
            if (!$this->getPostParam('txtPermisoEdit')) {
                echo 'Debe introducir el nombre del permiso.<br />';
                exit;
            } else {
                if ($this->getPostParam('txtPermisoEdit') != $this->getPostParam('namePermiso')) {
                    if (!$this->_acl->getPermisoByUsername($this->getPostParam('txtPermisoEdit'))) {
                        echo 'El permiso ingresado ya esta registrado.<br />';
                        exit;
                    }
                }
            }

            // Verificamos el txtKeyEdit tenga dato y que no este registrado
            if (!$this->getPostParam('txtKeyEdit')) {
                echo 'Debe introducir el key del permiso.<br />';
                exit;
            } else {
                if ($this->getPostParam('txtKeyEdit') != $this->getPostParam('nameKey')) {
                    if (!$this->_acl->verifyKeyExist($this->getPostParam('txtKeyEdit'))) {
                        echo 'El key ingresado ya esta registrado.<br />';
                        exit;
                    }
                }
            }
            $r = $this->_acl->editPermisoById($this->getPostParam('txtPermisoEdit'), $this->getPostParam('txtKeyEdit'), $this->filtrarInt('id'));
            if ($r) {
                echo 'Se edit&oacute; correctamente el permiso';
                exit;
            } else {
                echo 'Verifique que los datos ingresados sean correctos';
                exit;
            }
        } else {
            if (!$this->getPostParam('txtPermiso')) {
                echo 'Debe introducir el nombre del permiso.<br />';
                exit;
            }
            if (!$this->getPostParam('txtKey')) {
                echo 'Debe introducir el key del permiso.<br />';
                exit;
            }
            $r = $this->_acl->insertPermiso($this->getPostParam('txtPermiso'), $this->getPostParam('txtKey'));
            if ($r) {
                echo 'Se ingres&oacute; correctamente el permiso';
                exit;
            } else {
                echo 'Verifique que los datos ingresado son correctos';
                exit;
            }
        }
    }

    /**
     * Cargar los datos del permiso para editar
     *
     * @param int $_POST['id']
     *   Id del permiso a editar.
     *
     * @return
     *   Mostramos a través de Json los datos del permiso a editar.
     */
    public function editPermiso() 
    {
        $id = $this->filtrarInt($_POST['id']);
        $result = $this->_acl->getPermisoById($id);
        if (!$result) {
            exit;
        }
        if (is_array($result)) {
            foreach ($result as $perm) {
                echo json_encode($perm);
            }
        }
    }

    /**
     * Eliminar de la base de datos un permiso específico a través de ajax.
     *
     * @param int $_POST['id']
     *   Id del permiso a eliminar.
     *
     * @return
     *   Mostrará 0 en caso falle o 1 en caso se haya eliminado.
     */
    public function deletePermiso() 
    {
        $id = $this->filtrarInt($_POST['id']);
        $result = $this->_acl->deletePermiso($id);
        if (!$result) {
            echo '0';
        } else {
            echo '1';
        }
    }

}
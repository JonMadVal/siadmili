<?php

/*
 * Nombre       :   loginController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que permitirá el login del usuario
 */

class loginController extends Controller 
{

    private $_login;
    private $_users;

    public function __construct() 
    {
        parent::__construct();
        $this->_login = $this->loadModel('users');
        $this->_users = array();
    }

    /**
     * Cargará la vista por defecto.
     */
    public function index() 
    {
        if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
            $this->_view->setJs(array('funciones'));
            $this->_view->assign('titulo', APP_NAME . ' - Login');
            $this->_view->assign('_validation', 'TRUE');
            
            // Verificamos si hemos presionado el botón de login
            if (isset($_POST['grabar']) && $this->getInt('grabar') == '1') {
                $this->_view->assign('datos', $_POST);

                if (!$this->getTexto('username')) {
                    $this->_view->assign('_error', 'Debe introducir su nombre de usuario.');
                    $this->_view->renderizar('index');
                    exit;
                }

                if (!$this->getTexto('password')) {
                    $this->_view->assign('_error', 'Debe introducir su password.');
                    $this->_view->renderizar('index');
                    exit;
                }

                $datos = array (
                    'username' => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING),
                    'pass' => filter_input(INPUT_POST, 'password', FILTER_SANITIZE_URL));
                $this->_users = $this->_login->login($datos);

                if (is_array($this->_users) && count($this->_users)) {
                    Session::set('userID', $this->_users['userID']);
                    Session::set('login', $this->_users['login']);
                    Session::set('logged_in', TRUE);
                    Session::set('tiempo', time());
                    Session::set('role', $this->_users['role']);
                    $this->redirect('index');
                } 
                else {
                    $this->_view->assign('_error', 'Los datos ingresados no son correctos.');
                    $this->_view->renderizar('index');
                    exit;
                }
            }             
            $this->_view->renderizar('index');
        } else {
            $this->redirect('index');
        }
    }

    /**
     * Realizar el deslogueo del usuario.
     */
    public function logout() 
    {
        Session::destroy();
        $this->redirect('login');
    }

}
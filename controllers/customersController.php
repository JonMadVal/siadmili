<?php
/*
 * Nombre       :   customersController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que hará el mantenimiento de la tabla Customers
 */

class customersController extends Controller {
  private $_cust;
  
  public function __construct() {
    parent::__construct();
    $this->_cust = $this->loadModel('customers');
  }
  
  public function index($pagina = FALSE) {
    if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
      header('location: ' . BASE_URL . 'error/access/5050');
      exit;
    } else {
      if (!$this->filtrarInt($pagina)) {
        $pagina = FALSE;
      } else {
        $pagina = (int) $pagina;
      }
      
      $this->getLibrary('paginador');
      $paginador = new Paginador();
      $result = $this->_cust->getCustomers();
      $limite = '2';
      $this->_view->assign('customers', $paginador->paginar($result, $pagina, $limite));
      $this->_view->assign('paginacion', $paginador->getView('paginador', 'customers/index'));
      //$this->_view->setJs(array('funciones'));
      $this->_view->setCssPublic(array('jquery.alerts', 'ui-darkness/jquery-ui-1.8.18.custom'));
      $this->_view->setJsPublic(array('funciones', 'clockp', 'clockh', 'jquery-ui-1.8.18.custom.min', 'jquery.alerts', 'jquery.form'));
      $this->_view->assign('titulo', APP_NAME . ' - Mantenimiento de Clientes');
      $this->_view->renderizar('index', 'mantenimiento');
    }
  }
}
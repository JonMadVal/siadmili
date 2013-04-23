<?php
/*
 * Nombre       :   errorController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Clase que tratará los mensajes de error de nuestro sistema
 */

class errorController extends Controller {
  public function __construct() {
    parent::__construct();
  }

  /**
   * Cargará la vista por defecto mostrando el mensaje de error predeterminado.
   */
  public function index() {
    $this->_view->assign('titulo', APP_NAME . ' - Error');
    $this->_view->assign('_mensaje', $this->_getError());
    $this->_view->renderizar('index');
  }

  /**
   * Cargará la vista de acuerdo al código de mensaje enviado.
   *
   * @param int $codigo
   *   Código del mensaje a obtener.
   */
  public function access($codigo = FALSE) {
    $this->_view->assign('titulo', APP_NAME . ' - Error');
    $mensaje = $this->_getError($codigo);
    $this->_view->assign('_mensaje', $mensaje);
    $this->_view->renderizar('access');
  }

  /**
   * Obtener mensaje de error de acuerdo al código enviado.
   *
   * @param int $codigo
   *   Código del mensaje a obtener.
   *
   * @return
   *   Mensaje del error.
   */
  private function _getError($codigo = FALSE) {
    if ($codigo) {
      $codigo = $this->filtrarInt($codigo);
      if (is_int($codigo)) {
        $codigo = $codigo;
      }
    } 
    else {
      $codigo = 'default';
    }

    $error['default'] = 'Ha ocurrido un error y la p&aacute;gina no puede mostrarse';
    $error['5050'] = 'Acceso restringido!';
    $error['1234'] = 'Tiempo de la sesi&oacute;n agotado!';

    if (array_key_exists($codigo, $error)) {
      return $error[$codigo];
    } 
    else {
      return $error['default'];
    }
  }
}
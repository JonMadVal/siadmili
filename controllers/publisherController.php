<?php

/*
 * Nombre       :   publisherController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que realizará el mantenimiento con la tabla Pubblisher
 */

class publisherController extends Controller {
  private $_pub;
  private $_validator;

  public function __construct() {
    parent::__construct();
    $this->_pub = $this->loadModel('publisher');
    $this->getLibrary('Validator');
    $this->_validator = new Validator();
  }

  /**
   * Cargará la vista por defecto.
   */
  public function index() {
    if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
      header('location: ' . BASE_URL . 'error/access/5050');
      exit;
    } else {
      $this->_view->setJs(array('funciones'));
      $this->_view->setCssPublic(array('jquery.alerts', 'ui-darkness/jquery-ui-1.8.18.custom'));
      $this->_view->setJsPublic(array('funciones', 'clockp', 'clockh', 'jquery-ui-1.8.18.custom.min', 'jquery.alerts', 'jquery.form'));
      $this->_view->assign('titulo', APP_NAME . ' - Mantenimiento de Editoriales');
      $this->_view->renderizar('index', 'mantenimiento');
    }
  }

  /**
   * Permite realizar validación a través de Ajax.
   */
  public function validatePublisherAjax() {
    $response =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<response>' .
            '<result>' .
            $this->_validator->validateAJAX($_POST['inputValue'], $_POST['fieldID']) .
            '</result>' .
            '<fieldid>' .
            $_POST['fieldID'] .
            '</fieldid>' .
            '</response>';
    // genera la respuesta
    if (ob_get_length())
      ob_clean();
    header('Content-Type: text/xml');
    echo $response;
  }

  /**
   * Realiza la validación de los datos de la nueva editorial y finalmente nos 
   * mostrará un mensaje de acuerdo a la respuesta obtenida.
   *
   * @param $_POST
   *   Datos de la nueva editorial.
   *
   * @return
   *   Mensaje de error en caso no pase las validaciones y éxito en caso se haya
   *   ingresado la nueva editorial.
   */
  public function verifyAddPublisher() {
    if (!$this->getPostParam('txtPublisher')) {
      echo 'Debe introducir el nombre de la editorial.<br />';
      exit;
    }

    $insert = $this->_pub->insertPublisher($this->getPostParam('txtPublisher'), $this->getPostParam('txtDescription'));
    if ($insert) {
      echo 'Se ingres&oacute; correctamente la nueva editorial.';
    }
  }

  /**
   * Mostrar los datos de editoriales para realizar la paginación a través de ajax
   *
   * @param int $_POST
   *   Número de página a mostrar en la paginación.
   *
   * @return
   *   Carga la vista con los datos de las editoriales.
   */
  public function displayPublisher() {
    if ($this->filtrarInt($_POST['page'])) {
      $page = $this->filtrarInt($_POST['page']);
      $cur_page = $page;
      $page -= 1;
      $per_page = 5; // Per page records
      $previous_btn = true;
      $next_btn = true;
      $first_btn = true;
      $last_btn = true;
      $start = $page * $per_page;

      $this->_view->assign('_result', $this->_pub->getPublishersPagination($start, $per_page));
      /* -----Total count--- */
      $result_pag_num = $this->_pub->getTotalRow();
      $count = $result_pag_num['Total'];
      $no_of_paginations = ceil($count / $per_page);

      /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
      if ($cur_page >= 5) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3) {
          $end_loop = $cur_page + 3;
        } else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 4) {
          $start_loop = $no_of_paginations - 4;
          $end_loop = $no_of_paginations;
        } else {
          $end_loop = $no_of_paginations;
        }
      } else {
        $start_loop = 1;
        if ($no_of_paginations > 5) {
          $end_loop = 5;
        } else {
          $end_loop = $no_of_paginations;
        }
      }
      $this->_view->assign('first_btn', $first_btn);
      $this->_view->assign('previous_btn', $previous_btn);
      $this->_view->assign('next_btn', $next_btn);
      $this->_view->assign('last_btn', $last_btn);
      $this->_view->assign('cur_page', $cur_page);
      $this->_view->assign('start_loop', $start_loop);
      $this->_view->assign('end_loop', $end_loop);
      $this->_view->assign('no_of_paginations', $no_of_paginations);
      $this->_view->renderizar('displayPublisher', 'mantenimiento', TRUE);
    }
  }

  /**
   * Cargar los datos de la editorial para editar
   *
   * @param int $_POST['id']
   *   Id de la editorial a editar.
   *
   * @return
   *   Mostramos a través de Json los datos de la editorial a editar.
   */
  public function editPublisher() {
    $id = $this->filtrarInt($_POST['id']);
    $result = $this->_pub->getPublisherById($id);
    if (!$result) {
      exit;
    }
    if (is_array($result)) {
      foreach ($result as $pubs) {
        echo json_encode($pubs);
      }
    }
  }

  /**
   * Realiza la validación de los datos de la editorial a editar, y finalmente 
   * nos mostrará un mensaje de acuerdo a la respuesta obtenida.
   *
   * @param $_POST
   *   Datos de editorial a editar.
   *
   * @return
   *   Mensaje de error en caso no pase las validaciones y éxito en caso se haya
   *   editado la editorial.
   */
  public function verifyEditPublisher() {
    if (!$this->getPostParam('txtPublisherEdit')) {
      echo 'Debe introducir el nombre de editorial.<br />';
      exit;
    }

    if ($this->_pub->updatePublisher($this->getPostParam('txtPublisherEdit'), $this->getPostParam('txtDescription'), $_POST['hdId'])) {
      echo 'La editorial se edit&oacute; satisfactoriamente.';
    }
  }

  /**
   * Eliminar de la base de datos editorial específico a través de ajax.
   *
   * @param int $_POST['id']
   *   Id de editorial a eliminar.
   *
   * @return
   *   Mostrará 0 en caso falle o 1 en caso se haya eliminado.
   */
  public function deletePublisher() {
    $id = $this->filtrarInt($_POST['id']);
    $result = $this->_pub->deletePublisher($id);
    if (!$result) {
      echo '0';
    } else {
      echo '1';
    }
  }

}
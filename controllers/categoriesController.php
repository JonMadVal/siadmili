<?php

/*
 * Nombre       :   categoriesController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que realizará el mantenimiento con la tabla Categories
 */

class categoriesController extends Controller {

  private $_cat;
  private $_validator;

  public function __construct() {
    parent::__construct();
    $this->_cat = $this->loadModel('categories');
    $this->getLibrary('validator');
    $this->_validator = new Validator();
  }

  public function index() {
    if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
      header('location: ' . BASE_URL . 'error/access/5050');
      exit;
    } else {
      $this->_view->setJs(array('funciones'));
      $this->_view->setCssPublic(array('jquery.alerts', 'ui-darkness/jquery-ui-1.8.18.custom'));
      $this->_view->setJsPublic(array('funciones', 'clockp', 'clockh', 'jquery-ui-1.8.18.custom.min', 'jquery.alerts', 'jquery.form'));
      $this->_view->assign('titulo', APP_NAME . ' - Mantenimiento de Categor&iacute;as');
      $this->_view->renderizar('index', 'mantenimiento');
    };
  }
  
  /**
   * Mostrar los datos de categorías para realizar la paginación a través de ajax
   *
   * @param int $_POST
   *   Número de página a mostrar en la paginación.
   *
   * @return
   *   Carga la vista con los datos de las categorías.
   */
  public function displayCategories() {
    if ($this->filtrarInt($_POST['page'])) {
      $page = $this->filtrarInt($_POST['page']);
      $cur_page = $page;
      $page -= 1;
      $per_page = 10; // Per page records
      $previous_btn = true;
      $next_btn = true;
      $first_btn = true;
      $last_btn = true;
      $start = $page * $per_page;

      $this->_view->assign('_result', $this->_cat->getCategoriesPagination($start, $per_page));
      /* -----Total count--- */
      $result_pag_num = $this->_cat->getTotalRow();
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
      $this->_view->renderizar('displayCategories', 'mantenimiento', TRUE);
    }
  }
  
  /**
   * Permite realizar validación a través de Ajax.
   */
  public function validateCategoryAjax() {
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
   * Realiza la validación de los datos de la nueva categoría y finalmente nos 
   * mostrará un mensaje de acuerdo a la respuesta obtenida.
   *
   * @param $_POST
   *   Datos de la nueva editorial.
   *
   * @return
   *   Mensaje de error en caso no pase las validaciones y éxito en caso se haya
   *   ingresado la nueva categoría.
   */
  public function verifyAddCategories() {
    if (!$this->getPostParam('txtCategory')) {
      echo 'Debe introducir el nombre de la categor&iacute;a.<br />';
      exit;
    }

    $insert = $this->_cat->insertCategory($this->getPostParam('txtCategory'));
    if ($insert) {
      echo 'Se ingres&oacute; correctamente la nueva categor&iacute;a.';
    }
  }
  
  /**
   * Cargar los datos de la categoría para editar
   *
   * @param int $_POST['id']
   *   Id de la categoría a editar.
   *
   * @return
   *   Mostramos a través de Json los datos de la categoría a editar.
   */
  public function editCategory() {
    $id = $this->filtrarInt($_POST['id']);
    $result = $this->_cat->getCategoryById($id);
    if (!$result) {
      exit;
    }
    if (is_array($result)) {
      foreach ($result as $cats) {
        echo json_encode($cats);
      }
    }
  }
  
  /**
   * Realiza la validación de los datos de la categoría a editar, y finalmente 
   * nos mostrará un mensaje de acuerdo a la respuesta obtenida.
   *
   * @param $_POST
   *   Datos de categoría a editar.
   *
   * @return
   *   Mensaje de error en caso no pase las validaciones y éxito en caso se haya
   *   editado la categoría.
   */
  public function verifyEditCategory() {
    if (!$this->getPostParam('txtCategoryEdit')) {
      echo 'Debe introducir el nombre de categor&iacute;a.<br />';
      exit;
    }

    if ($this->_cat->updateCategory($this->getPostParam('txtCategoryEdit'), $_POST['hdId'])) {
      echo 'La categor&iacute;a se edit&oacute; satisfactoriamente.';
    }
  }
  
  /**
   * Eliminar de la base de datos categoría específico a través de ajax.
   *
   * @param int $_POST['id']
   *   Id de categoría a eliminar.
   *
   * @return
   *   Mostrará 0 en caso falle o 1 en caso se haya eliminado.
   */
  public function deleteCategory() {
    $id = $this->filtrarInt($_POST['id']);
    $result = $this->_cat->deleteCategory($id);
    if (!$result) {
      echo '0';
    } else {
      echo '1';
    }
  }
}
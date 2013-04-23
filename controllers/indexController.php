<?php

/*
 * Nombre       :   indexController.php
 * Proyecto     :   Admin - Librería
 * Descripción  :   Cargará nuestro controlador principal
 */

class indexController extends Controller 
{

    private $_index;

    public function __construct() 
    {
        parent::__construct();
        $this->_index = $this->loadModel('menu');
    }

    /**
     * Cargará la vista por defecto.
     */
    public function index() 
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            $menus = $this->_index->getMenus();
            if (is_array($menus) && count($menus)) {
                $this->_view->assign('menus', $menus);
                $submenus = $this->_index->getSubmenus();            
                if (is_array($submenus) && count($submenus)) {
                    $this->_view->assign('submenus', $submenus);
                }   
            }
            $this->_view->assign('titulo', APP_NAME . ' - Dashboard');
            $this->_view->renderizar('index', 'Dashboard');
        }
    }

}
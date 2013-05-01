<?php

/*
 * Nombre       :   indexController.php
 * Proyecto     :   Admin - Librería
 * Descripción  :   Cargará nuestro controlador principal
 */

class indexController extends Controller 
{
    public function __construct() 
    {
        parent::__construct();
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
            $this->_view->assign('titulo', APP_NAME . ' - Dashboard');
            $this->_view->renderizar('index', 'Dashboard');
        }
    }

}
<?php

/*
 * Nombre       :   Request.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Recogemos el controlador, método y argumentos enviados a 
 *                  través del $_GET['url']
 */

class Request 
{
    private $_modulo;
    private $_controlador;
    private $_metodo;
    private $_argumentos;
    private $_modules;

    public function __construct() 
    {
        if (isset($_GET['url'])) {
            /* Filtramos lo que nos viene en $_GET['url'] y quita caracteres
             * especiales */
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            // Divide en array $url separando a través del /
            $url = explode('/', $url);
            // Busca en el array $url si existe FALSE, TRUE o ''
            $url = array_filter($url);
            
            $this->_modules = array();
            $this->_modulo = strtolower(array_shift($url));
            
            if (!$this->_modulo) {
                $this->_modulo = FALSE;
            } else {
                if (count($this->_modules)) {
                    if (!in_array($this->_modulo, $this->_modules)) {
                        $this->_controlador = $this->_modulo;
                        $this->_modulo = FALSE;
                    } else {
                        $this->_controlador = strtolower(array_shift($url));
                        if (!$this->_controlador) {
                            $this->_controlador = 'index';
                        }
                    }
                } else {
                    $this->_controlador = $this->_modulo;
                    $this->_modulo = FALSE;
                }
            }
            
            $this->_metodo = strtolower(array_shift($url));
            $this->_argumentos = $url;
        }     

        if (!$this->_controlador) {
            $this->_controlador = DEFAULT_CONTROLLER;
        }

        if (!$this->_metodo) {
            $this->_metodo = 'index';
        }

        if (!$this->_argumentos) {
            $this->_argumentos = array();
        }
    }
    
    public function getModulo()
    {
        return $this->_modulo;
    }
    
    public function getControlador()
    {
        return $this->_controlador;
    }
    
    public function getMetodo()
    {
        return $this->_metodo;
    }
    
    public function getArgs()
    {
        return $this->_argumentos;
    }

}
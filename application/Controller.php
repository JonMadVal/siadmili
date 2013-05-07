<?php

/*
 * Nombre       :   Controller.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Clase Abstracta de la que heredarán los controladores que 
 *                  crearemos. Creamos un método abstracto lo que obligará a 
 *                  crear este método en las clases que hereden de esta.
 */

abstract class Controller
{
    private $_registry;
    protected $_view;
    protected $_acl;
    protected $_db;
    protected $_request;

    public function __construct() 
    {
        $this->_registry = Registry::getInstancia();
        $this->_acl = $this->_registry->_acl;
        $this->_request = $this->_registry->_request;
        $this->_view = new View($this->_request, $this->_acl);
    }

    abstract public function index();

    protected function loadModel($modelo, $modulo = FALSE)
    {
        $modelo = $modelo . 'Model';
        $rutaModelo = ROOT . 'models' . DS . $modelo . '.php';
        
        if (!$modulo) {
            $modulo = $this->_request->getModulo();
        }
        
        if ($modulo) {
            if ($modulo != 'default') {
                $rutaModelo = ROOT . 'modules' . DS . $modulo . DS . 'models' .DS . $modelo . '.php';
            }
        }

        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
            $modelo = new $modelo;
            return $modelo;
        } else {
            throw new Exception('Error en modelo');
        }
    }

    // Método que permite cargar la librería
    protected function getLibrary($libreria) 
    {
        $rutaLibreria = ROOT . 'libs' . DS . $libreria . '.php';

        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        } else {
            throw new Exception('Error de libreria');
        }
    }

    /**
     * Toma variable enviada vía POST, la filtra y la retorna
     * @param string $clave
     * @return string
     */
    protected function getTexto($clave) 
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
            return $_POST[$clave];
        }
        return '';
    }

    /**
     * Filtrar variable POST que sea número enteros
     * @param string $clave
     * @return int
     */
    protected function getInt($clave) 
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }
        return 0;
    }

    /**
     * Filtra valores numéricos enviados via GET
     * @param string $int
     * @return int
     */
    protected function filtrarInt($int) 
    {
        $int = (int) $int;
        if (is_int($int)) {
            return $int;
        } else {
            return 0;
        }
    }

    /**
     * Devuelve variable POST sin filtrar
     * @param string $clave
     * @return string
     */
    protected function getPostParam($clave)
    {
        if (isset($_POST[$clave])) {
            return $_POST[$clave];
        }
    }

    protected function getSql($clave) 
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = strip_tags($_POST[$clave]);

            if (!get_magic_quotes_gpc()) {
                //$_POST[$clave] = PDO::quote($_POST[$clave]);
                // Ojo debemos utilizar una función que sanitice string.
                $_POST[$clave] = $_POST[$clave];                
            }

            return trim($_POST[$clave]);
        }
    }

    /**
     * Permite filtrar variable aceptando solamente letras, números y @
     * @param type $clave
     * @return type
     */
    protected function getAlphaNum($clave) 
    {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }
    
    public function validarEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return FALSE;
        }
        return TRUE;
    }

    protected function redirect($ruta = FALSE, $msj = FALSE) 
    {
        if ($ruta) {
            header('Location: ' . BASE_URL . $ruta);
            exit;
        } else {
            header('Location: ' . BASE_URL);
            exit;
        }
    }
}
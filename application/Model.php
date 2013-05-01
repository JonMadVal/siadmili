<?php

/*
 * Nombre       :   Model.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Creamos una instancia de la clase Database
 */

class Model
{
    private $_registry;
    protected $_dbh;
    
    public function __construct() 
    {
        $this->_registry = Registry::getInstancia();
        $this->_dbh = $this->_registry->_db;
    }
    
    /**
     * Método que permite proteger envio de datos de ataques, pero nos sirve cuando
     * utilizamos conexión con mysqli
     * @param type $valor
     * @return string
     */
    public function comillas_inteligentes($valor)
    {
        // Retirar las barras
	if (get_magic_quotes_gpc()) {
            $valor = stripslashes($valor);
	}
	
	// Colocar comillas si no es entero
	if (!is_numeric($valor)) {
            $valor = "'" . mysql_real_escape_string($valor) . "'";
	}
	return $valor;
    }
}
?>

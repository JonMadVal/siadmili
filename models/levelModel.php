<?php

/*
 * Nombre       :   levelModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla Level
 */

class levelModel extends Model 
{
    private $_level;

    public function __construct() 
    {
        parent::__construct();
        $this->_level = array();
    }

    /**
     * Obtener los niveles que tenemos en nuestro sistema.
     * 
     * @return
     *   False en caso de error con la consulta o array con los niveles almacenados.
     */
    public function getLevels() 
    {
        $stmt = $this->_dbh->query("SELECT roleID, role FROM roles;");
        $this->_level = $stmt->fetchAll();
        return $this->_level;
        $this->_dbh = null;
    }

}
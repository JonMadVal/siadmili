<?php
/*
 * Nombre       :   menuModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla menu y submenu
 */

class menuModel extends Model 
{
    private $_menu;
    
    public function __construct() 
    {
        parent::__construct();
        $this->_menu = array();
    }
    
    public function getMenus() 
    {
        $stmt = $this->_dbh->query("SELECT `menuID`, `menu`, `enlace` FROM `menu`;");
        $this->_menu = $stmt->fetchAll();
        return $this->_menu;
        $this->_dbh = null;
    }
    
    public function getSubMenus() 
    {
        $stmt = $this->_dbh->query("SELECT `submenuID`, `submenu`, `enlace`, `menuID` FROM `submenu`;");
        $this->_menu = $stmt->fetchAll();
        return $this->_menu;
        $this->_dbh = null;
    }
}
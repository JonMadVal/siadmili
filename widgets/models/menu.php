<?php

class menuModelWidget extends Model 
{
    private $_menu;

    public function __construct() 
    {
        parent::__construct();
        $this->_menu = array();
    }
    
    public function getMenu()
    {
        $stmt = $this->_dbh->query("SELECT `menuID`, `menu`, `enlace` FROM `menu`;");
        $this->_menu = $stmt->fetchAll();
        return $this->_menu;
        $this->_dbh = null;
    }
    
    public function getSubMenus() 
    {
        $stmt = $this->_dbh->query("SELECT `submenuID`, `submenu`, `enlace`, `menuID` FROM `submenu`;");
        $this->_submenu = $stmt->fetchAll();
        return $this->_submenu;
        $this->_dbh = null;
    }
}
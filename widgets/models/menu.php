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
        if(Session::get('role')) {
            $roleID = Session::get('role');
        } else {
            $roleID = 0;
        }
        $stmt = $this->_dbh->query("SELECT `m`.`menuID`, `m`.`menu`, `m`.`enlace` FROM `menu` as `m`, `menu_roles` as `mr` "
                ."WHERE `m`.`menuID` = `mr`.`menuID` AND `mr`.`roleID` = $roleID;");
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
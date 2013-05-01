<?php
class menuWidget extends Widget 
{
    private $_modelo;
    
    public function __construct() 
    {
        $this->_modelo = $this->loadModel('menu');
    }
    
    public function getMenu($item) 
    {
        $data['menu'] = $this->_modelo->getMenu();
        $data['item'] = $item;
        $data['submenus'] = $this->_modelo->getSubMenus();
        return $this->render('menu-main', $data);
    }
    
    public function getConfig() 
    {
        return array(
            'position' => 'menu',
            'show' => 'all',
            'hide' => array()
        );
    }
}
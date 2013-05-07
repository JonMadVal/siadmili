<?php
class aclWidget extends Widget 
{
    public function __construct() 
    {
    }
    
    public function getAcl($itemAcl) 
    {
        $data['itemAcl'] = $itemAcl;
        return $this->render('acl-main', $data);
    }
    
    public function getConfig() 
    {
        return array(
            'position' => 'sidebar',
            'show' => 'all',
            'hide' => array()
        );
    }
}
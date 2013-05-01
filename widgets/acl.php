<?php
class aclWidget extends Widget 
{
    public function __construct() 
    {
    }
    
    public function getAcl() 
    {
        $data['role'] = "role";
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
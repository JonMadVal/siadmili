<?php

/*
 * Nombre       :   Database.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Definimos parametros de configuración que se utilizarán
 *                  para realizar la conexión a la base de datos
 */

class Database extends PDO  
{
    public function __construct() 
    {
        parent::__construct(
                'mysql:host=' . DB_HOST .
                ';dbname=' . DB_NAME, DB_USER, DB_PASS, 
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHAR));
    }

    public function con() 
    {
        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $con->query("SET NAMES 'utf8'");
        if ($con->connect_errno) {
            die('Error de conexi&oacute;n: ' . $con->connect_error);
        }
        return $con;
    }
}
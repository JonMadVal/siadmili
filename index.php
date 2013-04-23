<?php

ini_set('display_errors', '1');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('APP_PATH', ROOT . 'application' . DS);

try {
    require_once APP_PATH . 'Autoload.php';
    require_once APP_PATH . 'Config.php';

    // echo uniqid(); exit;
    //echo Hash::getHash('sha1', '123456', HASH_KEY); exit;
    Session::init();

    $registry = Registry::getInstancia();
    $registry->_request = new Request();
    $registry->_db = new Database();
    $registry->_acl = new ACL();

    //print_r(get_required_files());
    Bootstrap::run($registry->_request);
} catch (Exception $e) {
    $e->getMessage();
}
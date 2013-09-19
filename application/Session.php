<?php

/*
 * Nombre       :   Session.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Clase que permitirá declarar, asignar, recuperar y destruir 
 *                  variables de sesión.
 */

class Session
{
    // Iniciamos session
    public static function init() 
    {
        session_start();
    }

    // Destruimos una o varias variable de session
    public static function destroy($clave = FALSE) 
    {
        if ($clave) {
            if (is_array($clave)) {
                for ($i = 0; $i < count($clave); $i++) {
                    if (isset($_SESSION[$clave[$i]])) {
                        unset($_SESSION[$clave[$i]]);
                    }
                }
            } else {
                if (isset($_SESSION[$clave])) {
                    unset($_SESSION[$clave]);
                }
            }
        } else {
            session_destroy();
        }
    }

    public static function set($clave, $valor) {
        if (!empty($clave)) {
            $_SESSION[$clave] = $valor;
        }
    }

    public static function get($clave) {
        if (isset($_SESSION[$clave])) {
            return $_SESSION[$clave];
        }
    }

    /*
      public static function acceso($level) {
      if (!Session::get('logged_in')) {
      header('location: ' . BASE_URL . 'error/access/5050');
      exit;
      }

      Session::tiempo();

      if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
      header('location: ' . BASE_URL . 'error/access/5050');
      exit;
      }
      }

      public static function accessView($level) {
      if (!Session::get('logged_in')) {
      return FALSE;
      }

      if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
      return FALSE;
      }

      return TRUE;
      }
     */

    public static function getLevel($level) {
        $role['admin'] = 3;
        $role['especial'] = 2;
        $role['usuario'] = 1;

        if (!array_key_exists($level, $role)) {
            throw new Exception('Error de acceso');
        } else {
            return $role[$level];
        }
    }

    /*
      public static function accesoEstricto(array $level, $noAdmin = FALSE) {
      if (!Session::get('logged_in')) {
      header('location: ' . BASE_URL . 'error/access/5050');
      exit;
      }

      Session::tiempo();

      if ($noAdmin == FALSE) {
      if (Session::get('level') == 'admin') {
      return;
      }
      }

      if (count($level)) {
      if (in_array(Session::get('level'), $level)) {
      return;
      }
      }

      header('location: ' . BASE_URL . 'error/access/5050');
      exit
      }

      public static function accesoViewEstricto(array $level, $noAdmin = FALSE) {
      if (!Session::get('logged_in')) {
      return FALSE;
      }

      if ($noAdmin == FALSE) {
      if (Session::get('level') == 'admin') {
      return TRUE;
      }
      }

      if (count($level)) {
      if (in_array(Session::get('level'), $level)) {
      return TRUE;
      }
      }

      return FALSE;
      }
     */

    /**
     * Tiempo de duración de sesión mientras que el usuario permanece inactivo
     * @return type
     * @throws Exception
     */
    public static function tiempo() {
        if (!Session::get('tiempo') || !defined('SESSION_TIME')) {
            throw new Exception('No se ha definido el tiempo de sesion');
        }
        
        // Definimos tiempo de sesión indefinido
        if (SESSION_TIME == 0) {
            return;
        }

        if (time() - Session::get('tiempo') > (SESSION_TIME * 60)) {
            Session::destroy();
            header('location: ' . BASE_URL . 'error/access/1234');
        } else {
            Session::set('tiempo', time());
        }
    }

}
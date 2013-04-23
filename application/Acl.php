<?php

/*
 * Nombre       :   ACL.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   
 */

class ACL 
{
    private $_registry;
    private $_dbh;       // Objeto de la bases de datos
    private $_id;       // Id del usuario a aplicar lista de acceso
    private $_role;     // Id del rol con el cual se esta trabajando
    private $_permisos; // Permisos del rol

    public function __construct($id = FALSE) 
    {
        if ($id) {
            $this->_id = (int) $id;
        } else {
            if (Session::get('userID')) {
                $this->_id = Session::get('userID');
            } else {
                $this->_id = 0;
            }
        }
        
        $this->_registry = Registry::getInstancia();
        $this->_dbh = $this->_registry->_db;
        $this->_role = $this->getRole();
        $this->_permisos = $this->getPermisosRole();
        $this->compilarAcl();
    }

    /* Combinar los array de los permisos del rol y del usuario */
    public function compilarAcl() 
    {
        $this->_permisos = array_merge($this->_permisos, $this->getPermisosUsuario());
    }

    /* Obtenemos el role del usuario */

    public function getRole() 
    {
        $stmt = $this->_dbh->prepare("SELECT `role` FROM `users` WHERE `userID` = :userID");
        $stmt->bindParam(':userID', $this->_id, PDO::PARAM_INT);
        $stmt->execute();
        $role = $stmt->fetch();
        return $role['role'];
        $this->_dbh = NULL;
    }

    //  Obtener todos los permisos asignados al role del usuario
    public function getPermisosRoleId() 
    {
        $stmt = $this->_dbh->query("SELECT `id_permiso` FROM `permisos_role` WHERE `roleID` = '{$this->_role}'");
        $ids = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $id = array();
        for ($i = 0; $i < count($ids); $i++) {
            $id[] = $ids[$i]['id_permiso'];
        }
        return $id;
        $this->_dbh = NULL;
    }

    // Obtenemos los permisos ya procesados
    public function getPermisosRole() 
    {
        $stmt = $this->_dbh->prepare("SELECT `roleID`, `id_permiso`, `valor` FROM `permisos_role` WHERE `roleID` = :roleID");
        $stmt->bindParam(':roleID', $this->_role, PDO::PARAM_INT);
        $stmt->execute();        
        $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        
        $data = array();
        for ($i = 0; $i < count($permisos); $i++) {
            $key = $this->getPermisoKey($permisos[$i]['id_permiso']);
            if ($key == '') {
                continue;
            }
            if ($permisos[$i]['valor'] == 1) {
                $v = TRUE;
            } else {
                $v = FALSE;
            }
            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['id_permiso']),
                'valor' => $v,
                'heredado' => TRUE,
                'id' => $permisos[$i]['id_permiso']
            );
        }
        return $data;
        $this->_dbh = NULL;
    }

    // Obtener el key de los permisos
    public function getPermisoKey($permisoId) 
    {
        $permisoId = (int) $permisoId;
        $stmt = $this->_dbh->query("SELECT `key` FROM `permisos` WHERE `id_permiso` = {$permisoId}");
        $key = $stmt->fetch();
        return $key['key'];
        $this->_dbh = NULL;
    }

    // Obtener el nombre del permiso
    public function getPermisoNombre($permisoId) 
    {
        $permisoId = (int) $permisoId;
        $stmt = $this->_dbh->query("SELECT `permiso` FROM `permisos` WHERE `id_permiso` = {$permisoId}");
        $permiso = $stmt->fetch();
        return $permiso['permiso'];
        $this->_dbh = NULL;
    }

    // Devuelve los permisos del usuario
    public function getPermisosUsuario() 
    {
        $ids = $this->getPermisosRoleId();

        if (count($ids)) {
            $stmt = $this->_dbh->query("SELECT userID, id_permiso, valor FROM permisos_usuario WHERE userID = '{$this->_id}' AND id_permiso in (" . implode(",", $ids) . ")");
            $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $permisos = array();
        }

        $data = array();
        for ($i = 0; $i < count($permisos); $i++) {
            $key = $this->getPermisoKey($permisos[$i]['id_permiso']);
            if ($key == '') { continue; }
            if ($permisos[$i]['valor'] == 1) {
                $v = TRUE;
            } else {
                $v = FALSE;
            }
            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['id_permiso']),
                'valor' => $v,
                'heredado' => FALSE,
                'id' => $permisos[$i]['id_permiso']
            );
        }
        return $data;
    }

    // Obtenemo los permisos del rol del usuario
    public function getPermisos() 
    {
        if (isset($this->_permisos) && count($this->_permisos)) {
            return $this->_permisos;
        }
    }

    // Método para utilizar en las vistas para tomar decisión sobre si tiene permiso o no
    public function permiso($key) 
    {
        if (array_key_exists($key, $this->_permisos)) {
            if ($this->_permisos[$key]['valor'] == TRUE || $this->_permisos[$key]['valor'] == 1) {
                return TRUE;
            }
        }

        return FALSE;
    }

    // Método para utilizar en los controladores para tomar decisión sobre si tiene permiso o no
    public function acceso($key) 
    {
        if ($this->_permisos[$key]) {
            Session::tiempo();
            return;
        }
        header('Location: ' . BASE_URL . 'error/access/5050');
        exit;
    }

}
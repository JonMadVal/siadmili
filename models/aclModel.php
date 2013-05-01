<?php

/*
 * Nombre       :   aclModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con las tablas relacionadas ACL
 */

class aclModel extends Model 
{

    private $_roles;    
    private $_perm;

    public function __construct() 
    {
        parent::__construct();
        $this->_roles = array();
        $this->_perm = array();
    }

    /**
     * Obtener los datos de un role.
     *
     * @param Int $roleID
     *   Id del rol.
     *
     * @return
     *   False en caso no se encuentre registrado o array con los datos del role.
     */
    public function getRole($roleID) 
    {
        $roleID = (int) $roleID;
        $stmt = $this->_dbh->prepare("SELECT `role` FROM `roles` WHERE `roleID` = :roleID");
        $stmt->bindParam(':roleID', $roleID, PDO::PARAM_INT);
        $stmt->execute();
        $role = $stmt->fetch();
        return $role['role'];
        $this->_dbh = NULL;
    }

    /**
     * Listar todos los roles.
     *
     * @return
     *   False en caso no exista ningún role o listado de todos los roles.
     */
    public function getRoles() 
    {
        $stmt = $this->_dbh->query("SELECT `roleID`, `role` FROM `roles`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->_dbh = NULL;
    }

    /**
     * Crear array asociativo teniendo como índice el key, de todos los permisos.
     *
     * @return
     *   Array asociativo de permisos.
     */
    public function getPermisosAll() 
    {
        $stmt = $this->_dbh->query("SELECT `id_permiso`, `permiso`, `key` FROM `permisos`"); 
        $this->_perm = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < sizeof($this->_perm); $i++) {
            if ($this->_perm[$i]['key'] == '') { continue; }
            $data[$this->_perm[$i]['key']] = array(
                'key' => $this->_perm[$i]['key'],
                'valor' => 'x',
                'nombre' => $this->_perm[$i]['permiso'],
                'id' => $this->_perm[$i]['id_permiso']
            );
        }
        return $data;
    }

    /**
     * Crear array asociativo teniendo como índice el key, de todos los permisos
     * de un role indicado.
     *
     * @return
     *   Array asociativo de permisos.
     */
    public function getPermisosRole($roleID) 
    {
        $roleID = (int) $roleID;
        $stmt = $this->_dbh->query("SELECT `roleID`, `id_permiso`, `valor` FROM `permisos_role` WHERE `roleID` = {$roleID}");
        $this->_perm = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        for ($i = 0; $i < count($this->_perm); $i++) {
            $key = $this->getPermisoKey($this->_perm[$i]['id_permiso']);
            if ($key == '') { continue; }

            if ($this->_perm[$i]['valor'] == 1) {
                $v = 1;
            } else {
                $v = 0;
            }
            $data[$key] = array(
                'key' => $key,
                'valor' => $v,
                'nombre' => $this->getPermisoNombre($this->_perm[$i]['id_permiso']),
                'id' => $this->_perm[$i]['id_permiso']
            );
        }
        $data = array_merge($this->getPermisosAll(), $data);
        return $data;
        $this->_dbh = NULL;
    }

    /**
     * Editar el permisos asignado a un rol
     *
     * @param int $roleID
     *   Id del role a editar.
     * 
     * @param int $permisoID
     *   Id del permisos a editar.
     * 
     * @param int $valor
     *   Estado del permisos a editar.
     *
     * @return
     *   FALSE en caso no se pueda editar el permiso o TRUE en caso de sí.
     */
    public function editarPermisoRole($roleID, $permisoID, $valor) 
    {
        $roleID = (int) $roleID;
        $permisoID = (int) $permisoID;
        $valor = (int) $valor;
        $stmt = $this->_dbh->prepare("REPLACE INTO permisos_role SET roleID = :roleID, id_permiso = :id_permiso, valor = :valor");
        $stmt->bindParam(':roleID', $roleID, PDO::PARAM_INT);
        $stmt->bindParam(':id_permiso', $permisoID, PDO::PARAM_INT);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Denegar permisos a un rol
     *
     * @param int $roleID
     *   Id del role a denegar.
     * 
     * @param int $permisoID
     *   Id del permisos a denegar.
     *
     * @return
     *   FALSE en caso no se pueda editar el permiso o TRUE en caso de sí.
     */
    public function eliminarPermisoRole($roleID, $permisoID) 
    {
        $roleID = (int) $roleID;
        $permisoID = (int) $permisoID;
        $this->_dbh->query("DELETE FROM permisos_role WHERE id_permiso = {$permisoID} AND roleID = {$roleID}");
    }

    // Obtener el key del permiso
    public function getPermisoKey($permisoId) 
    {
        $permisoId = (int) $permisoId;
        $stmt = $this->_dbh->query("SELECT `key` FROM `permisos` WHERE `id_permiso` = $permisoId");
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

    /**
     * Verificar que nombre de role se encuentra registrado.
     *
     * @param str $role
     *   Role a verificar.
     *
     * @return
     *   False en caso se encuentre registrado o True en caso no lo esté.
     */
    public function getRoleByUsername($role) 
    {
        $stmt = $this->_dbh->prepare("SELECT roleID FROM roles WHERE role = :role");
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Insertar nuevo role.
     *
     * @param str $role
     *   Nombre del role.
     * 
     * @return
     *   False en caso de error o True en caso se registre el role.
     */
    public function insertRole($role) 
    {
        $stmt = $this->_dbh->prepare("INSERT INTO roles VALUES (null, :role)");
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Obtener datos de role específico.
     *
     * @param int $id
     *   Id del role.
     *
     * @return
     *   False en caso de error o array con los datos del role.
     */
    public function getRoleById($id) 
    {
        $id = (int) $id;
        $stmt = $this->_dbh->prepare("SELECT `roleID`, `role` FROM `roles` WHERE `roleID` = :roleID;");
        $stmt->bindParam(':roleID', $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->_roles = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->_roles;        
        $this->_dbh = NULL;
    }

    /**
     * Editar role.
     *
     * @param str $role
     *   Nombre del role a editar. 
     * 
     * @param int $id
     *   Id del role a editar
     * 
     * @return
     *   False en caso de error o True en caso se edite el usuario.
     */
    public function editRoleById($role, $id) 
    {
        $stmt = $this->_dbh->prepare("UPDATE roles SET role = :role WHERE roleID = :roleID");
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':roleID', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Eliminar role específico.
     *
     * @param int $id
     *   Id del role.
     *
     * @return
     *   False en caso de error o True en caso se elimine el role.
     */
    public function deleteRole($id) 
    {
        $result = NULL;
        $id = (int) $id;
        if ($this->getRole($id)) {
            $stmt = $this->_dbh->prepare("DELETE FROM `roles` WHERE roleID = :roleID");
            $stmt->bindParam(':roleID', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        }
        return $result;
        $this->_dbh = NULL;
    }

    /**
     * Listar todos los permisos.
     *
     * @return
     *   False en caso no exista ningún permiso o listado de todos los permisos.
     */
    public function getPermisos()
    {
        $sql = "SELECT id_permiso, permiso, `key` as clave FROM permisos;";
        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        if ($res->num_rows <= 0) {
            return FALSE;
        }
        while ($reg = $res->fetch_assoc()) {
            $this->_perm[] = $reg;
        }

        $res->free();
        return $this->_perm;
    }
    
    /**
     * Insertar permiso.
     *
     * @param str $permiso
     *   Nombre del permiso.
     * 
     * @param str $key
     *   Key del permiso
     * 
     * @return
     *   False en caso de error o True en caso se registre el permiso.
     */
    public function insertPermiso($permiso, $key) {
        // Verificamos que  no exista ya el permiso y el key
        $sql = sprintf("SELECT id_permiso FROM permisos WHERE permiso = %s or `key` = %s", 
        parent::comillas_inteligentes($permiso), parent::comillas_inteligentes($key));
        $res = $this->_db->con()->query($sql);
        if ($res->num_rows > 0) {
            return FALSE;
        } 
        // Si no estan ya registrados insertamos el permiso
        $sql = sprintf(
                "INSERT INTO permisos (permiso, `key`) "
                . "VALUES (%s, %s) ;", parent::comillas_inteligentes($permiso), parent::comillas_inteligentes($key)
        );
        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Verificar que nombre de permiso no se encuentra registrado.
     *
     * @param str $perm
     *   Permiso a verificar.
     *
     * @return
     *   False en caso se encuentre registrado o True en caso no lo esté.
     */
    public function getPermisoByUsername($perm) {
        $sql = "SELECT id_permiso "
                . "FROM permisos WHERE permiso = '" . $perm . "'; ";
        $res = $this->_db->con()->query($sql);
        if ($res->num_rows <= 0) {
            return 1;  // Que usuario no esta en la base de datos
        }
        return 0;
    }
    
    /**
     * Verificar que key de permiso no se encuentra registrado.
     *
     * @param str $key
     *   Key a verificar.
     *
     * @return
     *   False en caso se encuentre registrado o True en caso no lo esté.
     */
    public function verifyKeyExist($key) {
        $sql = "SELECT id_permiso "
                . "FROM permisos WHERE `key` = '" . $key . "'; ";
        $res = $this->_db->con()->query($sql);
        if ($res->num_rows <= 0) {
            return 1;  // Que usuario no esta en la base de datos
        }
        return 0;
    }
    
     /**
     * Obtener datos de permiso específico.
     *
     * @param int $id
     *   Id del permiso.
     *
     * @return
     *   False en caso de error o array con los datos del permiso.
     */
    public function getPermisoById($id) {
        $sql = "SELECT id_permiso, permiso, `key` FROM permisos WHERE id_permiso = $id;";
        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        if ($res->num_rows <= 0) {
            return FALSE;
        }
        if ($reg = $res->fetch_assoc()) {
            $this->_role[] = $reg;
        }

        return $this->_role;
    }
    
    /**
     * Editar permiso.
     *
     * @param str $perm
     *   Nombre del permiso a editar. 
     * 
     * @param str $key
     *   Key del permiso a editar.
     * 
     * @param int $id
     *   Id del permiso a editar
     * 
     * @return
     *   False en caso de error o True en caso se edite el permiso.
     */
    public function editPermisoById($perm, $key, $id) {
        $sql = sprintf("SELECT id_permiso FROM permisos WHERE permiso = %s OR `key` = %s", parent::comillas_inteligentes($perm), parent::comillas_inteligentes($key));
        $res = $this->_db->con()->query($sql);

        if ($res->num_rows > 0) {
            return FALSE;
        }

        $sql = sprintf(
                "UPDATE permisos "
                . "SET permiso = %s AND `key`= %s"
                . "WHERE id_permiso = %s", parent::comillas_inteligentes($perm), parent::comillas_inteligentes($key), parent::comillas_inteligentes($id)
        );

        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        return TRUE;
    }
    
     /**
     * Eliminar permiso específico.
     *
     * @param int $id
     *   Id del permiso.
     *
     * @return
     *   False en caso de error o True en caso se elimine el role.
     */
    public function deletePermiso($id) {
        $sql = sprintf(
                "DELETE FROM permisos "
                . "WHERE id_permiso = %s", parent::comillas_inteligentes($id)
        );
        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        return TRUE;
    }
}
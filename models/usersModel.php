<?php
/*
 * Nombre       :   usersModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla Users
 */

class usersModel extends Model 
{
    private $_user;
    private $_total;

    public function __construct() 
    {
        parent::__construct();
        $this->_user = array();
    }

    /**
     * Verificar los datos del usuario que realiza login.
     *
     * @param Str $username
     *   Username del usuario.
     * @param Str $pass
     *   Password del usuario
     *
     * @return
     *   False en caso no se encuentre registrado o array con los datos del usuario.
     */
    public function login($datos) 
    {
        // Almacenamos en $pass el password del usuario con método de encriptación sha1
        $pass = Hash::getHash('sha1', $datos['pass'], HASH_KEY);
        /*$sql = sprintf(
                "SELECT adminId, login, pass, level "
                . "FROM admin "
                . "WHERE login = %s and pass = %s", parent::comillas_inteligentes($username), parent::comillas_inteligentes($pass)
        );
        $res = $this->_db->con()->query($sql);

        if ($res->num_rows <= 0) {
            return FALSE;
        }
        if ($reg = $res->fetch_array()) {
            $this->_user[] = $reg;
        }
        $res->free();
        return $this->_user;*/
        $sql = "SELECT `userID`, `login`, `role` FROM `users` "
              ."WHERE `login` = ? AND `pass` = ?";
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(1, $datos['username'], PDO::PARAM_STR);
        $stmt->bindParam(2, $pass, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        $this->_dbh = null;
    }

    /**
     * Obtener listado de los usuarios registrados en la base de datos.
     *
     * @return
     *   False en caso error o array con los datos de los usuarios.
     */
    public function getUsers($condicion = '') 
    {
        $sql = "SELECT `u`.`userID`, `u`.`login`, `u`.`nombres`, `u`.`apaterno`, `u`.`amaterno`, `u`.`email`, `u`.`telefono`, `r`.`role` "
              ."FROM `users` `u`, `roles` `r` " 
              ."WHERE `u`.`role` = `r`.`roleID` " . $condicion . " order by `u`.`apaterno`;";
        $stmt = $this->_dbh->query($sql);
        $this->_user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->_user;
        $this->_dbh = null;
    }

    /**
     * Obtener el número total de usuarios registrados.
     *
     * @return
     *   False en caso de error o string con número total de registros.
     */
    public function getTotalRow() 
    {
        $stmt = $this->_dbh->query("SELECT count(*) AS `Total` FROM `users`;");
        $this->_total = $stmt->fetch();
        return $this->_total;
    }

    /**
     * Verificar que username se encuentra registrado.
     *
     * @param str $username
     *   Username a verificar.
     *
     * @return
     *   False en caso se encuentre registrado o True en caso no lo esté.
     */
    public function getUserByUsername($username) {
        $sql = "SELECT userID, login "
                . "FROM users WHERE login = '" . $username . "'; ";
        $res = $this->_db->con()->query($sql);
        if ($res->num_rows <= 0) {
            return 1;  // Que usuario no esta en la base de datos
        }
        return 0;
    }

    /**
     * Verificar que email se encuentra registrado.
     *
     * @param str $email
     *   Email a verificar.
     *
     * @return
     *   False en caso se encuentre registrado o True en caso no lo esté.
     */
    public function getUserByEmail($email) {
        $sql = "SELECT userID, login, email "
                . "FROM users WHERE email = '" . $email . "'; ";
        $res = $this->_db->con()->query($sql);
        if ($res->num_rows <= 0) {
            return 1;
        }
        return 0;
    }

    /**
     * Insertar nuevo usuario.
     *
     * @param str $login
     *   Username del usuario.
     *
     * @param str $password
     *   Password del usuario.
     *
     * @param str $name
     *   Nombre del usuario.
     * 
     * @param str $apaterno
     *   Apellido paterno del usuario.
     * 
     * @param str $amaterno
     *   Apellido materno del usuario.
     *
     * @param str $email
     *   Email del usuario.
     * 
     * @param str $telefono
     *   Teléfono del usuario.
     * 
     * @param str $avatar
     *   Avatar del usuario.
     * 
     * @param str $role
     *   Rol del usuario.
     * 
     * @param str $comments
     *   Comentario del usuario.
     * 
     * @return
     *   False en caso de error o True en caso se registre el usuario.
     */
    public function insertUser($login, $password, $name, $apaterno, $amaterno, $email, $telefono, $avatar, $role, $comments) {
        $sql = sprintf(
                "INSERT INTO users (login, pass, nombres, apaterno, amaterno, email, telefono, avatar, role, Comentario) "
                . "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s) ;", parent::comillas_inteligentes($login), parent::comillas_inteligentes($password), parent::comillas_inteligentes($name), parent::comillas_inteligentes($apaterno), parent::comillas_inteligentes($amaterno), parent::comillas_inteligentes($email), parent::comillas_inteligentes($telefono), parent::comillas_inteligentes($avatar), parent::comillas_inteligentes($role), parent::comillas_inteligentes($comments)
        );

        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Obtener datos de un usuario específico.
     *
     * @param int $id
     *   Id del usuario.
     *
     * @return
     *   False en caso de error o array con los datos del usuario.
     */
    public function getUserById($id) 
    {
        $stmt = $this->_dbh->prepare("SELECT u.userID, u.login, u.nombres, u.apaterno, u.amaterno, u.email, u.telefono, u.role, u.avatar, "
                ."u.Comentario, r.role FROM users u, roles r WHERE u.role = r.roleID AND userID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->_user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->_user;
        $this->_dbh = NULL;
    }

    /**
     * Editar usuario.
     *
     * @param str $name
     *   Nombre del usuario. 
     * 
     * @param str $apaterno
     *   Apellido paterno del usuario.
     * 
     * @param str $amaterno
     *   Apellido materno del usuario.
     *
     * @param str $email
     *   Email del usuario.
     * 
     * @param str $telefono
     *   Teléfono del usuario.
     * 
     * @param str $avatar
     *   Avatar del usuario.
     * 
     * @param str $login
     *   Username del usuario.
     * 
     * @param str $role
     *   Rol del usuario.
     * 
     * @param str $comments
     *   Comentario del usuario.
     * 
     * @param int $id
     *   Id del usuario a editar.
     * 
     * @return
     *   False en caso de error o True en caso se edite el usuario.
     */
    public function editUserById($name, $apaterno, $amaterno, $email, $telefono, $avatar, $login, $role, $comments, $id) {
        $sql = sprintf(
                "UPDATE users "
                . "SET login = %s, nombres = %s, apaterno = %s, amaterno = %s, email = %s, telefono = %s, avatar = %s, role = %s, Comentario = %s "
                . "WHERE userID = %s", parent::comillas_inteligentes($login), parent::comillas_inteligentes($name), parent::comillas_inteligentes($apaterno), parent::comillas_inteligentes($amaterno), parent::comillas_inteligentes($email), parent::comillas_inteligentes($telefono), parent::comillas_inteligentes($avatar), parent::comillas_inteligentes($role), parent::comillas_inteligentes($comments), parent::comillas_inteligentes($id)
        );

        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Eliminar a un usuario específico.
     *
     * @param int $id
     *   Id del usuario.
     *
     * @return
     *   False en caso de error o True en caso se elimine el usuario.
     */
    public function deleteUser($id) {
        $sql = sprintf(
                "DELETE FROM users "
                . "WHERE userID = %s", parent::comillas_inteligentes($id)
        );
        $res = $this->_db->con()->query($sql);
        if (!$res) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Obtener permisos de usuario.
     *
     * @param int $id
     *   Id del usuario.
     *
     * @return
     *   Los permisos del usuario.
     */
    public function getPermisosUsuario($userId) 
    {
        $userId = (int) $userId;
        $acl = new ACL($userId);
        return $acl->getPermisos();
    }

    /**
     * Obtener permisos del rol del usuario.
     *
     * @param int $id
     *   Id del usuario.
     *
     * @return
     *   Los permisos del rol del usuario.
     */
    public function getPermisosRole($userId) 
    {
        $userId = (int) $userId;
        $acl = new ACL($userId);
        return $acl->getPermisosRole();
    }

    /**
     * Eliminar permiso a un usuario específico.
     *
     * @param int $usuarioID
     *   Id del usuario.
     * 
     * @param int $permisoID
     *  Id del permiso.
     *
     * @return
     *   Los permisos del rol del usuario.
     */
    public function eliminarPermiso($userID, $permisoID) {
        $stmt = $this->_dbh->prepare("DELETE FROM `permisos_usuario` WHERE `userID` = :userID AND `id_permiso` = :id_permiso");
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':id_permiso', $permisoID, PDO::PARAM_INT);
        return $stmt->execute();
        $this->_dbh = NULL;
    }

    public function editarPermiso($userID, $permisoID, $valor) {
        $stmt = $this->_dbh->prepare("REPLACE INTO `permisos_usuario` SET `userID` = :userID, `id_permiso` = :id_permiso, `valor` = :valor");
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':id_permiso', $permisoID, PDO::PARAM_INT);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_INT);
        return $stmt->execute();
        $this->_dbh = NULL;
    }

}
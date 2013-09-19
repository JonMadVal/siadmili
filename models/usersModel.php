<?php
/*
 * Nombre       :   usersModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla Users
 */

class usersModel extends Model 
{
    private $_user;

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
        $sql = "SELECT `userID`, `login`, `role`, `avatar`, `nombres` FROM `users` "
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
     * Verificar que username no se encuentre registrado.
     *
     * @param str $username
     *   Username a verificar.
     *
     * @return
     *   False en caso se encuentre registrado o True en caso no lo esté.
     */
    public function verifyUsername($username) 
    {
        $stmt = $this->_dbh->prepare("SELECT `userID` FROM `users` WHERE `login` = :username");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
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
    public function verifyEmail($email) 
    {
        $stmt = $this->_dbh->prepare("SELECT `userID` FROM `users` WHERE `email` = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Insertar nuevo usuario.
     * 
     * @param array $data
     *   Array con los datos del usuarios a agregar.
     * 
     * @return
     *   False en caso de error o True en caso se registre el usuario.
     */
    public function addUser($data) 
    {
        $stmt = $this->_dbh->prepare("INSERT INTO `users` (`login`, `pass`, `nombres`, `apaterno`, `amaterno`, `email`, `telefono`, `role`, `Comentario`, `fec_modificacion`) "
                ." VALUES (:username, :pass, :nombres, :apaterno, :amaterno, :email, :telefono, :role, :comentario, now())");
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':pass', $data['pass'], PDO::PARAM_STR);
        $stmt->bindParam(':nombres', $data['nombres'], PDO::PARAM_STR);
        $stmt->bindParam(':apaterno', $data['apaterno'], PDO::PARAM_STR);
        $stmt->bindParam(':amaterno', $data['amaterno'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $data['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(':role', $data['role'], PDO::PARAM_INT);
        $stmt->bindParam(':comentario', $data['comentario'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
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
        $id = (int) $id;
        $stmt = $this->_dbh->prepare("SELECT `u`.`userID`, `u`.`login`, `u`.`nombres`, `u`.`apaterno`, `u`.`amaterno`, `u`.`email`, `u`.`telefono`, `u`.`avatar`, `r`.`roleID`, `r`.`role`, `u`.`Comentario` "
                ."FROM `users` as `u`, `roles` as `r` WHERE `u`.`role` = `r`.`roleID` AND `u`.`userID` = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->_user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->_user;
        $this->_dbh = NULL;
    }

    /**
     * Editar usuario.
     * 
     * @param array $data
     *   Array con los datos del usuarios a editar.
     * 
     * @return
     *   False en caso de error o True en caso se registre el usuario.
     */
    public function editUser($data) 
    {
        if (isset($data['avatar'])) {
            $avatar = $data['avatar'];
        } else {
            $avatar = 'avatar.png';
        }
        
        if (isset($data['pass'])) {
            $stmt = $this->_dbh->prepare("UPDATE `users` SET `login` = :username, `pass` = :pass, `nombres` = :nombres, `apaterno` = :apaterno, `amaterno` = :amaterno, `email` = :email, `telefono` = :telefono, `avatar` = :avatar, `role` = :role, `Comentario` = :comentario, `fec_modificacion` = now() "
                ."WHERE `userID` = :userID");
            $stmt->bindParam(':pass', $data['pass'], PDO::PARAM_STR);
        } else {
            $stmt = $this->_dbh->prepare("UPDATE `users` SET `login` = :username, `nombres` = :nombres, `apaterno` = :apaterno, `amaterno` = :amaterno, `email` = :email, `telefono` = :telefono, `avatar` = :avatar, `role` = :role, `Comentario` = :comentario, `fec_modificacion` = now() "
                ."WHERE `userID` = :userID");
        }
        
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':nombres', $data['nombres'], PDO::PARAM_STR);
        $stmt->bindParam(':apaterno', $data['apaterno'], PDO::PARAM_STR);
        $stmt->bindParam(':amaterno', $data['amaterno'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $data['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
        $stmt->bindParam(':role', $data['role'], PDO::PARAM_INT);
        $stmt->bindParam(':userID', $data['userID'], PDO::PARAM_INT);
        $stmt->bindParam(':comentario', $data['comentario'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Eliminar usuario específico.
     *
     * @param int $id
     *   Id del usuario.
     *
     * @return
     *   False en caso de error o True en caso se elimine el usuario.
     */
    public function deleteUser($id) 
    {
        $result = NULL;
        $id = (int) $id;
        $stmt = $this->_dbh->prepare("DELETE FROM `users` WHERE `userID` = :userID");
        $stmt->bindParam(':userID', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result;
        $this->_dbh = NULL;
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
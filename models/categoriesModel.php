<?php
/*
 * Nombre       :   categoriesModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla Categories
 */

class categoriesModel extends Model 
{
    private $_categories;

    public function __construct() 
    {
        parent::__construct();
        $this->_categories = array();
    }

    /**
     * Obtener listado de las categorías registrados en la base de datos.
     *
     * @return
     *   False en caso error o array con los datos de las categorías.
     */
    public function getCategories($condicion = '') 
    {
        $sql = '';
        if ($condicion AND !empty($condicion)) {
            $sql = "SELECT `catid`, `catname` FROM `categories` WHERE " . $condicion . " order by `catname`;";
        } else {
            $sql = "SELECT `catid`, `catname` FROM `categories` order by `catname`;";
        }
        $stmt = $this->_dbh->query($sql);
        $this->_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->_categories;
        $this->_dbh = null;
    }
    
    /**
     * Verificar que categoría no se encuentre registrado.
     *
     * @param str $category
     *   Categoría a verificar.
     *
     * @return
     *   False en caso se encuentre registrado o True en caso no lo esté.
     */
    public function verifyCategory($category) 
    {
        $stmt = $this->_dbh->prepare("SELECT `catid` FROM `categories` WHERE `catname` = :category");
        $stmt->bindParam(":category", $category, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Insertar nueva categoría.
     * 
     * @param array $data
     *   Array con los datos de la categoría a agregar.
     * 
     * @return
     *   False en caso de error o True en caso se registre la categoría.
     */
    public function addCategory($data) 
    {
        $stmt = $this->_dbh->prepare("INSERT INTO `categories` (`catname`) VALUES (:catname)");
        $stmt->bindParam(':catname', $data['catname'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Obtener datos de una categoría específica.
     *
     * @param int $id
     *   Id de la categoría.
     *
     * @return
     *   False en caso de error o array con los datos de la categoría.
     */
    public function getCategoryById($id) 
    {
        $id = (int) $id;
        $stmt = $this->_dbh->prepare("SELECT `catid`, `catname` "
                ."FROM `categories` WHERE `catid` = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->_categories = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->_categories;
        $this->_dbh = NULL;
    }

    /**
     * Editar categoría.
     * 
     * @param array $data
     *   Array con los datos de la categoría a editar.
     * 
     * @return
     *   False en caso de error o True en caso se edite la categoría.
     */
    public function editCategory($data) 
    {
        $stmt = $this->_dbh->prepare("UPDATE `categories` SET `catname` = :catname, `fec_modificacion` = now() "
                ."WHERE `catid` = :catid");
        $stmt->bindParam(':catname', $data['catname'], PDO::PARAM_STR);
        $stmt->bindParam(':catid', $data['catid'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return TRUE;
        } else {
            return FALSE;
        }
        $this->_dbh = NULL;
    }

    /**
     * Eliminar categoría.
     *
     * @param int $id
     *   Id de la categoría.
     *
     * @return
     *   False en caso de error o True en caso se elimine la categoría.
     */
    public function deleteCategory($id) 
    {
        $result = NULL;
        $id = (int) $id;
        $stmt = $this->_dbh->prepare("DELETE FROM `categories` WHERE `catid` = :catid");
        $stmt->bindParam(':catid', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result;
        $this->_dbh = NULL;
    }
}
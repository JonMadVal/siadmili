<?php
/*
 * Nombre       :   categoriesModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla categories
 */

class categoriesModel extends Model {
  private $_cat;
  private $_total;

  public function __construct() {
    parent::__construct();
    $this->_cat = array();
  }  

  /**
   * Listado de categorías para realizar la paginación a través de ajax.
   *
   * @param int $start
   *   De donde inicia a obtener los datos.
   * @param int $per_page
   *   Número de datos a obtener del listado.
   *
   * @return
   *   False en caso de error o array con los datos de las categorías.
   */
  public function getCategoriesPagination($start, $per_page) {
    $sql = "SELECT catid, catname FROM categories LIMIT $start, $per_page ;";
    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    if ($res->num_rows <= 0) {
      return FALSE;
    }
    while ($reg = $res->fetch_assoc()) {
      $this->_cat[] = $reg;
    }

    return $this->_cat;
  }
  
  /**
   * Obtener el número total de categorías registradas.
   *
   * @return
   *   False en caso de error o string con número total de registros.
   */
  public function getTotalRow() {
    $sql = "SELECT count( * ) AS Total FROM categories;";
    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    if ($reg = $res->fetch_array()) {
      $this->_total = $reg;
    }
    return $this->_total;
  }
  
  /**
   * Verificar que categoría se encuentra registrado.
   *
   * @param str $cat
   *   Categoría a verificar.
   *
   * @return
   *   False en caso se encuentre registrado o True en caso no lo esté.
   */
  public function verifyCategoryExist($cat) {
    $sql = "SELECT catid "
            . "FROM categories WHERE catname = '" . $cat . "'; ";
    $res = $this->_db->con()->query($sql);
    if ($res->num_rows <= 0) {
      return 1;  // Que usuario no esta en la base de datos
    }
    return 0;
  }  
  
  /**
   * Insertar nueva categoría.
   *
   * @param str $catname
   *   Nombre de categoría.
   *
   * @return
   *   False en caso de error o True en caso se registre la categoría.
   */
  public function insertCategory($catname) {
    $sql = sprintf(
            "INSERT INTO categories (catname) "
            . "VALUES (%s) ;", 
            parent::comillas_inteligentes($catname)
    );

    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    return TRUE;
  }
  
  /**
   * Obtener datos de una categoría específico.
   *
   * @param int $id
   *   Id de categoría.
   *
   * @return
   *   False en caso de error o array con los datos de la categoría.
   */
  public function getCategoryById($id) {
    $sql = "SELECT catid, catname FROM categories WHERE catid = $id;";
    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    if ($res->num_rows <= 0) {
      return FALSE;
    }
    if ($reg = $res->fetch_assoc()) {
      $this->_cat[] = $reg;
    }

    return $this->_cat;
  }
  
  /**
   * Editar categoría.
   *
   * @param str $catname
   *   Nombre de ecategoría. 
   *  
   * @param int $id
   *   Id de categoría a editar.
   * 
   * @return
   *   False en caso de error o True en caso se edite la categoría.
   */
  public function updateCategory($catname, $id) {
    $sql = sprintf(
            "UPDATE categories "
            . "SET catname = %s "
            . "WHERE catid = %s", 
            parent::comillas_inteligentes($catname), 
            parent::comillas_inteligentes($id)
    );

    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    return TRUE;
  }
  
  /**
   * Eliminar categoría específico.
   *
   * @param int $id
   *   Id de categoría.
   *
   * @return
   *   False en caso de error o True en caso se elimine la categoría.
   */
  public function deleteCategory($id) {
    $sql = sprintf(
            "DELETE FROM categories "
            . "WHERE catid = %s", parent::comillas_inteligentes($id)
    );
    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    return TRUE;
  }
}
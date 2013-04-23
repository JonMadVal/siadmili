<?php

/*
 * Nombre       :   publisherModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla publisher
 */

class publisherModel extends Model {

  private $_pubs;
  private $_total;

  public function __construct() {
    parent::__construct();
    $this->_pubs = array();
  }  

  /**
   * Listado de editoriales para realizar la paginación a través de ajax.
   *
   * @param int $start
   *   De donde inicia a obtener los datos.
   * @param int $per_page
   *   Número de datos a obtener del listado.
   *
   * @return
   *   False en caso de error o array con los datos de las editoriales.
   */
  public function getPublishersPagination($start, $per_page) {
    $sql = "SELECT publisher_id, publisher, description FROM publisher LIMIT $start, $per_page ;";
    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    if ($res->num_rows <= 0) {
      return FALSE;
    }
    while ($reg = $res->fetch_assoc()) {
      $this->_pubs[] = $reg;
    }

    return $this->_pubs;
  }

  /**
   * Obtener el número total de editoriales registradas.
   *
   * @return
   *   False en caso de error o string con número total de registros.
   */
  public function getTotalRow() {
    $sql = "SELECT count( * ) AS Total FROM publisher;";
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
   * Verificar que username se encuentra registrado.
   *
   * @param str $username
   *   Username a verificar.
   *
   * @return
   *   False en caso se encuentre registrado o True en caso no lo esté.
   */
  public function verifyPublisherExist($publisher) {
    $sql = "SELECT publisher_id "
            . "FROM publisher WHERE publisher = '" . $publisher . "'; ";
    $res = $this->_db->con()->query($sql);
    if ($res->num_rows <= 0) {
      return 1;  // Que usuario no esta en la base de datos
    }
    return 0;
  }  

  /**
   * Insertar nueva editorial.
   *
   * @param str $publisher
   *   Nombre de editorial.
   *
   * @param str $description
   *   Descripción de editorial
   *
   * @return
   *   False en caso de error o True en caso se registre el usuario.
   */
  public function insertPublisher($publisher, $description) {
    $sql = sprintf(
            "INSERT INTO publisher (publisher, description) "
            . "VALUES (%s, %s) ;", 
            parent::comillas_inteligentes($publisher), 
            parent::comillas_inteligentes($description)
    );

    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Obtener datos de una editorial específico.
   *
   * @param int $id
   *   Id de editorial.
   *
   * @return
   *   False en caso de error o array con los datos de la editorial.
   */
  public function getPublisherById($id) {
    $sql = "SELECT publisher_id, publisher, description FROM publisher WHERE publisher_id = $id;";
    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    if ($res->num_rows <= 0) {
      return FALSE;
    }
    if ($reg = $res->fetch_assoc()) {
      $this->_pubs[] = $reg;
    }

    return $this->_pubs;
  }

  /**
   * Editar editorial.
   *
   * @param str $publisher
   *   Nombre de editorial. 
   * 
   * @param str $description
   *   Descripción de editorial.
   *  
   * @param int $id
   *   Id de editorial a editar.
   * 
   * @return
   *   False en caso de error o True en caso se edite el usuario.
   */
  public function updatePublisher($publisher, $description, $id) {
    $sql = sprintf(
            "UPDATE publisher "
            . "SET publisher= %s, description = %s "
            . "WHERE publisher_id = %s", 
            parent::comillas_inteligentes($publisher), 
            parent::comillas_inteligentes($description), 
            parent::comillas_inteligentes($id)
    );

    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Eliminar editorial específico.
   *
   * @param int $id
   *   Id de editorial.
   *
   * @return
   *   False en caso de error o True en caso se elimine la editorial.
   */
  public function deletePublisher($id) {
    $sql = sprintf(
            "DELETE FROM publisher "
            . "WHERE publisher_id = %s", parent::comillas_inteligentes($id)
    );
    $res = $this->_db->con()->query($sql);
    if (!$res) {
      return FALSE;
    }
    return TRUE;
  }

}
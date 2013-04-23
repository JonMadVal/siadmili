<?php
/*
 * Nombre       :   customersModel.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Modelo que trabajará con la tabla Customers
 */

class customersModel extends Model {
  private $_cust;
  
  public function __construct() {
    parent::__construct();
    $this->_cust = array();
  }
  
  /**
   * Obtener listado de los clientes registrados en la base de datos.
   *
   * @return
   *   False en caso error o array con los datos de los clientes.
   */
  public function getCustomers() {
    $sql = "SELECT customerid, name, ap_paterno, ap_materno, address, city, zip, country_id FROM customers;";$res = $this->_db->con()->query($sql);

    if (!$res) {
      return FALSE;
    }
    if ($res->num_rows <= 0) {
      return FALSE;
    }
    while ($reg = $res->fetch_assoc()) {
      $this->_cust[] = $reg;
    }

    return $this->_cust;
  }
}
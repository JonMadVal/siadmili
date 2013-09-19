<?php
/*
 * Nombre       :   categoriesController.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Controlador que hará el mantenimiento de la tabla Categories
 */

class categoriesController extends Controller 
{
    private $_categories;
    private $_paginador;
    private $_user;

    public function __construct() 
    {
        parent::__construct();
        $this->_categories= $this->loadModel('categories');
        $this->_user = $this->loadModel('users');
        $this->_paginador = new Paginador();
    }

    /**
     * Cargará la vista por defecto.
     */
    public function index() 
    {
        if (!Session::get('logged_in') || Session::get('logged_in') == FALSE) {
            header('location: ' . BASE_URL . 'error/access/5050');
            exit;
        } else {
            $dataUser = $this->_user->getUserById(Session::get('userID'));
            if (is_array($dataUser) && count($dataUser)) {
                $this->_view->assign('dataUser', $dataUser);
            }
            $this->_view->setCssPublic(array('jquery.alerts'));
            $this->_view->setJs(array('funciones'));
            $this->_view->setJsPlugin(array('jquery.alerts'));
            $this->_view->assign('_validation', 'TRUE');
            
            // Código para agregar una nueva categoría
            if ($this->getInt('grabar') && $this->getInt('grabar') == "1") {
                if ($this->getSql('txtCat')) {
                    if ($this->_categories->verifyCategory($this->getSql('txtCat'))) {
                        $data = array(
                            'catname' => $this->getSql('txtCat')
                        );
                        if ($this->_categories->addCategory($data)) {
                            $this->_view->assign('_exito', 'Se agreg&oacute; correctamente la nueva categor&iacute;a.');
                        } else {
                            $this->_view->assign('_error', 'No se pudo agregar la categor&iacute;a. Por favor verifique los datos y vuelva a intentarlo.');
                        }
                    } else {
                        $this->_view->assign('_error', 'La categor&iacute;a ingresada ya est&aacute; registrada.');
                    }
                } else {
                    $this->_view->assign('_error', 'Debe ingresar el nombre de la categor&iacute;a.');
                }
            }
            
            // Código para editar una categoría
            if ($this->getInt('grabar') && $this->getInt('grabar') == "2") {
                if ($this->getSql('txtEditCat')) {
                    if ($this->getSql('txtEditCat') != $this->getSql('hdCatname')) {
                        if ($this->_categories->verifyCategory($this->getSql('txtEditCat'))) {
                            $data = array(
                                'catname'   => $this->getSql('txtEditCat'),
                                'catid'     => $this->getInt('hdCategoryId')
                            );
                            if ($this->_categories->editCategory($data)) {
                                $this->_view->assign('_exito', 'Se edit&oacute; correctamente la categor&iacute;a.');
                            } else {
                                $this->_view->assign('_error', 'No se pudo editar la categor&iacute;a por favor verifique los datos.');
                            }
                        } else {
                            $this->_view->assign('_error', 'El nombre de categor&iacute;a que intenta agregar ya se encuentra registrado.');
                        }
                    } else {
                        $this->_view->assign('_error', 'No ha modificado ning&uacute;n dato.');
                    }
                } else {
                    $this->_view->assign('_error', 'Debe ingresar el nombre de la categor&iacute;a.');
                }
            }
            
            $this->_view->assign('categories', $this->_paginador->paginar($this->_categories->getCategories()));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador_ajax'));
            $this->_view->assign('titulo', APP_NAME . ' - Mantenimiento de Categor&iacute;as');
            $this->_view->renderizar('index', 'Mantenimiento');
        }
    }

    /**
     * Mostrar los datos de las categorías para realizar la paginación a través de ajax
     *
     * @param int $_POST
     *   Número de página a mostrar en la paginación.
     *
     * @return
     *   Carga la vista con los datos del usuario.
     */
    public function displayCategories() 
    {      
        $page = $this->getInt('page');
        $category = $this->getSql('category');
        $condicion = "";
        
        if ($category) {
            $condicion .= " `catname` LIKE '" . $category . "%' ";
        }
        
        $registros = $this->getInt('registros');
        
        $categories = $this->_categories->getCategories($condicion);
        if (is_array($categories) && count($categories)) {
            $this->_view->assign('categories', $this->_paginador->paginar($categories, $page, $registros));
            $this->_view->assign('paginacion', $this->_paginador->getView('paginador_ajax'));
            $this->_view->renderizar('displayCategories', FALSE, FALSE, TRUE);            
        }
    }
    
    /**
     * Método que a través de la categoría enviado vía ajax permitirá validar si ya se encuentra registrado.
     */
    public function verifyCategory() 
    {       
        if ($this->_categories->verifyCategory($this->getSql('category'))) {
            $valid = 'true';
        } else {
            $valid = 'false';
        }
        echo $valid;
    }
    
    /**
     * Cargar los datos de la categoría a editar
     *
     * @param int $_POST['id']
     *   Id de la categoría a editar.
     *
     * @return
     *   Mostramos a través de Json los datos de la categoría a editar.
     */
    public function getCategory() 
    {
        $catid = $this->getInt('catid');
        $result = $this->_categories->getCategoryById($catid);
        echo json_encode($result);
    }

    /**
     * Eliminar de la base de datos uno más usuarios.
     *
     * @param array $_POST['idRole']
     *   Array con los Id de o los roles a eliminar.
     *
     * @return
     *   Nos redirige al método index enviando un mensaje de acuerdo a si se eliminó o no 
     *   el registro.
     */
    public function deleteCategories() 
    {
        if (isset($_POST['idCategory']) && count($_POST['idCategory']) > 0) {
            $errores = array();
            $exitos = array();
            foreach ($_POST['idCategory'] as $cat) {
                $result = $this->_categories->deleteCategory($cat);
                if ($result == NULL) {
                    $errores[] = 'No se pudo eliminar la categor&iacute;a ' . $cat;
                } else {
                    $exitos[] = 'Se elimin&oacute; la categor&iacute;a ' . $cat;
                }
            }
            $this->_view->assign('_errores', $errores);
            $this->_view->assign('_exitos', $exitos);
        } else {
            $this->_view->assign('_error', 'No ha seleccionado categor&iacute;as a eliminar.');
        }
        $this->index();
    }
    
    /**
     * Eliminar una categoría específica
     * 
     * @param int $id Este parámetro es enviado vía ajax y contiene el ID de la categría a eliminar
     * 
     * @return str Devuelve un string 0 si no se eliminó o 1 si lo realizó. Esto se devuelve a la 
     * función ajax.
     */
    public function deleteCategory() 
    {
        if ($this->getInt('catid')) {   
            $result = $this->_categories->deleteCategory($this->getInt('catid'));
            if ($result == NULL) {
                echo '0';
            } else {
                echo '1';
            }
        }
    }
}
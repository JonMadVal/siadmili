<?php

/*
 * Nombre       :   View.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Cargaremos la vista a la cual se le pasarán parámetros
 *                  como la cabecera, menús, footer, etc.
 */
require_once ROOT . 'libs' . DS . 'smarty' . DS . 'libs' . DS . 'Smarty.class.php';

class View extends Smarty 
{
    private $_request;
    private $_js;
    private $_jsPlugin;
    private $_css;
    private $_acl;
    private $_rutas;
    private $_template;
    private $_item;
    private $_itemAcl;

    public function __construct(Request $peticion, ACL $_acl) 
    {
        parent::__construct();
        $this->_request = $peticion;
        $this->_js = array();
        $this->_jsPlugin = array();
        $this->_css = array();
        $this->_acl = $_acl;        
        $this->_rutas = array();
        $this->_template = DEFAULT_LAYOUT;
        $this->_item = '';
        $this->_itemAcl = '';
        
        $modulo = $this->_request->getModulo();
        $controlador = $this->_request->getControlador();
        
        if ($modulo) {
            $this->_rutas['view'] = ROOT . 'modules' . DS . $modulo . DS . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = BASE_URL . 'modules/' . $modulo . '/views/' . $controlador . '/js/';
        } else {
            $this->_rutas['view'] = ROOT . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = BASE_URL . 'views/' . $controlador . '/js/';
        }
    }

    public function renderizar($vista, $item = FALSE,  $itemAcl = FALSE, $viewAjax = FALSE) 
    {
        if ($item) {
            $this->_item = $item;
        }
        
        if ($itemAcl) {
            $this->_itemAcl = $itemAcl;
        }
        
        $this->template_dir = ROOT . 'views' . DS . 'layout' . DS . $this->_template . DS;
        $this->config_dir = ROOT . 'views' . DS . 'layout' . DS . $this->_template . DS . 'configs' . DS;
        $this->cache_dir = ROOT . 'tmp' . DS . 'cache' . DS;
        $this->compile_dir = ROOT . 'tmp' . DS . 'template' . DS;

        $_params = array(
            'ruta_css' => BASE_URL . 'views/layout/' . $this->_template . '/css/',
            'ruta_img' => BASE_URL . 'views/layout/' . $this->_template . '/img/',
            'ruta_js' => BASE_URL . 'views/layout/' . $this->_template . '/js/',
            'item' => $this->_item,
            'itemAcl' => $this->_itemAcl,
            'js' => $this->_js,
            'jsPlugin' => $this->_jsPlugin,
            'css' => $this->_css,
            'root' => BASE_URL,
            'configs' => array(
                'app_name' => APP_NAME,
                'app_slogan' => APP_SLOGAN,
                'app_company' => APP_COMPANY,
                'url_site' => URL_SITE
            )
        );

        if (is_readable($this->_rutas['view'] . $vista . '.tpl')) {
            if ($viewAjax) {
                $this->template_dir = $this->_rutas['view'];
                $this->assign('_layoutParams', $_params);
                $this->display($this->_rutas['view'] . $vista . '.tpl');
                exit;
            }
            $this->assign('_contenido', $this->_rutas['view'] . $vista . '.tpl');
        } else {
            throw new Exception('Error en vista');
        }
        $this->assign('widgets', $this->getWidgets());
        $this->assign('_acl', $this->_acl);
        $this->assign('_layoutParams', $_params);
        $this->display('template.tpl');
    }

    public function setJs(array $js)
    {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_js[] = $this->_rutas['js'] . $js[$i] . '.js';
            }
        } else {
            throw new Exception('Error de js');
        }
    }

    /**
     * Método que permitirá cargar script js que se encuentran en la carpeta public
     * pero para vistas específicas.
     * @param array $js
     * @throws Exception 
     */
    public function setJsPlugin(array $js) 
    {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_jsPlugin[] = BASE_URL . 'public/js/' . $js[$i] . '.js';
            }
        } else {
            throw new Exception('Error de js');
        }
    }

    public function setCssPublic(array $css) 
    {
        if (is_array($css) && count($css)) {
            for ($i = 0; $i < count($css); $i++) {
                $this->_css[] = BASE_URL . 'public/css/' . $css[$i] . '.css';
            }
        } else {
            throw new Exception('Error de css');
        }
    }
    
    public function setTemplate($template)
    {
        $this->_template = (string)$template;
    }
    
    public function widget($widget, $method, $options = array())
    {
        if (!is_array($options)) {
            $options = array($options);
        }
        
        if (is_readable(ROOT . 'widgets' . DS . $widget . '.php')) {
            include_once ROOT . 'widgets' . DS . $widget . '.php';
            
            $widgetClass = $widget . 'Widget';
            
            if (!class_exists($widgetClass)) {
                throw new Exception('Error clase widget');
            }
            
            if (is_callable($widgetClass, $method)) {
                if (count($options)) {                    
                    return call_user_func_array(array(new $widgetClass, $method), $options);                    
                } else {
                    return call_user_func(array(new $widgetClass, $method));                    
                }
            }
            
            throw new Exception('Error método widget');
        }
        
        throw new Exception('Error de widget');
    }
    
    public function getLayoutPositions() 
    {
        if (is_readable(ROOT . 'views' . DS . 'layout' . DS . $this->_template . DS . 'configs.php')) {
            include_once ROOT . 'views' . DS . 'layout' . DS . $this->_template . DS . 'configs.php';
            return get_layout_positions();
        }
        throw new Exception('Error configuración layout');
    }
    
    private function getWidgets() 
    {
        $widgets = array(
            'menu-main' => array(
                'config' => $this->widget('menu', 'getConfig'),
                'content' => array('menu', 'getMenu', array($this->_item))
            ),
            'acl-main' => array(
                'config' => $this->widget('acl', 'getConfig'),
                'content' => array('acl', 'getAcl', array($this->_itemAcl))
            )
        );
        
        $positions = $this->getLayoutPositions();
        $keys = array_keys($widgets);
        
        foreach ($keys as $key) {
            // Verificar si la posición del widget esta presente
            if (isset($positions[$widgets[$key]['config']['position']])) {                
                // Verificar si esta deshabilitado para la vista
                if (!isset($widgets[$key]['config']['hide']) || !in_array($this->_item, $widgets[$key]['config']['hide'])) {                    
                    // Verificar si esta habiltado para la vista
                    if ($widgets[$key]['config']['show'] === 'all' || in_array($this->_item, $widgets[$key]['config']['show'])) {
                        // Llenar la posición del layout
                        $positions[$widgets[$key]['config']['position']][] = $this->getWidgetContent($widgets[$key]['content']);
                    }
                }
            }
        }
        return $positions;
    }
    
    public function getWidgetContent(array $content) 
    {
        if (!isset($content[0]) || !isset($content[1])) {
            throw new Exception('Error contenido widget');
            return;
        }
        
        if (!isset($content[2])) {
            $content[2] = array();
        }
        
        return $this->widget($content[0], $content[1], $content[2]);
    }
}
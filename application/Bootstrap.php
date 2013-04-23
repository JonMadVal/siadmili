<?php
/*
 * Nombre       :   Bootstrap.php
 * Proyecto     :   Sistema de Administraci&oacute;n de Librer&iacute;a - SIADMILI
 * Descripción  :   Va a llamar al controlador, a su método y va a a pasar en 
 *                  caso existan parámetros
 */

class Bootstrap
{
    
    public static function run(Request $peticion)
    {
        $modulo = $peticion->getModulo();
        $controller = $peticion->getControlador().'Controller';
        $metodo = $peticion->getMetodo();
        $args = $peticion->getArgs();
        
        if ($modulo) {
            $rutaModulo = ROOT . 'controllers' . DS . $modulo . 'Controller.php';
            
            if (is_readable($rutaModulo)) {
                require_once $rutaModulo;
                $rutaControlador = ROOT . 'modules' . DS . $modulo . DS . 'controllers' . DS . $controller . '.php';
            } else {
                throw new Exception('Error de base de modulo');
            }
        } else {
            $rutaControlador = ROOT . 'controllers' . DS . $controller . '.php';
        }
        
        // Verificamos si archivo existe y es legible
        if(is_readable($rutaControlador)){
            require_once $rutaControlador;
            $controller = new $controller;
            //Verificar que el objeto y su método puedan ser llamados
            if(is_callable(array($controller, $metodo))){
                $metodo = $peticion->getMetodo();
            }else{
                $metodo = 'index';
            }
            if(isset($args)){
                // Llamar a una llamada de retorno un array de parámetros
                call_user_func_array(array($controller, $metodo), $args);
            }else{
                // Llamar a una llamada de retorno dada por el primer parámetro
                call_user_func(array($controller, $metodo));
            }
        }else{
            throw new Exception('No encontrado');
        }
    }
}
?>

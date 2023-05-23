<?php

/*
 * -------------------------------------
 * 
 * Bootstrap.php
 * -------------------------------------
 */


class Bootstrap
{
    public static function run(Request $request)
    {
        $controller = $request->getController() . 'Controller';
        $routeController = ROOT . 'controllers' . DS . $controller . '.php';
        $method = $request->getMethod();
        $arguments = $request->getArguments();
        
        if(is_readable($routeController)){
            require_once $routeController;
            $controller = new $controller;
            
            if(is_callable(array($controller, $method))){
                $method = $request->getMethod();
            }
          	else{
                $method = DEFAULT_METHOD;
            }
            
            if(isset($arguments)){
                call_user_func_array(array($controller, $method), $arguments);
            }
            else{
                call_user_func(array($controller, $method));
            }
            
        } else {
            throw new Exception('no encontrado');
        }
    }
}
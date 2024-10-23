<?php

namespace Controllers;

use MVC\Router;

class citaController
{
    public static function index(Router $router)
    {
        isAuth();
        
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id_usuario' => $_SESSION['id']
        ]);

    }

}














?>
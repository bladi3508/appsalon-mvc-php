<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        isAdmin();
        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
        
    }

    public static function crear(Router $router)
    {
        isAdmin();
        $servicio = new Servicio;
        $alertas = [];

        if( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validarServicio();

            if( empty($alertas) ){
                //Guardar el servicio
                $resultado = $servicio->guardar();

                if( $resultado ){
                    header('location: /servicios');
                }
            }
            
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }


    public static function actualizar(Router $router)
    {
        isAdmin();
        //Variables
        if( !is_numeric( $_GET['id'] )) return; 
        $alertas = [];
        $servicio = Servicio::find($_GET['id']);

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if( empty($alertas) ){
                $servicio->guardar();
                header('location: /servicios');
            }
        }


        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }

    public static function eliminar()
    {
        isAdmin();
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){

            $id = $_POST['id'];
            $servicio = Servicio::find($id);

            $servicio->eliminar();

            if($servicio){
                header('location: /servicios');
            }
        }
    }
}

?>
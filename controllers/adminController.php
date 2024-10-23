<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController 
{
    public static function index(Router $router)
    {
        isAdmin();
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);

        if( !checkdate($fechas[1], $fechas[2], $fechas[0]) ){
            header('location: /404');
        }

        //Consultar la base de datos
        $consulta = "SELECT citas.id, citas.fecha, citas.hora, concat(usuarios.nombre, ' ', usuarios.apellido) as cliente, usuarios.email as correo, ";
        $consulta .= " usuarios.telefono, servicios.nombre as servicio, servicios.precio";
        $consulta .= " from citas_servicios inner join citas on citas_servicios.id_citas = citas.id";
        $consulta .= " inner join usuarios on citas.id_usuario = usuarios.id";
        $consulta .= " inner join servicios on citas_servicios.id_servicios = servicios.id";
        $consulta .= " WHERE fecha = '${fecha}'";
        $consulta .= " order by id desc";
        
        $citas = AdminCita::SQL($consulta);


        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'id_usuario' => $_SESSION['id'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}



?>
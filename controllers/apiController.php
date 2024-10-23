<?php

namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;


class APIController{

    public static function index()
    {      
        $servicios = Servicio::all();

        echo json_encode($servicios);
    }

    public static function guardar()
    {
        //Almacena la cita y devuelve el ID
        $cita = new Cita();
        $cita->sincronizar($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //Almacena la Cita con el Servicio
        $idServicios = explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args = [
                'id_citas' => $id,
                'id_servicios' => $idServicio
            ];

            $citaServicio = new CitaServicio();
            $citaServicio->sincronizar($args);
            $citaServicio->guardar();
        }


        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar()
    {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            $id = $_POST['id'];

            $cita = Cita::find($id);

            $cita->eliminar();

            header('location: ' . $_SERVER['HTTP_REFERER']);

        }
    }
}


?>
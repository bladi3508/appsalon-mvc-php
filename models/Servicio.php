<?php

namespace Model;


class Servicio extends ActiveRecord
{
    ////Base de Datos
    protected static $tabla = "servicios";
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Mensajes de validación para guardar un servicio

    public function validarServicio()
    {
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        if(!$this->precio){
            self::$alertas['error'][] = 'El precio es obligatorio';
        }
        if(!is_numeric($this->precio)){
            self::$alertas['error'][] = 'Favor de agregar digitos numericos en el precio';
        }

        return self::$alertas;
    }
}



?>
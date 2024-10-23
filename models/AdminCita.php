<?php

namespace Model;

class AdminCita extends ActiveRecord
{
    //Base de datos
    protected static $table = 'citas_servicios';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'cliente', 'correo', 'telefono', 'servicio', 'precio'];


    public $id;
    public $fecha;
    public $hora;
    public $cliente;
    public $correo;
    public $telefono;
    public $servicio;
    public $precio;

    public function __construct()
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
}

?>
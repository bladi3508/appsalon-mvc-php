<?php

namespace Model;

class Cita extends ActiveRecord
{
    //Base de datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'id_usuario'];

    public $id;
    public $fecha;
    public $hora;
    public $id_usuario;

    //Constructor
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $agrs['fecha'] ?? '';
        $this->hora = $agrs['hora'] ?? '';
        $this->id_usuario = $agrs['id_usuario'] ?? '';
    }
    
}

?>
<?php

namespace Model;

class Usuario extends ActiveRecord
{
    ////Base de Datos////
    protected static $tabla = "usuarios";
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono', 'email', 'admin', 'confirmado', 
                                    'token', 'password'];

    ////Globales////
    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    public $email;
    public $password;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Mensajes de validación para crear una cuenta
    public function validarCuentaNeva(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }
        if(!$this->telefono){
            self::$alertas['error'][] = 'Se requiere un numero telefonico';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'Correo electronico obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'Favor de especificar una contraseña';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarPassword()
    {
        if(!$this->password){
            self::$alertas['error'][] = 'Favor de colocar su nueva contraseña';
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }

        return  self::$alertas;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Validar si el usuario existe
    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = 'El email ya se encuentra registrado en otra centa';
        }

        return $resultado;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Hashear el passwor del usuario para la seguridad de sus datos
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Crear token unico para el usuario
    public function crearToken()
    {
        $this->token = uniqid();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Validar usuario
    public function validarUsuario()
    {
        if(!$this->email){
            self::$alertas['error'][] = 'Favor de ingresar un correo electronico';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'Favor de ingresar la contraseña';
        }

        return self::$alertas;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Validar el password del usuario y que haya confrimado su cuenta
    public function comprobarPassworAndConfrimacion($password){
        //Verificar el password
        $resultado = password_verify($password, $this->password);

        if(!$resultado){
            self::$alertas['error'][] = 'Contraseña incorrecta, porfavor intentelo de nuevo';
        }else{
            if(!$this->confirmado){
                self::$alertas['error'][] = 'Tu cuenta aún no ha sido confirmada, favor de seguir las intrucciones enviadas a tu email';           
            }else{
                return true;
            }
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Validar el email para recuperar la contraseña
    public function validarEmail()
    {
        if(!$this->email){
            self::$alertas['error'][] = 'Correo electronico obligatorio';
        }

        return self::$alertas;
    }

}

?>
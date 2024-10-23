<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use PHPMailer\PHPMailer as Mail;
use Classes\Email;

class LoginController
{
    public static function login(Router $router)
    {
        //Mensajes de error
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarUsuario();

            if (empty($alertas)) {
                //El usuario lleno los campos, hora de validar si existe en la BD
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    //Si el correo del usuario esta registrado, entonces, validamos el password

                    if ($usuario->comprobarPassworAndConfrimacion($auth->password)) {
                        //Si la contraseña es correcta y esta confrimada su cuenta, autenticamos al usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar deacuerdo al nivel del usuario
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('location: /admin');
                        } else {
                            header('location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'El correo no se encuentra registrado, favor de verificarlo o registrarse');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Cerrar session
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function logout()
    {
        $_SESSION = [];

        header('location: /');
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Metodos para la recuperacón de contraseñas
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === "1") {

                    //Generar Token unico nuevamente 
                    $usuario->crearToken();
                    $usuario->guardar();

                    //TODO: enviar email al usuario
                    Usuario::setAlerta('exito', 'Se ha enviado un correo con las instrucciones para cambiar tu contraseña');

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                } else {
                    Usuario::setAlerta('error', 'Correo electronico no registrado o sin confirmar');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function recuperar(Router $router)
    {
        //Establecer los mensajes de error
        $alertas = [];
        $error = false;

        //Obtener el token
        $token = s($_GET['token']);

        //Obtener los datos del usuario mediante el token
        $usuario = Usuario::where('token', $token);


        //Verificar si el token esta en los datos de un usuario
        if (empty($usuario)) {
            //Mostrar mensaje de error si no hay datos del usuario
            Usuario::setAlerta('error', 'Token no valido favor, o el usuario no existe');
            $error = true;
        }

        //Validar el nuevo password y gardarlo
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $password = new Usuario($_POST);

            $alertas = $password->validarPassword();

            if (empty($alertas)) {

                //Reseteamos el valor del password a null
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado){
                    header('location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Metodos para la creación de usuarios y confirmación de estos
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function crear(Router $router)
    {
        //Instanciar el modelo Usuario//
        $usuario = new Usuario($_POST);

        //Alertas de campos vacios
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNeva();

            //Validar que no haya ningun campo vacio
            if (empty($alertas)) {
                //Validar si el usuario ya se encuentra registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear password
                    $usuario->hashPassword();

                    //Generar un token unico para validar al usuario
                    $usuario->crearToken();

                    //Enviar el email de confirmación
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    //Crear al usuario
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('location: /mensaje');
                    } else {
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function confirmar(Router $router)
    {
        //Alertas de campos vacios
        $alertas = [];

        //Obtener el token para confirmar
        $token =  s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Lo sentomos el token no es valido');
        } else {
            //Confirmar al usuario y modificar los campos correspondientes
            $usuario->confirmado = "1";
            $usuario->token = null;

            $usuario->guardar();
            Usuario::setAlerta('exito', 'La confirmación de tu cuenta ha sido exitosa');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}

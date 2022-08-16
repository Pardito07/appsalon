<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){

                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        
                        if(!isset($_SESSION)){
                            session_start();
                        }

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else {
                            header('Location: /cita');
                        }
                    }
                    
                }else {
                    Usuario::setAlerta('error', 'Usuario no Encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        if(!isset($_SESSION)){
            session_start();
        }

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Usuario($_POST);

            $alertas = $auth->validarEmail();

            if(empty($alertas)){

                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === '1'){
                    $usuario->crearToken();
                    $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    $alertas = Usuario::setAlerta('exito', 'Te hemos enviado instrucciones para reestablecer tu password, revisa tu email');

                }else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        $token = $_GET['token'];

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $password = new Usuario($_POST);

            $alertas = $password->validarPassword();

            if(empty($alertas)){

                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = '';
                
                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){

        $alertas = [];

        $usuario = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();

                }else {
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuarios' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){

        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token No Válido');
            
        }else {

            $usuario->confirmado = '1';
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {
    public static function index(Router $router) {
        session_start();

        //Se coloca depsues del session_start para verificar si es admin
        // de no ser asi, redirige al usuario.
        isAdmin();

        $servicios = Servicio::all();

        $router -> render('servicios/index', [
            'nombre'=> $_SESSION['nombre'],
            'servicios' => $servicios

        ]);
    }

    public static function crear(Router $router) {
        session_start();
        isAdmin();

        //Instancia de servicio
        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Lo que hace esto es que, el objeto que ya tenemos en me,
            //lo sincroniza con los datos del post, los asigna, en lugar de crear otro objeto, lo asigna al objeto existente,
            //y lo va a sincronizar con los datos de post 
            $servicio -> sincronizar($_POST);

            $alertas = $servicio -> validar();

            if(empty($alertas)) {
                $servicio -> guardar();
                header('Location: /servicios');
            }

        }
        $router -> render('servicios/crear', [
            'nombre'=> $_SESSION['nombre'],
            'servicio'=> $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {
        session_start();
        isAdmin();
        
        if(!is_numeric($_GET['id'])) return;
             //Instancia de servicio
             $servicio = Servicio::find($_GET['id']);
             $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Para validar, que tome el ultimo valor que tenemos y que tambine entre la validacion, no debe de quedar campos sin datos.
            //Sincronizamos la istancia con los datos de post
            $servicio -> sincronizar($_POST);
            //Validamos
            $alertas = $servicio -> validar();

            if(empty($alertas)) {
                $servicio -> guardar();
                header('Location: /servicios');
            }
        }
        $router -> render('servicios/actualizar', [
            'nombre'=> $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas

        ]);
    }

    public static function eliminar() {
        session_start();
        isAdmin();
        //eliminar no requiere router por que solo va a leer el id del servicio que se va a eliminar y nos redirecciona
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio -> eliminar();

            header('Location: /servicios');
        }
    }
}
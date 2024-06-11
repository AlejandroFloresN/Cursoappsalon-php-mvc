<?php

namespace Controllers;

use MVC\Router;

class CitaController {

    public static function index(Router $router) {
        //Forma limpia de utilizar los datos del usuario en la sesion iniciada
        //Para EL NOMBRE
        session_start();
        //Para autenticar si la sesion es true o no, 
        //si el usuario esta autenticado o no
        isAuth();
        $router ->render('cita/index', [
            //Gacias al sessison_start, tenemos los datos del usuario, podemos pasar a la vista el nombre y el id
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']

        ]);
    }
}
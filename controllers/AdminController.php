<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {

    //A diferencia del ApiController, esta separa el backend del frontend y nos retorna una respuesta json
    //Por lo tanto, no requiere el router ni el engine de php para consultar datos

    //Pero AdminController si va a consultar la base de datos y va a mostrar las citas
    public static function index(Router $router) {
        session_start();

        isAdmin();

        //Capturamos la fecha de la url, en caso contrario, usamos la fecha del servidor.
        //Primero se busca un GET, y si no hay, va a poner la fecha del servidor
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        //Para usar algo llamado checkdate(evita que graciosos pongan fechas inexistentes en la url)
        //Debemos separa el año, mes y dia
        $fechas = explode('-', $fecha);
        //Con explode ya vamos a tener separadoa la fecha en un areeglo de 3 posiciones.

        if(!checkdate($fechas[1], $fechas[2], $fechas[0])) {
            header('Location: /404');
        }
        //Para conseguir la fecha del dia actual 
  
        

        //Consultar la BD
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '$fecha' ";
        //debuguear($consulta);
        $citas = AdminCita::SQL($consulta);
        //debuguear($citas);

        $router -> render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);

    }

}
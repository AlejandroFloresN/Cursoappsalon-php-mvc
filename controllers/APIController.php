<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();

        echo json_encode($servicios);
    }

    public static function guardar() {

//Almacena la cita y devuelve el id
        $cita = new Cita($_POST);
         //Al guardar en la tabla de citas, el modelo activerecord retorna un id (linea 164).
         //Aqui en resultado se guardara ese id y de ahi se tiene la referencia de la cita
         //Tambien se estan mandando desde formdata los servicios (linea 457 en adelante en el app.js).
        $resultado = $cita -> guardar();

        //Extraemos el id
        $id = $resultado['id'];
        // //De esta forma todo lo que se mande como cita, va a crearse el objeto
        // $respuesta = [
        //      'cita' => $cita
        // ];

        //Almacena la cita y el servicio
        //El resultado de esta peticion es un string, y para convertirlo en arreglo...
        //Se puede separar por comas, en js se usa algo llamado split, en php hay explode
        //explode toma dos valores, toma el separador y luego el string
        //Con esto, la respuesta ya viene como un arreglo.
//Almacena los servicioa con el ID de la cita
        $idServicios = explode(",", $_POST['servicios']);
        //$idServicio ya almacena un arreglo, entonces podemos usar foreach para iterar
        //Lo que hace todo este foreach es ir instanciando cuantos ids haya disponibles y
        //va a ir iterando cada uno de los servicios con la referencia de la cita
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            //Esto nos va a crear una nueva instancia de citaServicio
            //Detecta citaId y servicioId
           $citaServicio  = new CitaServicio($args);
           $citaServicio -> guardar();      //La iteracion hecha por el foreach se termina por guardar en esta parte
        }

    //Retornamos uina respuesta
        echo json_encode(['resultado' => $resultado]);
    }


    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leemos el id
            $id = $_POST['id'];
            //Lo encontramos
            $cita = Cita :: find($id);
            
            //Lo eliminamos
            $cita -> eliminar();
            //Redirigimos al usuario a la pagina de donde provenia.
            header('Location:' . $_SERVER['HTTP_REFERER']);

        }
    }

}


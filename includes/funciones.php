<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo (string $actual, string $proximo) : bool {

    if($actual !== $proximo) {
        return true;
    } else {
        return false;
    }
}

//Funcion que revisa que el usuario este autenticado
//No retrona nada, por lo tanto se asigna un void
//Revisa en la variable $_session si es true, de no ser asi,
//redirige al usuario a la pantalla de login 
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

function isAdmin() : void {
    //Si no es admin, enviara al panel de inicio de sesion
    //Negamos la condicion para eso
    if(!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}
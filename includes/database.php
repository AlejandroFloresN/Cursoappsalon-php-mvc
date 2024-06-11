<?php

//Las variables de entorno nos ayudan a esconder u ocultar informacion sensible, como las credenciales de la BD
//para instalarlas, se abre una nueva terminal y se ejecuta el siguiente comando
//composer require vlucas/phpdotenv
//Esto nos instalara el phpdotenv
//y en el includes se crea el archivo .env donde estaran las variables 
//No se pueden pasar directamente en la bd
//en el app.php se escribe el siguinte codigo
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->safeLoad();
// y ya se pueden aplicar a las credenciales de la bd
//Es el mismo proceso con los emails

$db = mysqli_connect(
    $_ENV['DB_HOST'], 
    $_ENV['DB_USER'], 
    $_ENV['DB_PASS'], 
    $_ENV['DB_NAME']
);
$db->set_charset('utf8');

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}

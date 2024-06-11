<h1 class="nombre-pagina">Actualizar Servicios</h1>

<p class="descripcion-pagina">Actualiza la informacion del servicio</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<!-- Se le quita el action para mantener la referencia del id -->
<form method="POST" class="formulario">

<?php include_once __DIR__ . '/formulario.php'; ?>

<input type="submit" class="boton" value="Actualizar">

</form>
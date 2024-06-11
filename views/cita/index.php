<h1 class="nombre-pagina">Nueva Cita</h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<p class="descripcion-pagina">Sigue las instrucciones para agendar una cita con los servicios que deseas.</p>

<!-- Enfoque con mas Js -->
<div id="app">

<nav class="tabs">
    <!-- data-paso es un atributo personalizado -->
    <button type="button" data-paso = "1">Servicios</button>
    <button type="button" data-paso = "2">Informacion Cita</button>
    <button type="button" data-paso = "3">Resumen</button>
</nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige los servicios que deseas a continuacion:</p>

        <div id="servicios" class="listado-servicios">

        </div>
    </div>
    <div id="paso-2" class="seccion">
    <h2>Tus Datos y Cita</h2>
    <p class="text-center">Coloca tus datos y selecciona la fecha de tu cita:</p>

    <form class="formulario">
        <div class="campo">
            <label for="nombre">Nombre: </label>
            <input
                id="nombre"
                type="text"
                placeholder="Escribe tu nombre"
                value="<?php echo $nombre ?>"
                disabled
            >
        </div>
        <div class="campo">
            <label for="fecha">Fecha: </label>
            <input
                id="fecha"
                type="date"
                min ="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
            >
        </div>
        <!-- Para evitar que la gente seleccione una fecha anterior a la actual -->
        <!-- En php -->
        <!-- <?//p h p// echo date('Y-m-d') ?> -->
        <!-- strtotime('+1 day') convierte un string a fecha, ideal por si un cliente quiere que no se acepten citas
             el dia actual, si no que sea apartir del siguiente dia. -->

        <div class="campo">
            <label for="hora">Hora: </label>
            <input
                id="hora"
                type="time"
            >
        </div>
        <!-- Para poder hacer uso del id, usamos un hidden -->
        <input type="hidden" id="id" value="<?php echo $id; ?>">
    </form>

    </div>
    <div id="paso-3" class="seccion contenido-resumen">
    <h2>Resumen</h2>
    <p class="text-center">Verifica que toda la informacion proporcionada y los servicios deseados sean los correctos.</p>

    </div>

    <div class="paginacion">
        <button
            id="anterior"
            class="boton"
            >&laquo; Anterior
        </button>

        <button
            id="siguiente"
            class="boton"
            >siguiente &raquo;
        </button>
    </div>

</div>

<?php

$script ="
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src ='build/js/app.js'></script>
    ";

?>

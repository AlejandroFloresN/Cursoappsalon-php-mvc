<h1 class="nombre-pagina">Restablece tu Contraseña</h1>
<p class="descripcion-pagina">Escribe tu nueva contraseña.</p>
<p class="descripcion-pagina">Recuerda que debe tener como minimo 8 caracteres y no debe de contener espacios.</p>

<?php 
//El DIR hace referencia ala ubicacion del archivo actual.
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return;  ?>

<!-- Se omite el action para que no se borre el token del usuario.
     Sin action, se usa la misma url. -->
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Nueva Contraseña: </label>
        <input type="password"
               id="password"
               name="password"
               placeholder="Ingresa la contraseña">
    </div>

    <input type="submit" class="boton" value="Restablecer Contraseña">

</form>

<div class="acciones">
    <div>
    <p>¿Ya tienes una cuenta? </p> <a href="/">Inicia Sesion</a>
    </div>
    <div>
    <p>¿No tienes cuenta? </p> <a href="/create-account">Crea una</a>
    </div>
</div>
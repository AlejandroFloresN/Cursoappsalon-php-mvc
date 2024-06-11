<h1 class="nombre-pagina">Recuperacion</h1>
<p class="descripcion-pagina">Ingresa tu correo.</p>
<p class="descripcion-pagina">Se enviara un mensaje para confirmar tu identidad y poder reestablecer tu contraseña.</p>

<?php 
//El DIR hace referencia ala ubicacion del archivo actual.
    include_once __DIR__ . "/../templates/alertas.php";
?>


<form action="/forget" method="POST" class="formulario">
<div class="campo">
        <label for="email">Correo: </label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Escribe tu correo"
            value=""
            />
    </div>
    <input type="submit" value="Enviar Instrucciones" class="boton">
</form>

<div class="acciones">
    <div>
    <p>¿Ya tienes una cuenta? </p> <a href="/">Inicia Sesion</a>
    </div>
    <div>
    <p>¿No tienes cuenta? </p> <a href="/create-account">Crea una</a>
    </div>
</div>
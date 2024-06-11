
<h1 class="nombre-pagina">LOGIN</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos</p>

<!-- Include de alertas -->
<?php 
//El DIR hace referencia ala ubicacion del archivo actual.
    include_once __DIR__ . "/../templates/alertas.php";
?>


<form action="/" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Correo: </label>
        <input  
                type="email" 
                id="email" 
                name="email" 
                placeholder="Ingresa tu correo"
                />
    </div>
    <div class="campo">
        <label for="password"> Contraseña: </label>
        <input 
                type="password"
                id="password"
                name="password"
                placeholder="Ingresa la contraseña"
         />
    </div>
    <input type="submit" class="boton" value="Iniciar Sesion ">
</form>

<div class="acciones">
    <div>
    <p>¿No tienes cuenta? </p> <a href="/create-account">Crea una</a>
    </div>
    <div>
    <p>¿Olvidaste tu contraseña? </p> <a href="/forget">Recuperala</a>
    </div>
</div>
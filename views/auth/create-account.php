<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario de suscripcion. </p>

<?php 
//El DIR hace referencia ala ubicacion del archivo actual.
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/create-account" class="formulario" method="POST">
    <div class="campo">
        <label for="nombre">Nombre: </label>
        <input 
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Dinos tu nombre..."
            value="<?php echo s($usuario -> nombre); ?>"
            />
    </div>
    <div class="campo">
        <label for="apellido">Apellidos: </label>
        <input 
            type="text"
            id="apellido"
            name="apellido"
            placeholder="Dinos tus apellidos..."
            value="<?php echo s($usuario -> apellido); ?>"
            />
    </div>
    <div class="campo">
        <label for="telefono">Telefono: </label>
        <input 
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="¿Cual es tu telefono?"
            value="<?php echo s($usuario -> telefono); ?>"
            />
    </div>
    <div class="campo">
        <label for="email">Correo: </label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="¿Cual es tu correo?"
            value="<?php echo s($usuario -> email); ?>"
            />
    </div>
    <div class="campo">
        <label for="password">Contraseña: </label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Escribe tu contraseña"
            />
    </div>
    <div class="campo">
        <label for="password2">confirma tu contraseña: </label>
        <input 
            type="password"
            id="password2"
            name="password2"
            placeholder="Vuelve a escribir tu contraseña"
            />
    </div>
    
    <input type="submit" value="Crear Cuenta" class="boton">
</form>

<div class="acciones">
    <div>
    <p>¿Ya tienes una cuenta? </p> <a href="/">Inicia Sesion</a>
    </div>
    <div>
    <p>¿Olvidaste tu contraseña? </p> <a href="/forget">Recuperala</a>
    </div>
</div>
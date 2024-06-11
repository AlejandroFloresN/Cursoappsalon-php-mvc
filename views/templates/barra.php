<div class="barra">
    <!-- Colocamos el nombre del usuario a la vista
        El nombre, al igual que el id ya se pasaron a la vista en CitaController. -->
    <p>Hola: <?php echo $nombre ?? ''; ?></p>

    <!-- Enlace para cerrar sesion -->
    <a class="boton" href="/logout">Cerrar Sesion</a>
</div>

<?php if(isset($_SESSION['admin'])) { ?>
  
    <div class="barra-servicios">
       <a href="/admin" class="boton">Ver Citas</a>
       <a href="/servicios" class="boton">Ver Servicios</a>
       <a href="/servicios/crear" class="boton">Nuevo Servicio</a>
    </div>

<?php } ?>
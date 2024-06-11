<h1 class="nombre-pagina"> Panel de Administracion </h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <!-- Este input date no tendra min por que el admin si podra retroceder en las fechas y ver las citas -->
            <input 
                type="date"
                id="fecha"
                name="fecha"
                value="<?php echo $fecha; ?>"
            />
        </div>
    </form>
</div>

<?php
//La funcion count nos 
if(count($citas) === 0) {
    echo "<h2>No hay citas programadas para hoy</h2>";
}
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
        $idCita = 0;
        //Iteramos sobre el objeto cita
        //Tambine, para mostrar el total a pagar, necesitaremos registrar el indice, por ende usamos key
        foreach($citas as $key => $cita) {
            //Condicion, si el id de la cita es diferente a cita-> id
            //entonces ejecuta el siguiente codigo
            if($idCita !== $cita->id) {
                //La varible total inicia en cero aqui, por que esta parte del codigo solo se inicia una vez, cuando iniciamos
                //al mostrar los datos de la cita. 
                $total = 0;
        ?>
            <li>
                <p>ID: <span><?php echo $cita -> id ?></span></p>
                <p>Hora: <span><?php echo $cita -> hora ?></span></p>
                <p>Cliente: <span><?php echo $cita -> cliente ?></span></p>
                <p>Email: <span><?php echo $cita -> email ?></span></p>
                <p>Telefono: <span><?php echo $cita -> telefono ?></span></p>
            

            <h3>Servicios</h3>
       
        <?php 
            $idCita = $cita -> id;
            }//Fin de if

            //La suma de los precios empieza despues del if, por que, como el if se ejecuta una vez, ahi se inici ala variable
            //y el foreach empieza a iterar sobre los servicios y va sumando los precios en la variable 
            $total += $cita -> precio;
        ?>

        <!-- Fuera del if para que no se repitan. -->
            <p class="servicio">
                <?php echo $cita -> servicio . " " . $cita -> precio; ?>
            </p>

            <!-- Se eliminar el li de cierre para que html lo cierre automaticamnete, 
                esto evita que el primer servicio tenga algo parecido a un margin y este separado y desalineado
                de los demas servicios. -->

        <?php
        //Lo que hace esto es
        //La variable actual nos va a retornar el id en el que nos encontramos actualmente,
        //mientras que proximo es el indice en el arreglo de la bd, va a empezar en 0, 1, 2, 3, ...
        //De esta forma va a identificar cual es el ultimo registro (cita) que tiene el mismo id para detectar que es el ultimo
        //Y se crea una variable que va a ser el total, que inica el cero  y cada vez que se vaya iterando, se va a ir sumando el precio hasta llegar al final, con un total.
            $actual = $cita -> id;
            $proximo = $citas[$key + 1] -> id ?? 0;

            if(esUltimo($actual, $proximo)) {
            ?>

            <p class="total">Total <span>$ <?php echo $total ?></span></p>
            <form action="api/eliminar" method="POST">
                <input type="hidden" name="id"  value="<?php echo $cita -> id; ?>">
                <input type="submit" class="boton-eliminar" value="Eliminar">
            </form>

            <?php
            }

         }//Fin de foreach
        ?>
    </ul>

</div>

<?php

$script = "<script src='build/js/buscador.js'></script>";

?>
<?php
//$alertas va a estar disponible aqui, en la vista de crear-cuenta y en el logincontroller,
//por la forma en que funciona php, solo se necesita una variable en la vista para que este disponible
//en lo que sea que la vista tenga relacion.

//Teniendo en cuenta que el arreglo, o lista tiene de nombre "error", la forma en la que vamos a iterar sobre el para que nos muestre
//correctamente va a ser con dos foreach, uno para iterar sobre el arreglo principal "array" para acceder al key "error", y el segundo
//es para ya iterar sobre el arreglo, o lista, "error", en este caso.

//Por eso, en el modelo usuario, en la funcion validarNuevaCuenta, tenemos dos arreglos definidos en cada alerta, el primero es el tipo de mensaje,
//y el segundo ya son los mensajes de errores.

//$key representa la llave que tenemos que identificar, "error" en este caso,
//y despues ya accedemos a los mensajes.

    foreach($alertas as $key => $mensajes):
        foreach($mensajes as $mensaje):
?>
            <div class="alerta <?php echo $key ?>"> <?php echo $mensaje; ?></div>
            <!-- Muy importante, no se sanitiza por que el arreglo se esta generando en el modelo.
                Se sanitiza lo que el usuario escfibe o introduce, lo que los modelos generano php, no. -->
<?php        

        endforeach;

    endforeach;

?>
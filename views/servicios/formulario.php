<div class="campo">
    <label for="nombre">Nombre: </label>
    <input 
        type="text"
        id="nombre"
        placeholder="Agrega el nombre del servicio"
        name="nombre"
        value="<?php echo $servicio -> nombre; ?>"
        />
</div>

<div class="campo">
    <label for="precio">Precio: </label>
    <input 
        type="number"
        id="precio"
        placeholder="Agrega el precio del servicio"
        name="precio"
        value="<?php echo $servicio -> precio; ?>"
        />
</div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
    <div class="contenedor-app">
        <!-- Para la imagen de fondo -->
        <div class="imagen"></div>
        <!-- Para todo el resto del contenido. -->
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>
    
    <?php
    //Si no hay ninguna variable de script, entonces imprime un string vacio.
    //Solamente donde este la variable de script, va aimprimirla y donde no, solo dara un string vacio.
    //Asi se evitan errores.
        echo $script ?? '';
    ?>
            
</body>
</html>
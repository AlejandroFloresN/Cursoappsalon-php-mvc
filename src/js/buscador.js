document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
})

function iniciarApp() {
    buscarPorFecha();
}


function buscarPorFecha() {
    //Seleccioanmos el input con su id fecha
    const fechaInput = document.querySelector('#fecha');
    //Le agregamos un eventlistener
    fechaInput.addEventListener('input', function(e) {
        //Le pasamos el evento para leer un valor(la fecha seleccionada)
        const fechaSeleccionada = e.target.value; 

        //Redireccionamos al usuario una vez que seleccione una fecha, vamos a redireccionarlo, y
        //por metodo GET la url y se va a validar en el controlador(Admin controller).
        window.location = `?fecha=${fechaSeleccionada}`;
        //De esta forma se redirige al usuario por querystring, la fecha que haya seleccionado
        //Con esto ya se puede leer la fecha con codigo de php y asi filtrar las citas
    });
}
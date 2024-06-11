let paso = 1;
//Para que el paginador no suba mas de la cuenta (3) o baje menos de 1, se agregan los numeros inicial y final
//Se usa el tipo de variable const por que su valor no se va a modificar nunca.
const pasoInicial = 1;
const pasoFinal = 3;

//Objeto con las citas seleccionadas
const cita = {
    id:'',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {

    iniciarApp();

});

function iniciarApp() {
    //Inicio de la aplicacion
    mostrarSeccion();   //Muestra y oculta las secciones
    tabs();             //Cambia la seccion cuando se presionen los tabs
    botonesPaginador(); //Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();     //Consultar la API en el backend de PHP

    idCliente();        //
    nombreCliente();    //Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); //Añade la fecha al objeto de cita
    seleccionarHora();  //Añade la hora al objeto de cita

    mostrarResumen();   //Muestra un resumen de la cita en la seccion resumen
}

function mostrarSeccion() {


//Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    //Si hay una clase de mostrar, ejecuta el siguiente codigo, si no, no se hace nada
    if(seccionAnterior) {
    //Lo que hace esto es que, selecciona lo que tenga la clase de mostrar, y se la quita
    seccionAnterior.classList.remove('mostrar');
    }

//Seleccionar la seccion con el paso
    //Se usa un templatestring `` por que se va a mezclar el codigo con un selector de html
    // se selecciona el id y luego se le adjunta el valor de paso, que en este caso es 1.
    const seccion = document.querySelector(`#paso-${paso}`);
    //como ya se selecciono esa clase, ahora
    // cuando se leccione el elemento por el id, se le va a agregar la clase de mostrar. 
    seccion.classList.add('mostrar');

//Quitar la clase actual al tab anterior
const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

//Resalta la seccion actual
    //${paso} va a ir cambiando conforme vayamos haciendo click en los tabs
    const tab = document.querySelector(`[data-paso = "${paso}"]`);
    tab.classList.add('actual');

}

function tabs() {
    //Se seleccionan los botones que esten dentro de la clase .tabs
    //como los botones no tiene clases, se seleccionan el tipo button.
    // el queryselector retorna uno, el queryselectorall retorna un grupo de objetos
    const botones = document.querySelectorAll('.tabs button');

    //Para ir iterando en cada uno de los botones dentro del div con la clase .tabs
    //El foreach iterara en cada uno de los elementos la cantidad de veces como haya elementos.
    //y asi agregarle un eventlistener a cada uno de los elementos(botones), ya que no se 
    //puede agregar un eventlistener a una coleccion de elementos, como son los botones.
    // se registra un evento click y se le agrega una funcion 
    botones.forEach( boton => {
        boton.addEventListener('click', function(e) {
            //con el dataset [opdemos acceder a los atributos que estamos creando (los atributos que creamos).
            //parseInt sirve para convertir a enteros, numeros.
            paso = parseInt(e.target.dataset.paso);

            //Una vez que se haya asignado alguno de estos pasos,
            //se manda a llamar otra funcion
            //evento click
            mostrarSeccion();
            botonesPaginador();
            //ambas funciones anteriores se mandan a llamar, tanto cuando se inicia la aplicacion, como cuando haya algun evento click

            //Para hacer que la funcion mostrarResumen siempre se esten mandando a llamar con cada evento click en los tabs
            // se agrega aqui 
            //mostrarSeccion y botonesPaginador son funciones que deben de estar ejecutandose con cada evento click, pero mostrarResumen, debe mostrarse solo en la seccion 3
            //al ser seleccionada, por ende, se manda a llamar desde botonesPaginador, cuando sea el paso 3
        })

    } )
}

function botonesPaginador() {
    //Seleccionamos los botones mediante su id, siguiente o anterior
    const paginaAnterior= document.querySelector('#anterior');
    const paginaSiguiente= document.querySelector('#siguiente');

    if(paso === 1) {
        //Ocultar maneja una propiedad llamada visibility: hidden, que solo oculta el boton, no lo remueve como el display: none
        //Esto para que el boton siguiente no tome el lugar de "anterior"
        paginaAnterior.classList.add('ocultar');
        //En el primer evento aparece ocultar a anterior, en el segundo aparece ocultar a siguiente,
        //si se regresa a la seccion anterior, se mantiene el ocutar a siguiente, entonces, para solucionar el problema...
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        //Asi como con los tabs, al presionar el boton de siguiente, y llegar a la seccion 3, se debe de mandar a llamar la funcion mostrarResumen
        //pero solo en la seccion 3
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();

}

function paginaAnterior() {
        //Seleccionamos los botones mediante su id, siguiente o anterior
        const paginaAnterior= document.querySelector('#anterior');
        //Le agregamos un eventlistener, que, al registrar un click, va a correr la siguiente funcion
        paginaAnterior.addEventListener('click', function() {
            if(paso <= pasoInicial) return;
            paso --;
            //Al final mandamos a llamar la funcion botonesPaginador,
            //que ya cuenta con toda la logica para el cambio entre botones.
            botonesPaginador();
        });

}

function paginaSiguiente() {

    const paginaSiguiente= document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {
        if(paso >= pasoFinal) return;
        paso ++;
        //Al final mandamos a llamar la funcion botonesPaginador,
        //que ya cuenta con toda la logica para el cambio entre botones.
        botonesPaginador();

    });
}

//Para hacer una funcion asincrona
async function consultarAPI() {

    //try catch
    //El try intenta ejecutar el codigo dentro suyo, y si hay algun error, el catch lo retiene y
    //nos marca un mensaje, y esto nos previene de que se detenga la ejecucion de nuestra aplicacion en caso del agun error
    try {
        //el location.origin cambiara deoendiendo del dominio que se le asigne
        //Esta es una manera de hacerlo dinamico
        //Tambine funciona dejarle solo /api/servicios
        //pero esto solo funciona si el backend y el front end estan alojados en el mismo servidor
        const url = `${location.origin}/api/servicios`;
        // la funcion fetch nos permite consumir el servicio del API.
        //El await basicamente detiene la ejecucion del codigo temporalmente, hasta que fetch haya terminado
        //de hacer lo que tenga aue hacer.
        //El fetch antes era conocido como ajax
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        //Con el console.log(resultado) podemos ver mucha informacion, y ahi esta el metodo de json, que es proximo a ocuparse

        mostrarServicios(servicios);


    }catch(error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    //Para ir iterando sobre los servicios
    servicios.forEach (servicio => {
        //Aplicamos destructuring
        //Destructuring extrae el valor pero tambien crea la variable al mismo tiempo.
        const {id, nombre, precio} = servicio;
        //Usamos scripting
        const nombreServicio = document.createElement('P'); //Hacemos un parrafo
        nombreServicio.classList.add('nombre-servicio');    //Le agregamos una clase para los estilos
        nombreServicio.textContent = nombre;                //Le damos el valor que tendra.

        const precioServicio = document.createElement('P'); //Hacemos un parrafo
        precioServicio.classList.add('precio-servicio');    //Le agregamos una clase para los estilos
        precioServicio.textContent = `$${precio}`;          //Le damos el valor que tendra, la variable.

        //Contenedor, div que contenga todos estos servicios y sus precios

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        //Le creamos un atributo personalizado
        servicioDiv.dataset.idServicio = id;
        //Funcion que se ejecutara cuando se de click en algun servicio.
        // Para poder pasar un dato de una funcion a otra cuando estamos creandolo con scripting,
        //usamos un callback
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        };

        //Para mostrarlo en pantalla
        //Con esto, dentro del div creado, ya tenemos los dos parrafos
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //En la carpeta de views, en cita, esta el index, en el, hay un div (en el paso numero 1) con el id servicios
        //ahi es donde vamos a poner todo el contenido
        document.querySelector('#servicios').appendChild(servicioDiv);
        //Como el foreach va a ir iterando, va a ir agregando todos los servicios dentro del html
    });
}

function seleccionarServicio(servicio) {
    //Extraemos el id para futuros usos
    const {id} = servicio;
    //Extraemos los servicios del objeto, dentro de la variable cita, en la parte superior.
    const {servicios} = cita;

       //Para agregarle una clase al servicio seleccionado, de modo que se resalte para que se sepa que esta seleccionado
    //Usaremos el id del servicio para indetificar el servicio seleccionado
    //Selector que identifica un elemento medinte id
    const divServicio = document.querySelector(`[data-id-servicio = "${id}"]`);

    //if que va a comprobar si un servicio ya fue seleccionado
    //Por lo tanto, se debe iterar sobre servicios, el arreglo en la variable de cita
    //El some itera sobre el arreglo buscando un elemento, y si un elemento existe, retorna true, o false en caso contrario.
    //La palabra agregado se usa solamente para no usar otra vez la palabra servicio
    //se queda solo como id (el de servicio) por que arriba ya se esta sacando con destructuring, yua no es necesario poner servicio.id
    if(servicios.some(agregado => agregado.id === id)) {
        //Si ya esta agregado, eliminarlo
        //El filter nos permite sacar un elemento bajo ciertas condiciones
        cita.servicios = servicios.filter(agregado => agregado.id !== id);

        //Quitamos la clase
        divServicio.classList.remove('seleccionado');

    }else {
        //cita.servicios se refiere a que vamos a estar trabajando dentro de el.
        //Dentro del arreglo, se toma una copia de los servicios (...servicios), y le agregamos el nuevo servicio.
        cita.servicios = [...servicios, servicio];

        //Agregamos la clase
        //Esto se va a hacer cuando ya hayamos seleccionado un servicio
        divServicio.classList.add('seleccionado');
    }
    
}

function idCliente() {
    cita.id = document.querySelector('#id').value;
}

function nombreCliente () {
    //Leer el nombre que ya esta agregado al formulario y asignarlo a cita
    cita.nombre = document.querySelector('#nombre').value;
    
}

function seleccionarFecha() {
    //seleccionamos la fecha por su id.
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        //Para validar que no se seleccionen dias de descanso(sabado y/o domingo)
        //El getUTCDay saca la numeracion de los dias de la semana, contando desde el domingo como un 0, hasta 6 sabado
        const dia = new Date(e.target.value).getUTCDay();
        

        //includes compara el valor(dia) con un grupo de datos en un arreglo, devuelve true si esta o false si no
        if([6, 0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Sabados y domingo no laboramos', 'error', '.formulario');
        }else {
            cita.fecha = e.target.value;
            
        }
    })
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function (e) {
       const horaCita = e.target.value;
       //El split permiter separar una cadena de string, el separador en este caso son :
       const hora = horaCita.split(":")[0];
       //Con el 0 en corchetes solo nos traemos la hora en lugar del arreglo que contiene hora y minutos separados
       if(hora < 9 || hora > 18) {
        e.target.value = '';
        mostrarAlerta('Las citas solo pueden ser agendadas en un horario entre las 9 AM y las 6 PM.', 'error', '.formulario');
       }else {
        cita.hora = e.target.value;
       }
    })
}

//Para hacer mas reutilizable esta funcion, 
//como parametros va a tomar un mensaje y un tipo de alerta y el elemento, el selector donde aparecera la alerta
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    //para prevenir que se genere la misma alerta muchas veces
    const alertePrevia = document.querySelector('.alerta');
    // si es true alertaprevia, manda un return.
    if(alertePrevia) {
        //Debido a que la alerta de resumen esta siempre visible, no generara otras alertas, por si no habian seleccionado hora  fehca,
        //POr eso, la alerta previa se va a aliminar
        alertePrevia.remove();
    };

//Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    //Para mostrarlo en pantalla
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    // if(desaparece) {
    //         //Eliminamos la alerta
    //     //Para que tenga temporizador(5000 = 5s)
    //     setTimeout(() => {
    //         alerta.remove();
    //     }, 5000);
    // }

}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //Limpiar el contenido de resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    //El object.values(cita) es un metodo especificamente diseñado para objetos.
    //Cunado se quiere validar un objeto, se p[uede utilizar este metodo, trabaja en conjunto con el .includes(''),
    //con el que, en el objeto, buscaremos un string vacio.
    //Pero cuando se quiere verificar si un arreglo esta vacio, se puede utilizar .length.
    //Si es igual a 0, entonces quiere decir que no ha seleccionado ningun servicio.
    if(Object.values(cita).includes('') || cita.servicios.length === 0) {
        //Aqui sera una alerta, pero esta toma como referencia formulario, entonces...
        mostrarAlerta('Hacen falta datos y/o seleccionar un servicio.', 'error', '.contenido-resumen', false);

        //El return es para que detenga la ejecucucion del codigo y no este todo en un else.
        return;
    } 

    // Formatear el div de resumen
    const {nombre, fecha, hora, servicios} = cita;

    //heading para servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    //Como servicios es un arreglo...
    //iteramos
    servicios.forEach(servicio => {
        //Como se esta iterando sobre servicios, y ya tenemos una instancia ya se puede hacer destructuring aqui, entonces..
        const {id, precio, nombre} = servicio;
        //cada servicio se colocara en un div
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span> $${precio}`;

        //Como ya se habia creado un contenedor div, ahora solo falta meterle el textoservicio y el precioservicio
        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        //Ahora solo falta agregarlo al resumen
        resumen.appendChild(contenedorServicio);
    })

    //heading para Cita en Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    //Formatear la fecha a español
    const fechaObj = new Date(fecha);   //Obtenemos un objeto manipulable de la fecha al instanciarla
    const mes = fechaObj.getMonth();    //retorna el numero del mes, pero los meses inician desde 0, osea, enero es 0 y diciembre es 11
    const dia = fechaObj.getDate() + 2;     //getdate nos retorna el dia del mes, y getday nos retorna el dia de la semana, que tambien tiene un desface de un dia, osea, el primer dia de cada mes es 0
    const year = fechaObj.getFullYear();//Pbtenemos el año cpmpleto

    //Date.UTC toma cada uno de los valores por separado, no puede ponerse toda la fecha junta
    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    // NOTA: Cada vez qyue se use new Date se hace un desface en los dias, si se usa dos veces, el desface ssera de dos dias,
    //por ende, si se selecciona una fecha (2024 - 05 - 13), en consola y en la bd, saldra 2024 - 05 - 11, por el uso de NEW Date
    //Para evitar esto, le agregamos un +2 a la variable de dia

    //toLocaleDateString toma un objeto como val;or, por eso es importante anteriormente formatear la fecha de string a objeto
    //Y hacinedo una busqueda basica en internet, se pueden encontrar los codigos de localizacion de idiomas
    //Y toma tambien opciones de configuracion
    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    //Con esto no se modifica el valor de fecha en el objeto original, solo el valor de la variable fecha
    //Esto se le conoce como inmutabilidad, procurar no cambiar, o cambiar lo menos posible el dato original

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span> ${hora} Horas`;

    //Boton para crear cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;   //Cuando se asocia un evento de esta forma, no se le agregan los parentesis, por que eso manda a llamar la funcion.
    // si se requiere pasar un parametro a la funcion, se recomienda crear un callback, ejemplo:
    // botonReservar.onclick = function () {
    //     reservarCita(id);
    // }
    //En este caso, no se le pasa nad a por que el objeto esta en global

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonReservar);

}

async function reservarCita() {
    //Extraemos el objeto de cita
    const {nombre, fecha, hora, servicios, id} = cita;

    //Para extraer los servicios, hay que iterar sobre ellos
    //Como en la base de datos solo requerimos el id de servicios, entonces solo eso necesitamos
    //Le aplicamos restructuring a los servicios
    //En este caso se usara el map, la diferencia entre un map y un forEach es que el forEach solamente itera, mientras que el map, las coincidencias las colocara en la variable
    //De esta forma, itero sobre los servicios, identifico el id y lo va retornando a la variable idServicios.
    const idServicios = servicios.map(servicio => servicio.id);

    // console.log(idServicios);

    //Una vez presionando este boton, se tiene que mandar una peticion a la api 
    const datos = new FormData();
    //Solo se crea un objeto y se envia al servidor, ya la api de formdata se encarga de manejar los datos

    //La parte de la izquierda es la forma en la que se va a acceder a los datos(nombre de la columna en la bd)
    //Lo de lado derecho es la variable
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);
    //No se puede ver lo que el formdata envia, un tip para poder ver los datos y saber que enviamos es usar el spread operator
    //usamos console.log y como si fuera un arreglo, dentro de este, ponemos la variable a usar, en este caso datos,
    //y antes de la variable, ponemos tres puntos seguidos
    //Esto lo que hace es que toma una copia del formdata y lo formatea dentro del arreglo.
    // console.log([...datos]);
    //El formData actua como el submit en js pero debemos enviarlo hacia una api, para eso

    //Para empezar a enviar datos al servidor, usando la api que hemos creado (ApiController)
    //Primero hay que registrar una url (en index) que se encargue de registrar los datos que se van a enviar por medio del formData

    try {
        //Peticion hacia la api
        const url = `${location.origin}/api/citas`;

        //Usaremos async (en la funcion) await por que, de aqui a que se conecte al servidor, realice la peticion y nos retorne una respuesta, no sabemos cuanto tiempo va a tardar
        //Usamos fetch pata hacer la peticion al servidor, con await, y le pasamos la url, el segundo parametro es opcional, es un objeto de configuracion
        //pero en peticiones de tipo post, es obligatorio
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos         //Para hacer parte de fetch el formData, usamos body y le pasamos la instancia del formdata, con esto, los datos en el append se enviaran
        });
        //Baicamente, el archivo de js va a utilizar un metodo post hacia la url que se le definio
        //la api creada va a verificar si tiene un endpoint registrado, que es asi, y tambien soporta post, y de esa forma se van a conectar, el archivo de js con el controlador (APIController) que se tiene definido

        const resultado = await respuesta.json();

        //resultado.resultado para que solo traigamos el valor booleano, true en este caso
        //Es la forma que esta construido en activerecord
        //(Linea 161)
        if(resultado.resultado) {
            Swal.fire({
                title: "Bien Hecho!",
                text: "Tu cita ha sido creada correctamente!",
                icon: "success",
                button: 'OK'
                //se usa un callbak para
            }).then(() => {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            });
        }

    }catch(error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Parece que algo salio mal al guardar la cita!",
        });
    }
//Si se debe mostrar un mensaje de error, utilizando fetch, no se puede evaluar con un if(resultado.resultado), por que no va a axistir ese resultado

}
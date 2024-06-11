<?php
namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController {
    //Para iniciar sesion
    public static function login(Router $router) {
        $alertas = [];

        //$auth = new Usuario;            //Para autocompletado del login, email y password
        //Trabaja en conjunto con el value del html de login,
        //el value toma como valor php, que a su vez toma un echo con la funcion de sanitizacion y dentro,
        //toma $auth -> email, o contraseña, pero no es nada recomendable.

        //Al dar click en iniciar sesion...
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Instanciamos el modelo de usuario,
            //y le pasamos todo lo que el usuario escriba en POST.
            $auth = new Usuario($_POST);

            //Metodo para validar el login
            $alertas = $auth -> validarLogin();

            //Si el usuario escribio correo y contraseña...
            if(empty($alertas)) {
                //Comprobar que exista el usuario mediante su email
                $usuario = Usuario::whereAll('email', $auth -> email);

                if($usuario) {
                // En caso de que exista el usuario...
                //Verificamos password
                //Le pasamos a la funcion la contraseña que el usuario escribio.
                    if($usuario -> comprobarPasswordAndVerificado($auth -> contraseña)) {
                        //Si todo esta bien, verificado, contraseña correcta, entonces autenticamos al usuario
                        //$usuario ya tiene un ubjeto con los datos del usuario.
                        //Iniciamos la sesion
                        session_start();
                        //Una vez abierta la sesion, tenemos acceso a la superglobal $_SESSION
                        //a la cual le pasamos el id del usuario,
                        //que en general, se le pasaran todos los datos del usuario que creamos necesarios
                        //esto para que no estemos revisando la base de datos a cada rato
                        $_SESSION['id'] = $usuario -> id;
                        $_SESSION['nombre'] = $usuario -> nombre . " " . $usuario -> apellido;
                        $_SESSION['email'] = $usuario -> email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento
                        //En caso de que el usuario sea un admin o no
                        if($usuario -> admin === "1") {
                            //Si es 1, entonces es admin
                            // se agrega a la sesion la variable de admin.
                            $_SESSION['admin'] = $usuario -> admin ?? null;
                            header('Location: /admin');

                        } else {
                            //En caso de que sea 0
                            //Para agendar cita
                            header('Location: /cita');

                        }

                        

                    }
                }else {
                    //En caso de que no...
                    Usuario ::setAlerta('error', 'El correo no existe.');

                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router -> render('auth/login', [
            'alertas'=> $alertas
            //'auth' => $auth             //Para autocompletado del email o password
        ]);

    }

    //Para cerrar sesion
    public static function logout() {
        session_start();

        //Vaciamos la sesion
        $_SESSION = [];

        //Redirigimos al usuario a la pantalla de inicio de sesion
        header('Location: /');
    }

    //Para contraseña olvidada
    public static function forget(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth -> validarEmail();

            if(empty($alertas)) {
                //Validamos que el correo exista
                $usuario = Usuario ::whereAll('email', $auth -> email);

                //Si existe y, si esta confirmado
                if($usuario && $usuario -> confirmado === "1") {
                    //Se genera token
                    //token esta vacio, entonce se genera otro token
                    $usuario -> crearToken();
                    $usuario -> guardar();
                    //Enviar email
                    //Cunado se instancia, se pasan los datos al constructor
                    $email = new Email($usuario -> email, $usuario -> nombre, $usuario -> apellido, $usuario -> token);
                    $email -> enviarInstrucciones();

                    //alerta exito
                    Usuario ::setAlerta('exito', 'Se ha enviado un email de recuperacion, verifique su correo.');
                    

                }else {
                    //En caso de que no exista o no se haya confirmado
                    Usuario ::setAlerta('error', 'El usuario no existe o no esta confirmado');
                    
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router -> render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    //Para recuperar contraseña
    public static function recover(Router $router) {
        $alertas= [];
        $error = false;

        //Se lee el token primero
        $token = s($_GET['token']);

        //Buscar usuario por su token
        $usuario = Usuario ::whereAll('token', $token);

        if(empty($usuario)){
            Usuario ::setAlerta('error', 'Token no Valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer el nuevo password y proceder a guardarlo

            $password = new Usuario($_POST);

            

            $alertas = $password -> validarPassword();
            
            if(empty($alertas)){

                    $usuario -> contraseña = null;
                    //Leemos la contraseña de password
                    //Y finalmente lo volvemos parte del objeto usuario
                    $usuario -> contraseña = $password -> contraseña;
                    //Hasheamos el password
                    $usuario -> hashPassword();

                    //reseteamos token a null
                    $usuario -> token = null;

                    //Nos retorna un resultado
                    $resultado = $usuario -> guardar();

                    //Redireccionamos al usuario
                    if($resultado){
                        header('Location: /');
                    }

                    debuguear($usuario);

            }
        }

        $alertas = Usuario :: getAlertas();
        $router -> render('auth/recover', [
            'alertas' => $alertas,
            'error' => $error
            
        ]);
    }

    //Para crear cuenta nueva
    public static function create(Router $router) {
            //Instanciamos Usuario
            //En esta parte para poder mantener los valores en los input por cualquier cosa.
        $usuario = new Usuario($_POST);

        //Alertas vacias
        $alertas =[];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

           //Sincronizar con los datos de POST
           //Trabaja en conjunto con los echo de value de crear-cuenta.php y
           //con la instancia de usuario.
            $usuario -> sincronizar($_POST);

            //Valdaciones
            //Como retorna un valor, entonces escribimos $alertas=
            $alertas = $usuario -> validarNuevaCuenta();

            //Verificar que alertas este vacio
            if(empty($alertas)) {
                //Verificar que el usuario no este registrado y recibimos el resultado de usuario
                $resultado = $usuario -> existeUsuario();


                //En caso de que haya resultado..
                if($resultado -> num_rows){
                    //se llena alertas, y lo mandamos a la vista.
                    $alertas = Usuario ::getAlertas();
                }else {
                    //Si no esta registrado,
                    //primero, hashear password
                    $usuario -> hashPassword();

                    //segundo, generar token unico
                    $usuario -> crearToken();

                    //tercero, enviamos el email para confirmacion
                    $email = new Email($usuario -> email, $usuario -> nombre, $usuario -> apellido, $usuario -> token);
                    $email -> enviarConfirmacion();

                    //Creamos el usuario
                    $resultado = $usuario -> guardar();
                    if($resultado) {
                        header('Location: /message');
                    }



                }

            }
        }

        $router -> render('auth/create-account', [
            //Al pasar la variable $usuario a la vista, ya tendremos el objeto ahi, en la vista
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    //Mensaje despues de registrarse
    public static function message(Router $router) {
        $router -> render('auth/message');
    }

    //Modelo para la confirmacion de la cuenta
    public static function confirm(Router $router){
        $alertas = [];

        //Para traernos el token
        //Se sanitiza la entrada por que los usuarios pueden andar jugando con la url y borrar datos.
        $token = s($_GET['token']);
        // debuguear($token);
        $usuario = Usuario ::where('token', $token);
        //  debuguear($usuario);

        if(empty($usuario)) {
            //Mostrar mensaje de error si esta vacio, o null.
            Usuario :: setAlerta('error', 'Token No Valido.');
        }else {
            //Si no esta vacio, confirmacion exitosa, modificar a usuario confirmado
            $usuario -> confirmado = "1";
            $usuario -> token = null;
            $usuario -> guardar();
            $usuario -> setAlerta('exito', 'Cuenta Confirmada Correctamente.');

        }

        //Obtener alertas
        //Para que las alertas que se estan guardando en memoria
        //se puedan leer antes de renderizar la vista
        $alertas = Usuario :: getAlertas();
        $router -> render('auth/confirm-account', [
            'alertas' => $alertas
        ]);
    }
}
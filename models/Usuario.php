<?php
namespace Model;

class Usuario extends ActiveRecord {
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'contraseña', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $contraseña;
    public $contraseña2;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;


    public function __construct($args = []) {
        $this -> id = $args['id'] ?? null;
        $this -> nombre = $args['nombre'] ?? '';
        $this -> apellido = $args['apellido'] ?? '';
        $this -> email = $args['email'] ?? '';
        $this -> contraseña = $args['password'] ?? '';
        $this -> contraseña2 = $args['password2'] ?? '';
        $this -> telefono = $args['telefono'] ?? '';
        $this -> admin = $args['admin'] ?? 0;
        $this -> confirmado = $args['confirmado'] ?? 0;
        $this -> token = $args['token'] ?? '';
    }

    //Mensjaes de validacion para la creacion de una cuenta
    public function validarNuevaCuenta()
    {
        if(!$this -> nombre) {
            self :: $alertas['error'][] = 'El nombre es obligatorio.';
        }

        if(!$this -> apellido) {
            self :: $alertas['error'][] = 'Los apellidos son obligatorios.';
        }

        if(!$this -> email) {
            self :: $alertas['error'][] = 'El correo es obligatorio.';
        }

        if(!$this -> contraseña) {
            self :: $alertas['error'][] = 'La contraseña es obligatoria.';
        }
        if(strlen($this -> contraseña) < 8) {
            self :: $alertas['error'][] = 'La contraseña debe contener al menos 8 caracteres.';
        }
        if($this -> contraseña != $this -> contraseña2) {
            self :: $alertas['error'][] = 'Las contraseñas no coinciden.';
        }
        if(!$this -> telefono) {
            self :: $alertas['error'][] = 'Un numero de telefono es obligatorio.';
        }
        return self::$alertas;
    }

    //Metodo de validacion para login
    //No toma nada por que ya hay un objeto con la informacion
    public function validarLogin() {
        if(!$this -> email){
            self :: $alertas['error'][] = 'El campo de Correo es obligatorio.';
        }
        if(!$this -> contraseña){
            self :: $alertas['error'][] = 'El campo de Contraseña es obligatorio.';
        }

        return self :: $alertas;
    }

    //Metodo de validacion para email
    //En caso de contraseña olvidada
    public function validarEmail() {
        if(!$this -> email) {
            self :: $alertas['error'][] = 'El correo es obligatorio';
        }
        return self :: $alertas;
    }

    //Metodo de validacion para contraseña
    //En caso de recuperacion
    public function validarPassword(){
        if(!$this -> contraseña) {
            self::$alertas['error'][] = 'Una contraseña nueva es obligatoria.';
        }
        if(strlen($this -> contraseña < 8)){
            self::$alertas['error'][] = 'La contraseña debe de tener como minimo 8 caracteres.';
        }

        return self ::$alertas;
    }

    //Metodo para saber si el usuario ya existe o no
    public function existeUsuario(){
        //self por ser static / this por ser public y los datos ya estan en las variables / LIMIT 1 para que solo traiga el primer dato.
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        //Con esto y el debuguear(), podemos ver la sintaxis de objeto al dar click en crear cuenta
        $resultado = self::$db -> query($query);

        //Si hay un resultado, es que ya esta registrado, entonces...
        if($resultado -> num_rows){
            self :: $alertas['error'][] = 'El usuario ya existe.';
        }
        
        //retorna el resultado a logincontroller
        return $resultado;

    }

    //Metodo para hashear password
    public function hashPassword() {
        //La funcion toma el mismo password, lo va a leer y lo vuelve a asignar en el mismo lugar
        $this -> contraseña = password_hash($this -> contraseña, PASSWORD_BCRYPT);
    }

    //Metodo para la creacion de un token unico
    public function crearToken(){
        $this -> token = uniqid();
    }

    //Recibimos de logincontroller la contraseña que el usuario escriobio,
    //y la comparamos con la que ya esta en la base de datos, $this -> contraseña,
    //la funcion password_verify devuelve un true
    public function comprobarPasswordAndVerificado($contraseña) {
        $resultado = password_verify($contraseña, $this -> contraseña);
        
        //Revisamos si esta verificado el usuario...
        //en negacion, en caso de que no...
        //A grosso modo
        //Si no esta confirmada la cuenta o si la contraseña es incorrecta
        if(!$resultado || !$this -> confirmado) {
            self :: $alertas['error'][] = 'La contraseña es incorrecta o tu cuenta no ha sido confirmada aun.';
        }else {
            //En caso de que retorne true, osea, sin alertas, pasa a logincontroller.
            return true;

        }

    }

}
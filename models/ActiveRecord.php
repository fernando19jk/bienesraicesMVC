<?php
namespace Model;

class ActiveRecord {
    
    //Base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //Errores
    protected static $errores = [];



    //Definir la conexion a la db
    public static function setDB($database) {
        self::$db = $database;
    }



    public function guardar() {

        if (!empty($this->id)) {
            //Actualizar
            $this->actualizar();
        }else {
            //Creando nuevo registro
            $this->crear();
        }
    }

    public function crear() {

        //Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        //Insertar en la db
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(',', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("','",array_values($atributos));
        $query .= " ') ";
        // debuguear($query);

        $resultado = self::$db->query($query);
        if ($resultado) {
            //redireccionar al usuario
            header('Location: /admin?resultado=1');
        }
    }

    public function actualizar() {
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE " .  static::$tabla  . "  SET ";
        $query .= join(',',$valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";
         
        $resultado = self::$db->query($query);
        if ($resultado) {
            //redireccionar al usuario
            header('Location: /admin?resultado=2');
        }
    }

    //Eliminar un registro
    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla  . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if ($resultado) {
            $this->borrarImagen();
            //redireccionar al usuario
            header('Location: /admin?resultado=3');
        }
    }

    //Identificar y unir los atributos de la base de datos
    public function atributos() {
        $atributos =  [];
        foreach (static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        $sanitizado = [];


        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;

    }
    //Subida de archivos
    public function setImage($imagen){
        //Elimina la imagen previa
        // echo '<pre>'.var_dump(!is_null($this->id)).'</pre>';
        if (!empty($this->id) ) {
            //Comprobar si existe el archivo
            $this->borrarImagen();
        }

        //Asignar al atributo de imagen el nombre de la imagen
        if($imagen) {
            $this->imagen = $imagen;
        }
    }

    //eliminar archivo
    public function borrarImagen() {
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    //Validacion
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {

        static::$errores = [];
        return static::$errores;
    }

    //Lista todas los registros
    public static function all() {
        $query = "SELECT * FROM ". static::$tabla;
        return $resultado = self::consultarSQL($query);
    }

    //Obtiene determinado numero de registros
    public static function get($cantidad) {
        $query = "SELECT * FROM ". static::$tabla . " LIMIT " . $cantidad;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    //Busca una registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = $id";

        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query) {
        //Consultar la db
        $resultado = self::$db->query($query);

        //Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc())
        {
            $array[] = static::crearObjeto($registro);
        }
        // debuguear($array);

        //Liberar la memoria
        $resultado->free();

        //retornar los resultados
        
        return $array;
    }
    //de array a objeto se queda en memoria
    protected static function crearObjeto($registro) {
        $objeto = new static;
        
        foreach ($registro as $key => $value) {
            if (property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }
        // debuguear($objeto);
        return $objeto;
    }

    //Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar( $args = []){
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
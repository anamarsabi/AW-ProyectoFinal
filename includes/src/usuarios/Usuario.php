<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Usuario
{
    use MagicProperties;

    public const ADMIN_ROLE = 0;

    public const ROOMIE_ROLE = 1;

    public const HOST_ROLE = 2;

    public static function login($correo, $password)
    {
        $usuario = self::buscaUsuario($correo);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return self::cargaRoles($usuario);
        }
        else{
            //print("Falla al comprobar password");
        }
        return false;
    }

    public static function crea($email, $nombre, $apellido1, $apellido2, $fecha_nacimiento, $password, $rol){
        
        $user = new Usuario($email, $nombre, $apellido1, $apellido2, $fecha_nacimiento, self::hashPassword($password));
        $user->añadeRol($rol);
        return $user->guarda();
 
        return false;
    }

    public static function buscaUsuario($correo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE correo='%s'", $conn->real_escape_string($correo));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['correo'], $fila['nombre'], $fila['apellido1'],  $fila['apellido2'], $fila['fecha_nacimiento'],$fila['contrasenia'], $fila['id_usuario']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            //print("No rs");
        }
        return $result;
    }

    public static function buscaPorId($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE id_usuario=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['correo'], $fila['nombre'], $fila['apellido1'],  $fila['apellido2'], $fila['fecha_nacimiento'],$fila['contrasenia'], $fila['id_usuario']);
                self::cargaRoles($result);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
    
    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private static function cargaRoles($usuario)
    {
        $roles=[];
            
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT rol FROM rolesusuario WHERE id_usuario=%d"
            , $usuario->id
        );
        $rs = $conn->query($query);
        if ($rs) {
            $roles = $rs->fetch_all(MYSQLI_ASSOC);
            $rs->free();

            $usuario->roles = [];
            foreach($roles as $rol) {
                $usuario->roles[] = intval($rol['rol']);
            }
            return $usuario;

        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return false;
    }
   
    private static function inserta($usuario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        $query = sprintf("INSERT INTO usuarios (correo, nombre, apellido1, apellido2, contrasenia, fecha_nacimiento)  
                        VALUES ('%s','%s', '%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($usuario->correo)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->apellido1)
            , $conn->real_escape_string($usuario->apellido2)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->birthday)
        );
        if ( $conn->query($query) ) {
            $usuario->id = $conn->insert_id;
            $result = self::insertaRoles($usuario);
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
   
    private static function insertaRoles($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        //print("id --> ". $usuario->id);

        foreach($usuario->roles as $rol) {
            //print("rol --> ". $rol);
            $query = sprintf("INSERT INTO rolesusuario(id_usuario, rol) VALUES (%d, %d)"
                , $usuario->id
                , $rol
            );
            if ( ! $conn->query($query) ) {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return $usuario;
    }
    
    private static function actualiza($usuario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO usuarios (correo, nombre, apellido1, apellido2, contrasenia, fecha_nacimiento)  
                        VALUES ('%s','%s', '%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($usuario->correo)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->apellido1)
            , $conn->real_escape_string($usuario->apellido2)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->birthday)
        );
        if ( $conn->query($query) ) {
            $result = self::borraRoles($usuario);
            if ($result) {
                $result = self::insertaRoles($usuario);
            }
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }
   
    private static function borraRoles($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        // La sentencia anterior --> "SELECT RU.rol FROM RolesUsuario RU WHERE RU.usuario=%d"
        // daba error de sintaxis >:v
        $query = sprintf("DELETE FROM rolesusuario WHERE usuario=%d"
            , $usuario->id
        );

        //print($query);
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $usuario;
    }
    
    private static function borra($usuario)
    {
        return self::borraPorId($usuario->id);
    }
    
    public static function borraPorId($idUsuario)
    {
        if (!$idUsuario) {
            return false;
        } 
        /* Los roles se borran en cascada por la FK
         * $result = self::borraRoles($usuario) !== false;
         */
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM usuarios WHERE id_usuario = %d"
            , $idUsuario
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    private $id;

    private $nombreUsuario; //No se usa.

    private $correo;

    private $nombre;

    private $apellido1;

    private $apellido2;

    private $password;

    private $birthday;

    private $roles = array();


    private function __construct($email, $nombre, $apellido1, $apellido2, $fecha_nacimiento, $password, $id = null, $roles = array())
    {
        $this->id = $id;
        $this->correo = $email;
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->password = $password;
        $this->birthday = $fecha_nacimiento;
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido1()
    {
        return $this->apellido1??"";
    }

    public function getApellido2()
    {
        return $this->apellido2??"";
    }

    public function getBirthday()
    {
        return $this->birthday;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function añadeRol($role)
    {
        $this->roles[] = $role;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function tieneRol($role)
    {
        if ($this->roles == null) {
            self::cargaRoles($this);
        }
 
        return array_search($role, $this->roles) !== false;
    }

    public function compruebaPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function cambiaPassword($nuevoPassword)
    {
        $this->password = self::hashPassword($nuevoPassword);
    }


    public function cambiaDatosPersonales($datos)
    {
        $this->nombre = $datos['nombre'];
        $this->apellido1 = $datos['apellido1'];
        $this->apellido2 = $datos['apellido2'];
        $this->birthday = $datos['birthday'];
    }
    
    public function guarda()
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    public static function actualiza_dp($usuario){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE usuarios SET nombre = '%s', apellido1='%s', apellido2='%s', fecha_nacimiento='%s' WHERE id_usuario=%d"
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->apellido1)
            , $conn->real_escape_string($usuario->apellido2)
            , $conn->real_escape_string($usuario->birthday)
            , $usuario->id
        );
        if ( $conn->query($query) ) {
            $result = true;
            // $result = self::borraRoles($usuario);
            // if ($result) {
            //     $result = self::insertaRoles($usuario);
            // }
            // else{
            //     print("AAAAA");
            // }

        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }

    public static function actualiza_pwd($usuario){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE usuarios SET contrasenia = '%s' WHERE id_usuario=%d"
            , $conn->real_escape_string($usuario->password)
            , $usuario->id
        );
        if ( !$conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        else{
            $result=true;
        }
        
        return $result;
    }
    
    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }

    public static function obtieneRol($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM rolesusuario WHERE id_usuario=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = $fila['rol'];
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function obtieneNombreRol($rolUsuario)
    {
       if( $rolUsuario==0){
           $result="Admin";
       }elseif( $rolUsuario==1){
            $result="Roomie";
       }else{
            $result="Host";
       }

        return $result;
    }

    public static function getUsuarios()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios");
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[] = new Usuario($fila['correo'], $fila['nombre'], $fila['apellido1'],  $fila['apellido2'], $fila['fecha_nacimiento'],$fila['contrasenia'], $fila['id_usuario']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

}

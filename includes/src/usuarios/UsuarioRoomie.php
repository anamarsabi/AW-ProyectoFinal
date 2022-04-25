<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class UsuarioRoomie extends Usuario
{   
    use MagicProperties;
    private $idUsuario;
    private $descripcion;
    private $aficiones = array();
    private $mascota;

    private function __construct($idUsuario,$aficiones, $descripcion, $mascota)
    {
        $this->idUsuario = $idUsuario;
        $this->descripcion = $descripcion;
        $this->mascota=$mascota;
        $this->aficiones = $aficiones;
    }

    public static function creaRoomie($idUsuario,$aficiones,$descripcion,$mascota){
      
        $user = new UsuarioRoomie($idUsuario,$aficiones,$descripcion,$mascota);
        return $user->guardaRoomie();
 
        return false;
    }

    public function guardaRoomie()
    {
        return self::inserta($this);
    }

    private static function inserta($usuario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        $query = sprintf("INSERT INTO roomies (id_usuario, tiene_mascota, descripcion)  
                        VALUES ('%d', '%s', '%s')"
            , $conn->real_escape_string($usuario->idUsuario)
            , $conn->real_escape_string($usuario->mascota)
            , $conn->real_escape_string($usuario->descripcion)
        );

        $array=$usuario->aficiones;
    
        foreach($array as $a){
            $query2 = sprintf("SELECT * FROM aficiones U WHERE U.nombre='%s'", $conn->real_escape_string($a));
            $rs = $conn->query($query2);
            $result = false;
            if ($rs) {
                $fila = $rs->fetch_assoc();
                if ($fila) {
                    $id_af = $fila['id_aficion'];
                }
                $rs->free();
            }

            $query3 = sprintf("INSERT INTO roomie_aficiones (id_aficion, id_usuario)  
                            VALUES ('%d','%d')"
                    ,$conn->real_escape_string($id_af)
                    ,$conn->real_escape_string($usuario->idUsuario)
            );
        }

        if ($conn->query($query) and $conn->query($query2) and $conn->query($query3)) {
            //print ("Se ha insertado correctamente Roomie");
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }


    public function cambiaDatosPersonales($datos)
    {
        $this->aficiones = $datos['aficiones'];
        $this->descripcion = $datos['descripcion'];
        $this->mascota = $datos['mascota'];
    }

    public static function actualiza_dp($usuario){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE roomies U SET descripcion = '%s', tiene_mascota='%s' WHERE U.id_usuario='%d'"
            , $conn->real_escape_string($usuario->descripcion)
            , $conn->real_escape_string($usuario->mascota)
            , $usuario->idUsuario
        );

        
        $array=$usuario->aficiones;
        if(!empty($af)){
            foreach($array as $a){
                $query2 = sprintf("SELECT * FROM aficiones U WHERE U.nombre='%s'", $conn->real_escape_string($a));
                $rs = $conn->query($query2);
                $result = false;
                if ($rs) {
                    $fila = $rs->fetch_assoc();
                    if ($fila) {
                        $id_af = $fila['id_aficion'];
                    }
                    $rs->free();
                }
                $conn->query($query2);
                $query3 = sprintf("INSERT INTO roomie_aficiones (id_aficion, id_usuario)  
                                VALUES ('%d','%d') WHERE id_aficion<>$id_af"
                        ,$conn->real_escape_string($id_af)
                        ,$conn->real_escape_string($usuario->idUsuario)
                );
                $conn->query($query3);
            }
        }

        if ( $conn->query($query)) {
            $result = true;

        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }

    public static function buscaRoomiePorId($idUsuario, $af)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM roomies WHERE id_usuario=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new UsuarioRoomie($fila['id_usuario'],$af,$fila['descripcion'], $fila['tiene_mascota']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    // public function getDescripcion()
    // {
    //     return $this->descripcion;
    // }

    // public function getMascota()
    // {
    //     return $this->mascota;
    // }

}

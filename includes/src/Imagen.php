<?php
namespace es\ucm\fdi\aw;

class Imagen
{
    public static function crea($nombre, $mimeType, $ruta, $id_piso)
    {
        $imagen = new Imagen($ruta, $nombre, $mimeType, $id_piso);
        return $imagen;
    }

    public static function listaImagenes()
    {
        return self::getImagenes();
    }

    public static function getImagenes()
    {
        $result = [];
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = 'SELECT * FROM imagenes_prueba';
        
        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[] = new Imagen($fila['ruta'], $fila['nombre'], $fila['mimeType'], $fila['id'], $fila['id_piso']);
            }
            $rs->free();
        } else {
            error_log($conn->error);
        }

        return $result;
    }

    public static function buscaPorId($idImagen)
    {
        $result = null;

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf('SELECT * FROM imagenes_prueba WHERE id = %d', intval($idImagen));
        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result = new Imagen($fila['ruta'], $fila['nombre'], $fila['mimeType'], $fila['id'], $fila['id_piso']);
            }
            $rs->free();
        } else {
            error_log($conn->error);
        }

        return $result;
    }

    public static function buscaPorId_piso($id_piso)
    {
        $result = [];

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf('SELECT * FROM imagenes_prueba WHERE id_piso = %d', intval($id_piso));
        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[] = new Imagen($fila['ruta'], $fila['nombre'], $fila['mimeType'], $fila['id'], $fila['id_piso']);
            }
            $rs->free();
        } else {
            error_log($conn->error);
        }

        return $result;
    }

    public static function printImagenes_idPiso($id_piso)
    {
        /* https://www.w3schools.com/css/css3_images.asp */
        $result = self::buscaPorId_piso($id_piso);
        $total = count($result);
        $html_imagenes ="";
        $html_imagenes .=<<<EOF
            <h2>Imagenes Piso: {$total} </h2>

        EOF;
        foreach($result as $imagen)
        {
            $html_imagenes .=<<<EOF
                <div class="polaroid">
                    <img class="h-100 w-100" src="almacenPublico/$imagen->ruta">
                    <div class="container-texto">
                        <p>{$imagen->nombre}</p>
                    </div>
                </div>
            EOF;
        }
        #$html_imagenes .= "</div>";
        return $html_imagenes;
    }

    public static function getPortada($id_piso)
    {
        $html_portada ="";
        $result = self::buscaPorId_piso($id_piso);
        if (count($result)===0)
        {
            $html_portada .=<<<EOF
                <img class="h-100 w-10e fpiso" src="img/logo.png" alt="Imagen">
            EOF;
        }
        else
        {
            $imagen = $result[0];
            $html_portada .=<<<EOF
                <img class="h-100 w-10e" src="almacenPublico/$imagen->ruta">
            EOF;
        }
        return $html_portada;
    }

    private static function inserta($imagen)
    {
        $result = false;

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf(
            "INSERT INTO imagenes_prueba (ruta, nombre, mimeType, id_piso) VALUES ('%s', '%s', '%s', %s)",
            $conn->real_escape_string($imagen->ruta),
            $conn->real_escape_string($imagen->nombre),
            $conn->real_escape_string($imagen->mimeType),
            $imagen->id_piso
        );

        $result = $conn->query($query);
        if ($result) {
            $imagen->id = $conn->insert_id;
            $result = $imagen;
        } else {
            error_log($conn->error);
        }

        return $result;
    }

    private static function actualiza($imagen)
    {
        $result = false;

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf(
            "UPDATE imagenes_prueba SET ruta = '%s', nombre = '%s', mimeType = '%s', id_piso = %s WHERE id = %d",
            $conn->real_escape_string($imagen->ruta),
            $conn->real_escape_string($imagen->nombre),
            $conn->real_escape_string($imagen->mimeType),
            $conn->real_escape_string($imagen->id_piso),
            $imagen->id
        );
        $result = $conn->query($query);
        if (!$result) {
            error_log($conn->error);
        } else if ($conn->affected_rows != 1) {
            error_log(__CLASS__ . ": Se han actualizado '$conn->affected_rows' !");
        }

        return $result;
    }

    private static function borra($imagen)
    {
        return self::borraPorId($imagen->id);
    }

    private static function borraPorId($idImagen)
    {
        $result = false;

        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM imagenes_prueba WHERE id = %d", intval($idImagen));
        $result = $conn->query($query);
        if (!$result) {
            error_log($conn->error);
        } else if ($conn->affected_rows != 1) {
            error_log("Se han borrado '$conn->affected_rows' !");
        }

        return $result;
    }

    use MagicProperties;

    private $id;

    private $ruta;

    private $nombre;

    private $mimeType;

    private $id_piso;

    private function __construct($ruta, $nombre, $mimeType, $id_piso, $id = NULL)
    {
        $this->ruta = $ruta;
        $this->nombre = $nombre;
        $this->mimeType = $mimeType;
        $this->id_piso = $id_piso;
        $this->id = intval($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRuta()
    {
        return $this->ruta;
    }

    public function setRuta($nuevaRuta)
    {
        $this->ruta = $nuevaRuta;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nuevoNombre)
    {
        $this->nombre = $nuevoNombre;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getId_piso()
    {
        return $this->id_piso;
    }

    public function setId_piso($nuevoId_piso)
    {
        $this->id_piso = $nuevoId_piso;
    }

    public function guarda()
    {
        if (!$this->id) {
            self::inserta($this);
        } else {
            self::actualiza($this);
        }

        return $this;
    }

    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }
}
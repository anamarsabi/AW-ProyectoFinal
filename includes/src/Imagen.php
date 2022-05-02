<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\usuarios\FormularioBotonDeleteImagen;

class Imagen
{

    const EXTENSIONES_PERMITIDAS = array('gif', 'jpg', 'jpe', 'jpeg', 'png', 'webp', 'avif');

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
        $query = 'SELECT * FROM imagenes_pisos';
        
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
        $query = sprintf('SELECT * FROM imagenes_pisos WHERE id = %d', intval($idImagen));
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

    public static function buscaPorIdEntidad($id_piso)
    {
        $result = [];

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf('SELECT * FROM imagenes_pisos WHERE id_piso = %d', intval($id_piso));
        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[] = new Imagen($fila['ruta'], $fila['nombre'], $fila['mimeType'], $fila['id_piso'], $fila['id']);
            }
            $rs->free();
        } else {
            error_log($conn->error);
        }

        return $result;
    }

    public static function getHTMLImagenesPorIdEntidad($id, $url_redireccion="", $delForm=false)
    {
        /* https://www.w3schools.com/css/css3_images.asp */
        $result = self::buscaPorIdEntidad($id);
        $total = count($result);
        $html_imagenes ="";
        $html_imagenes .=<<<EOF
            <h2>Imagenes: {$total} </h2>
            <div class="flex-wrapper">
        EOF;
       
        foreach($result as $imagen)
        {
            $form = $delForm
                        ?(new FormularioBotonDeleteImagen($imagen->id, $url_redireccion))->gestiona()
                        :"";
            $html_imagenes .=<<<EOF
                <div class="polaroid w-100p mx-1e">
                    <img class="w-100" src="almacenPublico/$imagen->ruta">
                    <div class="container-texto">
                        <p>{$imagen->nombre}</p>
                        $form
                    </div>
                </div>
            EOF;
        }
        $html_imagenes .= "</div>";
        return $html_imagenes;
    }

    public static function getPortada($id)
    {
        $html_portada ="";
        $result = self::buscaPorIdEntidad($id);
        if (count($result)===0)
        {
            $html_portada .=<<<EOF
                <img class="h-100 w-10e " src="img/logo.png" alt="Imagen">
            EOF;
        }
        else
        {
            $imagen = $result[0];
            $html_portada .=<<<EOF
                <img class="fpiso" src="almacenPublico/$imagen->ruta">
            EOF;
        }
        return $html_portada;
    }

    public static function getPortadaHabitacion($id_hab)
    {
        $html_portada ="";
        $result = self::buscaPorIdEntidadHabitacion($id_hab);
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
            "INSERT INTO imagenes_pisos (ruta, nombre, mimeType, id_piso) VALUES ('%s', '%s', '%s', %s)",
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
            "UPDATE imagenes_pisos SET ruta = '%s', nombre = '%s', mimeType = '%s', id_piso = %s WHERE id = %d",
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

    public static function borraPorId($idImagen)
    {
        $result = false;

        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM imagenes_pisos WHERE id = %d", intval($idImagen));
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




    /**
     * Check $_FILES[][name]
     *
     * @param (string) $filename - Uploaded file name.
     * @author Yousef Ismaeil Cliprz
     * @See http://php.net/manual/es/function.move-uploaded-file.php#111412
     */
    public static function check_file_uploaded_name($filename)
    {
        return (bool) ((preg_match("`^[-0-9A-Z_\.]+$`i",$filename)) ? true : false);
    }
    /**
     * Sanitize $_FILES[][name]. Remove anything which isn't a word, whitespace, number
     * or any of the following caracters -_~,;[]().
     *
     * If you don't need to handle multi-byte characters you can use preg_replace
     * rather than mb_ereg_replace.
     * 
     * @param (string) $filename - Uploaded file name.
     * @author Sean Vieira
     * @see http://stackoverflow.com/a/2021729
     */
    public static function sanitize_file_uploaded_name($filename)
    {
        /* Remove anything which isn't a word, whitespace, number
     * or any of the following caracters -_~,;[]().
     * If you don't need to handle multi-byte characters
     * you can use preg_replace rather than mb_ereg_replace
     * Thanks @Åukasz Rysiak!
     */
        $newName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
        // Remove any runs of periods (thanks falstro!)
        $newName = mb_ereg_replace("([\.]{2,})", '', $newName);

        return $newName;
    }

    /**
     * Check $_FILES[][name] length.
     *
     * @param (string) $filename - Uploaded file name.
     * @author Yousef Ismaeil Cliprz.
     * @See http://php.net/manual/es/function.move-uploaded-file.php#111412
     */
    public static function check_file_uploaded_length($filename)
    {
        return (bool) ((mb_strlen($filename, 'UTF-8') < 250) ? true : false);
    }



}
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
        $result = [];

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf('SELECT * FROM imagenes_pisos WHERE id = %d', intval($idImagen));
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

    public static function buscaPorIdEntidad($id, $tabla="imagenes_pisos", $entidad="id_piso")
    {
        $result = [];

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf('SELECT * FROM '.$tabla.' WHERE '.$entidad.' = %d', intval($id));
        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[] = new Imagen($fila['ruta'], $fila['nombre'], $fila['mimeType'], $fila[$entidad], $fila['id']);
            }
            $rs->free();
        } else {
            error_log($conn->error);
        }

        return $result;
    }
    
    public static function getHTMLImagenes($datos)
    {
        //Id de la entidad que tiene las imágenes
        $id = $datos['id'];
        //Si quiere o no el botón de borrar imagen
        $delForm = $datos['delForm']??"false"; 
        //Si quiere el botón de borrar, a dónde redirigir si se borra
        $url_redireccion = $datos['url_redireccion']??"index.php"; 
        //Nombre de la tabla en la BD
        $tabla = $datos['tabla']??null; 
        //Nombre de la columna id_entidad
        $entidad = $datos['entidad']??null;

        /* https://www.w3schools.com/css/css3_images.asp */
        $result = self::buscaPorIdEntidad($id, $tabla, $entidad);
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
                    <img class="w-100" src="almacenPublico/$imagen->ruta" alt="$imagen->nombre">
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

    public static function getPortada($datos)
    {
        //Id de la entidad que tiene las imágenes
        $id = $datos['id'];
        //Nombre de la tabla en la BD
        $tabla = $datos['tabla']??null; 
        //Nombre de la columna id_entidad
        $entidad = $datos['entidad']??null;

        $html_portada ="";
        $result = self::buscaPorIdEntidad($id, $tabla, $entidad);
        if (count($result)===0)
        {
            $html_portada .=<<<EOF
                <img class="h-100 w-10e" src="img/logo.png" alt="Image_not_found">
            EOF;
        }
        else
        {
            $imagen = $result[0];
            $html_portada .=<<<EOF
                <img class="fpiso" src="almacenPublico/$imagen->ruta" alt="$imagen->nombre">
            EOF;
        }
        return $html_portada;
    }

    private static function inserta($imagen, $entidad="id_piso", $tabla="imagenes_pisos")
    {
        $result = false;

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf(
            "INSERT INTO ".$tabla." (ruta, nombre, mimeType, ".$entidad.") VALUES ('%s', '%s', '%s', %s)",
            $conn->real_escape_string($imagen->ruta),
            $conn->real_escape_string($imagen->nombre),
            $conn->real_escape_string($imagen->mimeType),
            $imagen->id_entidad
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

    private static function inserta_habitacion($imagen)
    {
        $result = false;

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf(
            "INSERT INTO imagenes_habitaciones (ruta, nombre, mimeType, id_habitacion) VALUES ('%s', '%s', '%s', %s)",
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


    

    private static function actualiza($imagen, $entidad="id_piso", $tabla="imagenes_pisos")
    {
        $result = false;

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf(
            "UPDATE ".$tabla." SET ruta = '%s', nombre = '%s', mimeType = '%s', ".$entidad." = %s WHERE id = %d",
            $conn->real_escape_string($imagen->ruta),
            $conn->real_escape_string($imagen->nombre),
            $conn->real_escape_string($imagen->mimeType),
            $conn->real_escape_string($imagen->id_entidad),
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

    private static function actualiza_habitacion($imagen)
    {
        $result = false;

        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $query = sprintf(
            "UPDATE imagenes_habitaciones SET ruta = '%s', nombre = '%s', mimeType = '%s', id_habitacion = %s WHERE id = %d",
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

    private static function borra_habitacion($imagen)
    {
        return self::borraPorId_habitacion($imagen->id);
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

    public static function borraPorId_habitacion($idImagen)
    {
        $result = false;

        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM imagenes_habitaciones WHERE id = %d", intval($idImagen));
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

    private $id_entidad;

    private function __construct($ruta, $nombre, $mimeType, $id_entidad, $id = NULL)
    {
        $this->ruta = $ruta;
        $this->nombre = $nombre;
        $this->mimeType = $mimeType;
        $this->id_entidad = $id_entidad;
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

    public function getId_entidad()
    {
        return $this->id_entidad;
    }

    public function setId_entidad($nuevo)
    {
        $this->id_entidad = $nuevo;
    }

    public function guarda($entidad="id_piso", $tabla="imagenes_pisos")
    {
        if (!$this->id) {
            self::inserta($this, $entidad, $tabla);
        } else {
            self::actualiza($this, $entidad, $tabla);
        }

        return $this;
    }

    // public function guarda_habitacion()
    // {
    //     if (!$this->id) {
    //         self::inserta_habitacion($this);
    //     } else {
    //         self::actualiza_habitacion($this);
    //     }

    //     return $this;
    // }

    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }


    public static function insertaImagen($datos){

        $tam = count($_FILES['archivos']['name']);
        $errores = Array();
        if($tam && $_FILES['archivos']['name'][0]==""){$tam=0;}

        $id_entidad = $datos['id_entidad'];
        $carpeta = $datos['carpeta'];
        $entidad = $datos['entidad'];
        $tabla = $datos['tabla'];


        for($i=0; $i<$tam; $i++){
            $nombre = $_FILES['archivos']['name'][$i];
            $ok = self::check_file_uploaded_name($nombre) && self::check_file_uploaded_length($nombre);

            /* 2. comprueba si la extensiÃ³n estÃ¡ permitida */
            $extension = pathinfo($nombre, PATHINFO_EXTENSION);
            $ok = $ok && in_array($extension, self::EXTENSIONES_PERMITIDAS);

            /* 3. comprueba el tipo mime del archivo corresponde a una imagen image/* */
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['archivos']['tmp_name'][$i]);

            $ok = preg_match('/image\/*./', $mimeType);

            if (!$ok) {
                $errores[] = 'El archivo tiene un nombre o tipo no soportado';
            }

            if (count($errores) == 0) {
           
                $tmp_name = $_FILES['archivos']['tmp_name'][$i];

                $imagen = Imagen::crea($nombre, $mimeType, '', $id_entidad);
                $imagen->guarda($entidad, $tabla);
                $fichero = "{$imagen->id}.{$extension}";
                $imagen->setRuta($carpeta.'/'.$fichero);
                $imagen->guarda($entidad, $tabla);
                $ruta = implode(DIRECTORY_SEPARATOR, [RUTA_ALMACEN_PUBLICO, $carpeta, $fichero]);
                if (!move_uploaded_file($tmp_name, $ruta)) {
                    $errores[] = 'Error al mover el archivo';
                }
            }

            return $errores;
        }
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
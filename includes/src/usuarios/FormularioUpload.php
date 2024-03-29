<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Imagen;

class FormularioUpload extends Form
{
    private $id_piso;

    const EXTENSIONES_PERMITIDAS = array('gif', 'jpg', 'jpe', 'jpeg', 'png', 'webp', 'avif');

    public function __construct($id)
    {
        $this->id_piso = $id;
        parent::__construct('subir'.$this->id_piso, ['enctype' => 'multipart/form-data', 'urlRedireccion' => 'addFotos_piso.php']);
    }

    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['archivos'], $this->errores, 'span', array('class' => 'error'));

        $app = Aplicacion::getInstance();
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);

        $id_form = 'subir'.$id_piso;

        $html = <<<EOS
        $htmlErroresGlobales
     
            <div>
                <input type="file" name="archivos[]" onchange="loadFile(event)" multiple/>
                {$erroresCampos['archivos']}
            </div>

            <div id="output" class="flex-wrapper">
            
            </div>

            <script>
                var loadFile = function(event) {
                    var output = document.getElementById('output');

                    Array.prototype.forEach.call(event.target.files, function(valor, indice, array) {
                        var aux = URL.createObjectURL(valor);
                        output.innerHTML += '<div class="tag"><img class="img-preview" src=' + aux + '></div>';
                    });
                };

            </script>            
      
        EOS;

        return $html;
    }

    protected function procesaFormulario($datos)
    {
        $this->errores = [];
        $app = Aplicacion::getInstance();
        //Comenta por qué haces get y put 
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);

        // Verificamos que la subida ha sido correcta
        // $ok = $_FILES['archivo']['error'] == UPLOAD_ERR_OK && count($_FILES) == 1;
        // if (! $ok ) {
        //     $this->errores['archivo'] = 'Error al subir el archivo';
        //     return;
        // }  

        $nombre = $_FILES['archivo']['name'];

        /* 1.a) Valida el nombre del archivo */
        $ok = self::check_file_uploaded_name($nombre) && $this->check_file_uploaded_length($nombre);

        /* 1.b) Sanitiza el nombre del archivo (elimina los caracteres que molestan)
        $ok = self::sanitize_file_uploaded_name($nombre);
        */

        /* 1.c) Utilizar un id de la base de datos como nombre de archivo */
        // Vamos a optar por esta opciÃ³n que es la que se implementa mÃ¡s adelante

        /* 2. comprueba si la extensiÃ³n estÃ¡ permitida */
        $extension = pathinfo($nombre, PATHINFO_EXTENSION);
        $ok = $ok && in_array($extension, self::EXTENSIONES_PERMITIDAS);

        /* 3. comprueba el tipo mime del archivo corresponde a una imagen image/* */
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($_FILES['archivo']['tmp_name']);
        $ok = preg_match('/image\/*./', $mimeType);

        if (!$ok) {
            $this->errores['archivo'] = 'El archivo tiene un nombre o tipo no soportado';
        }

        if (count($this->errores) > 0) {
            return;
        }

        $tmp_name = $_FILES['archivo']['tmp_name'];

        $imagen = Imagen::crea($nombre, $mimeType, '', $id_piso);
        $imagen->guarda();
        $fichero = "{$imagen->id}.{$extension}";
        $imagen->setRuta($fichero);
        $imagen->guarda();
        $ruta = implode(DIRECTORY_SEPARATOR, [RUTA_ALMACEN_PUBLICO, $fichero]);
        if (!move_uploaded_file($tmp_name, $ruta)) {
            $this->errores['archivo'] = 'Error al mover el archivo';
        }
    }


    /**
     * Check $_FILES[][name]
     *
     * @param (string) $filename - Uploaded file name.
     * @author Yousef Ismaeil Cliprz
     * @See http://php.net/manual/es/function.move-uploaded-file.php#111412
     */
    private static function check_file_uploaded_name($filename)
    {
        return (bool) ((mb_ereg_match('/^[0-9A-Z-_\.]+$/i', $filename) === 1) ? true : false);
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
    private static function sanitize_file_uploaded_name($filename)
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
    private function check_file_uploaded_length($filename)
    {
        return (bool) ((mb_strlen($filename, 'UTF-8') < 250) ? true : false);
    }
}
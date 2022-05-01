<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Piso;
use es\ucm\fdi\aw\Imagen;
use es\ucm\fdi\aw\Busqueda;

class FormularioEditDatosPiso extends Form{

    const EXTENSIONES_PERMITIDAS = array('gif', 'jpg', 'jpe', 'jpeg', 'png', 'webp', 'avif');


    public function __construct() {
        parent::__construct('formEditDatosPiso', ['enctype' => 'multipart/form-data','urlRedireccion' => Aplicacion::getInstance()->resuelve('/mis_pisos.php')]);
    }

    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['calle', 'barrio', 'ciudad', 'num_banios', 'descripcion', 'archivos'], $this->errores, 'span', array('class' => 'error text-danger'));
        
        $app = Aplicacion::getInstance();
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);

        $formUpload = new FormularioUpload($id_piso);
        $htmlFormUpload = $formUpload->gestiona();
        
        $piso = Piso::buscaPorId($id_piso);

        $calle = $piso->calle??"";
        $barrio = $piso->barrio??"";
        $ciudad = $piso->ciudad??"";
        $num_banios = $piso->num_banios??0;
        $descripcion = $piso->descripcion??"";

        $servicios = $piso->servicios??Array();
        $listaServicios = Busqueda::getServicios();


        $html_checkboxes = '<div class="flex-container-servicios wrap">';
        foreach($listaServicios as $id => $s){

            $check = in_array($s, $servicios)?"checked":""; 

            $html_checkboxes .= <<<EOS
                <input type="checkbox" id="$s" value="$s" name="lista_servicios[]" class="visually-hidden checkbox-servicios" $check>
                <label class="label-servicios mx-1e" for="$s">$s</label>
            EOS;
        }
        $html_checkboxes .= '</div>';


        if($piso->permiteMascota()){
            $si = "checked";
            $no = "";
        }
        else{
            $si = "";
            $no = "checked";
        }
        $html_permite_mascotas = <<<EOS

            <div class="flex-container-servicios wrap">
                <input type="radio" id="permite_mascota" value="true" name="permite_mascota" class="visually-hidden radiobtn-mascota" $si>
                <label class="label-mascota mx-1e" for="permite_mascota">🦝Permite</label>
                
                <input type="radio" id="no_permite_mascota" value="false" name="permite_mascota" class="visually-hidden radiobtn-mascota" $no>
                <label class="label-mascota mx-1e" for="no_permite_mascota">No permite</label>
            </div>

        EOS;

    
        $html = <<<EOF
            $htmlErroresGlobales

            <div class="formulario registro">

                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <label class="mt-2">Calle<span class="text-danger">*</span></label>
                        <input type="text" value="$calle" name="calle" placeholder="Calle" required>
                        {$erroresCampos['calle']}
                    </div>
                    <div class="flex-2col-item block">
                        <label class="mt-2">Barrio</label>
                        <input type="text" value="$barrio" name="barrio" placeholder="Barrio">
                        {$erroresCampos['barrio']}
                    </div>
                </div>
            
                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <label class="mt-2">Ciudad<span class="text-danger">*</span></label>
                        <input type="text" value="$ciudad" name="ciudad" placeholder="Ciudad" required>
                        {$erroresCampos['ciudad']}
                    </div>
                    <div class="flex-2col-item block num">
                        <label class="mt-2">Número de baños<span class="text-danger">*</span></label>
                        <input class="num" type="number" value="$num_banios" name="num_banios" min="0" max="100" placeholder="Baños" required>
                        {$erroresCampos['num_banios']}
                        
                    </div>
                </div>
                    
                <h2 class='mt-2'>¿Permite mascota?</h2>
                <div class="flex">
                    $html_permite_mascotas
                </div>
                

                <h2 class='mt-2'>Servicios que ofrece</h2>
                <div class="flex mr-auto">
                    $html_checkboxes
                </div>
                <div class="break"></div>

                <h2 class='mt-2'>Añade imágenes del piso</h2>
              
                <div>
                    <input type="file" name="archivos[]" onchange="loadFile(event)" multiple/>
                    {$erroresCampos['archivos']}
                </div>
                <div id="output" class="flex-wrapper"></div>


                <script>
                    var loadFile = function(event) {
                        var output = document.getElementById('output');

                        Array.prototype.forEach.call(event.target.files, function(valor, indice, array) {
                            var aux = URL.createObjectURL(valor);
                            output.innerHTML += '<div class="tag"><img class="img-preview" src=' + aux + '></div>';
                        });
                    };

                </script>            
                
                <div class="break"></div>
                
                <h2>Una breve descripción sobre el piso:</h2>
                <div class="flex flex-dir-col">
                    <div class="flex-container-servicios">
                        <textarea class="w-100 px-10 max-w-100 min-w-50 h-150" name="descripcion" maxlength="2048" placeholder="Este piso ofrece...">$descripcion</textarea>
                        {$erroresCampos['descripcion']}
                    </div>
                </div>
                <div >
                    <input class="btn-registro" form="formEditDatosPiso" type="submit" value="Guardar">
                </div>

                
            </div>
            
            
            
        EOF;
        // <input id="btn-registro" form="formEditDatosPiso" type="submit" value="Guardar">

        return $html;
    }


    protected function procesaFormulario($datos)
    {
        $this->errores = [];

        $calle = trim($datos['calle'] ?? '');
        $calle = filter_var($calle, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$calle) {
            $this->errores['calle'] = 'La calle contiene caracteres no permitidos.';
        }

        //Campo opcional
        $barrio = trim($datos['barrio'] ?? '');
        if($barrio){
            $barrio = filter_var($barrio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( !$barrio) {
                $this->errores['barrio'] = 'El barrio contiene caracteres no permitidos.';
            }
        }

        $ciudad = trim($datos['ciudad'] ?? '');
        $ciudad = filter_var($ciudad, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !$ciudad ) {
            $this->errores['ciudad'] = 'La ciudad contiene caracteres no permitidos.';
        }

        $num_banios = $datos['num_banios'] ?? 0;
        $num_banios = filter_var($num_banios, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !$num_banios || !is_numeric($num_banios) || $num_banios>100 || $num_banios<0) {
            $this->errores['num_banios'] = 'La cantidad introducida no es válida';
        }

        $servicios = $datos['lista_servicios']??Array();
        $permite_mascota = $datos['permite_mascota']==="true";

        $descripcion = trim($datos['descripcion'] ?? '');
        if($descripcion){
            $descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( !$descripcion) {
                $this->errores['descripcion'] = 'La descripción contiene caracteres no permitidos.';
            }
        }

        
        if (count($this->errores) === 0) {
            $app = Aplicacion::getInstance();
            //Comenta por qué haces get y put 
            $id_piso = $app->getAtributoPeticion("id_piso");
            $app->putAtributoPeticion("id_piso", $id_piso);

            $piso = Piso::buscaPorId($id_piso);
            
            if (!$piso) {
                $this->errores[] = "Piso no ha sido encontrado usando su id";
            } else {
                $detalles = [
                    'mascota'=>$permite_mascota,
                    'servicios'=>$servicios,
                    'fotos'=>null,
                    'descripcion'=>$descripcion,
                    'precio_max'=>0,
                    'precio_min'=>0,
                    'plazas_libres'=>0,
                    'plazas_ocupadas'=>0,
                    'num_banios'=>$num_banios
                ];
                    
                $piso->cambiaDatos($calle, $barrio, $ciudad, $detalles);
                if($piso->guarda()){
                    //print("A-ok");
                }
                else{
                    $this->errores[] = "No se ha podido actualizar los datos";
                }
            }

            $tam = count($_FILES['archivos']['name']);
            if($tam && $_FILES['archivos']['name'][0]==""){$tam=0;}
            for($i=0; $i<$tam; $i++){
                $nombre = $_FILES['archivos']['name'][$i];
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
                $mimeType = $finfo->file($_FILES['archivos']['tmp_name'][$i]);

                $ok = preg_match('/image\/*./', $mimeType);
    
                if (!$ok) {
                    $this->errores['archivos'] = 'El archivo tiene un nombre o tipo no soportado';
                }
    
                if (count($this->errores) > 0) {
                    return;
                }
    
                $tmp_name = $_FILES['archivos']['tmp_name'][$i];
    
                $imagen = Imagen::crea($nombre, $mimeType, '', $id_piso);
                $imagen->guarda();
                $fichero = "{$imagen->id}.{$extension}";
                $imagen->setRuta($fichero);
                $imagen->guarda();
                $ruta = implode(DIRECTORY_SEPARATOR, [RUTA_ALMACEN_PUBLICO, $fichero]);
                if (!move_uploaded_file($tmp_name, $ruta)) {
                    $this->errores['archivos'] = 'Error al mover el archivo';
                }

            }


          
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
 
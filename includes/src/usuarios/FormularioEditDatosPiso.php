<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Piso;
use es\ucm\fdi\aw\Imagen;
use es\ucm\fdi\aw\Busqueda;

class FormularioEditDatosPiso extends Form{
    
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
                <input type="checkbox" id="$id" value="$s" name="lista_servicios[]" class="visually-hidden checkbox-servicios" $check>
                <label class="label-servicios mx-1e" for="$id">$s</label>
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
                    <input type="file" name="archivos[]" accept="image/*" onchange="loadFile(event)" multiple/>
                    {$erroresCampos['archivos']}
                </div>
                <div id="output" class="flex-wrapper"></div>      
                
                <div class="break"></div>
                
                <h2>Una breve descripción sobre el piso:</h2>
                <div class="flex flex-dir-col">
                    <div class="flex-container-servicios">
                        <textarea id="textarea-pisos" class="w-100 px-10 max-w-100 min-w-50 h-150" name="descripcion" maxlength="2048" placeholder="Este piso ofrece...">$descripcion</textarea>
                        <div id="wordcount">0/1024</div>
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
        $app = Aplicacion::getInstance();
        
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);


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
                if(!$piso->guarda()){
                    $this->errores[] = "No se ha podido actualizar los datos";
                }
                else{
                    $datos = ['id_entidad'=>$piso->id,
                            'carpeta'=>"pisos",
                            'tabla'=>"imagenes_pisos",
                            'entidad'=>"id_piso"];

                    $errores = Imagen::insertaImagen($datos);

                    foreach($errores as $err){
                        $this->errores['archivos'] = $err;
                    }
                }
            }
        }
    }
}
 
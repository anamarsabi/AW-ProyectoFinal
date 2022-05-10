<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Busqueda;
use es\ucm\fdi\aw\Piso;
use es\ucm\fdi\aw\Imagen;

class FormularioRegistroPiso extends Form
{   private $admin_host_aux;
 
    public function __construct() {
        parent::__construct('formRegistroPiso', ['enctype' => 'multipart/form-data','urlRedireccion' => Aplicacion::getInstance()->resuelve('/mis_pisos.php')]);
    }
    
    protected function generaCamposFormulario($datosIniciales)
    {
        $app = Aplicacion::getInstance();
        $admin_host= $app->getAtributoPeticion("id_host");
        $app->putAtributoPeticion("id_host", $admin_host);

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['archivos', 'calle', 'barrio', 'ciudad', 'num_banios', 'descripcion'], $this->errores, 'span', array('class' => 'error text-danger'));

        $listaServicios = Busqueda::getServicios();

        $html_checkboxes="";
        
        $cont = 0;
        $html_checkboxes .= '<div class="flex-container-servicios wrap">';
        foreach($listaServicios as $id => $servicio){
            
            $html_checkboxes .= <<<EOS
                <input type="checkbox" id="$servicio" value="$servicio" name="lista_servicios[]" class="visually-hidden checkbox-servicios">
                <label class="label-servicios mx-1e" for="$servicio">$servicio</label>
            EOS;
        }
       
        $html_checkboxes .= '</div>';
        $html_permite_mascotas = <<<EOS

            <div class="flex-container-servicios wrap">
                <input type="radio" id="permite_mascota" value="true" name="permite_mascota" class="visually-hidden radiobtn-mascota">
                <label class="label-mascota float-l" for="permite_mascota">🦝Permite</label>
                
                <input type="radio" id="no_permite_mascota" value="false" name="permite_mascota" class="visually-hidden radiobtn-mascota" checked>
                <label class="label-mascota float-l" for="no_permite_mascota">No permite</label>
            </div>

        EOS;

        $html = <<<EOF
            $htmlErroresGlobales

            <div class="formulario registro">

                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <label class="mt-2">Calle<span class="text-danger">*</span></label>
                        <input type="text" name="calle" placeholder="Calle" required>
                        {$erroresCampos['calle']}
                    </div>
                    <div class="flex-2col-item block">
                        <label class="mt-2">Barrio</label>
                        <input type="text" name="barrio" placeholder="Barrio">
                        {$erroresCampos['barrio']}
                    </div>
                </div>
               
                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <label class="mt-2">Ciudad<span class="text-danger">*</span></label>
                        <input type="text" name="ciudad" placeholder="Ciudad" required>
                        {$erroresCampos['ciudad']}
                    </div>
                        
                    <div class="flex-2col-item block num">
                        <label class="mt-2">Número de baños<span class="text-danger">*</span></label>
                        <input class="num" type="number" name="num_banios" min="0" max="100" placeholder="Baños" required>
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

                <h2>Una breve descripción sobre el piso:</h2>
                <div class="flex flex-dir-col">
                    <div class="flex-container-servicios">
                        <textarea class="w-100 max-w-100 min-w-50 h-150" name="descripcion" maxlength="1024" placeholder="Este piso ofrece..."></textarea>
                        {$erroresCampos['descripcion']}
                    </div>
                </div>
            </div>

            <input class="btn-registro button" form="formRegistroPiso" type="submit" value="Guardar">
            
            
        EOF;
        return $html;
    }
    
    
    protected function procesaFormulario($datos){

        $this->errores = [];

        
        $calle = trim($datos['calle'] ?? '');
        $tam = strlen($calle);
        $calle = filter_var($calle, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$calle || $tam>strlen($calle)) {
            $this->errores['calle'] = 'La calle contiene caracteres no permitidos.';
        }

        //Campo opcional
        $barrio = trim($datos['barrio'] ?? '');
        if($barrio){
            $tam = strlen($barrio);
            $barrio = filter_var($barrio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( !$barrio || $tam>strlen($barrio)) {
                $this->errores['barrio'] = 'El barrio contiene caracteres no permitidos.';
            }
        }

        $ciudad = trim($datos['ciudad'] ?? '');
        $tam = strlen($ciudad);
        $ciudad = filter_var($ciudad, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !$ciudad || $tam>strlen($ciudad)) {
            $this->errores['ciudad'] = 'La ciudad contiene caracteres no permitidos.';
        }

        $num_banios = $datos['num_banios'] ?? 0;
        $num_banios = filter_var($num_banios, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !$num_banios || !is_numeric($num_banios) || $num_banios>100 || $num_banios<0) {
            $this->errores['num_banios'] = 'La cantidad introducida no es válida';
        }

        $servicios = $datos['lista_servicios']??Array();
        $permite_mascota = $datos['permite_mascota']==true;

        $descripcion = trim($datos['descripcion'] ?? '');
        if($descripcion){
            $tam = strlen($descripcion);
            $descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( !$descripcion || $tam>strlen($descripcion)) {
                $this->errores['descripcion'] = 'La descripción contiene caracteres no permitidos.';
            }

        }
        
        
        if (count($this->errores) === 0) {
            $id_host="";  
            $app = Aplicacion::getInstance();
            $admin_host=$app->getAtributoPeticion("id_host");
    
            if($admin_host!=""){
                $id_host= $admin_host;
            }else{
                $id_host = $app->idUsuario();
            }
            
            if(!$id_host){
                $this->errores[] = "No se ha podido encontrar el usuario";
            }
            else{
                $detalles = ['mascota'=>$permite_mascota,
                'servicios'=>$servicios,
                'fotos'=>null,
                'descripcion'=>$descripcion,
                'precio_max'=>0,
                'precio_min'=>0,
                'plazas_libres'=>0,
                'plazas_ocupadas'=>0,
                'num_banios'=>$num_banios];

                $piso = Piso::crea($id_host, $calle, $barrio, $ciudad, $detalles);

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
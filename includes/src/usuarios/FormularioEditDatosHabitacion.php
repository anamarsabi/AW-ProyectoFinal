<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Habitacion;
use es\ucm\fdi\aw\Busqueda;

class FormularioEditDatosHabitacion extends Form{

    public function __construct() {
        parent::__construct('formEditDatosPersonales', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mis_habitaciones.php')]);
    }

    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['cama_cm', 'precio', 'descripcion', 'disponibilidad'], $this->errores, 'span', array('class' => 'error text-danger'));
     
        $app = Aplicacion::getInstance();
        //Para que no se pierda este dato al recargar la página o al hacer submit
        $id_habitacion = $app->getAtributoPeticion("id_habitacion");
        $app->putAtributoPeticion("id_habitacion", $id_habitacion);

        $hab = Habitacion::buscaPorId($id_habitacion);

        if($hab){
            $detalles = $hab->detalles;

            $tam_cama = $detalles['tam_cama'];
            $banio_privado = $detalles['banio_privado'];
            $precio = $detalles['precio'];
            $descripcion = $detalles['descripcion'];
            $gastos_incluidos = $detalles['gastos_incluidos'];
            $fecha_disponibilidad = $detalles['fecha_disponibilidad'];

            $gi_check = $gastos_incluidos?"checked":"";
            $bp_check = $banio_privado?"checked":"";

        
            $gastos_incluidos = <<<EOS
                <input type="checkbox" id="gastos_incluidos" name="gastos_incluidos" class="visually-hidden checkbox-servicios" $gi_check>
                <label class="label-servicios mx-1e" for="gastos_incluidos">Gastos incluidos</label>
            EOS;

            $banio_propio = <<<EOS
                <input type="checkbox" id="banio_propio" name="banio_propio" class="visually-hidden checkbox-servicios" $bp_check>
                <label class="label-servicios mx-1e" for="banio_propio">Baño propio</label>
            EOS;

            $hoy = date("Y-m-d", time());
        
            $html = <<<EOF
                $htmlErroresGlobales

                <div class="formulario registro">

                    <div class="flex-registro">
                        <div class="flex-2col-item block">
                            <label class="mt-2">Tamaño de la cama (cm)<span class="text-danger">*</span></label>
                            <input type="number" value="$tam_cama" name="cama_cm" placeholder="90 cm" required>
                            {$erroresCampos['cama_cm']}
                        </div>
                        <div class="flex-2col-item block">
                            <label class="mt-2">Precio<span class="text-danger">*</span></label>
                            <input type="number" value="$precio" name="precio" placeholder="Precio" required>
                            {$erroresCampos['precio']}
                        </div>
                    </div>
                
                    <div class="flex-registro">
                    
                        <div class="flex-2col-item block num">
                            <label class="mt-2">Disponibilidad<span class="text-danger">*</span></label>
                            <input type="date" value="$fecha_disponibilidad" name="disponibilidad" min="$hoy" max="2031-01-01" />
                            {$erroresCampos['disponibilidad']}
                        </div>
                        <div class="flex-2col-item block">
                            
                        </div>
                            
                    </div>
                        
                    <h2 class='mt-2'>Características</h2>
                    <div class="flex">
                        $gastos_incluidos
                        $banio_propio
                    </div>
                    
                    <div class="break"></div>

                    <h2>Una breve descripción sobre la habitación:</h2>
                    <div class="flex flex-dir-col">
                        <div class="flex-container-servicios">
                            <textarea class="w-100 max-w-100 min-w-50 h-150" name="descripcion" maxlength="1024" placeholder="Esta habitación ofrece...">$descripcion</textarea>
                            {$erroresCampos['descripcion']}
                        </div>
                    </div>
                </div>

                <input id="btn-registro-habitacion" class="button" type="submit" value="Guardar">

            EOF;

        }

        

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

          
        }
    }
}
 
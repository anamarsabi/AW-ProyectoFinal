<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Habitacion;
use es\ucm\fdi\aw\Busqueda;
use es\ucm\fdi\aw\Imagen;

class FormularioEditDatosHabitacion extends Form{

    public function __construct() {
        parent::__construct('formEditDatosPersonales', ['enctype' => 'multipart/form-data','urlRedireccion' => Aplicacion::getInstance()->resuelve('/mis_habitaciones.php')]);
    }

    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['archivos','cama_cm', 'precio', 'descripcion', 'disponibilidad'], $this->errores, 'span', array('class' => 'error text-danger'));
     
        $app = Aplicacion::getInstance();
        //Para que no se pierda este dato al recargar la página o al hacer submit
        $id_habitacion = $app->getAtributoPeticion("id_habitacion");
        $app->putAtributoPeticion("id_habitacion", $id_habitacion);
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);

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
                        <div class="flex-2col-item block"></div>
                            
                    </div>
                        
                    <h2 class='mt-2'>Características</h2>
                    <div class="flex">
                        $gastos_incluidos
                        $banio_propio
                    </div>
                    
                    <div class="break"></div>

                    <h2 class='mt-2'>Añade imágenes de la habitación</h2>
              
                    <div>
                        <input type="file" name="archivos[]" accept="image/*" onchange="loadFile(event)" multiple/>
                        {$erroresCampos['archivos']}
                    </div>
                    <div id="output" class="flex-wrapper"></div>
        

                    <h2>Una breve descripción sobre la habitación:</h2>
                    <div class="flex flex-dir-col">
                        <div class="flex-container-servicios">
                            <textarea class="w-100 max-w-100 min-w-50 h-150" name="descripcion" maxlength="1024" placeholder="Esta habitación ofrece...">$descripcion</textarea>
                            {$erroresCampos['descripcion']}
                        </div>
                    </div>
                </div>
               
                <input class="btn-registro button" type="submit" value="Guardar">
      
            EOF;
        }

        return $html;
    }


    protected function procesaFormulario($datos)
    {
        $this->errores = [];

        $app = Aplicacion::getInstance();
        //Para que no se pierda este dato al recargar la página o al hacer submit
        $id_habitacion = $app->getAtributoPeticion("id_habitacion");
        $app->putAtributoPeticion("id_habitacion", $id_habitacion);
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);

        $cama_cm = trim($datos['cama_cm'] ?? 0);
        $cama_cm = filter_var($cama_cm, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$cama_cm || !is_numeric($cama_cm)) {
            $this->errores['cama_cm'] = 'Contiene caracteres no permitidos.';
        }
        else if(strlen($cama_cm)>=4){
            $this->errores['cama_cm'] = 'Es demasiado grande';
        }

        $precio = trim($datos['precio'] ?? 0);
        $precio = filter_var($precio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$precio || !is_numeric($precio)) {
            $this->errores['precio'] = 'Contiene caracteres no permitidos.';
        }
        else if(strlen($precio)>=5){
            $this->errores['precio'] = 'Es demasiado grande';
        }

        $hoy = date("Y-m-d", time());

        $disponibilidad = strtotime($datos["disponibilidad"]);
        $disponibilidad = date('Y-m-d', $disponibilidad);
        if($disponibilidad<$hoy){
            $this->errores['disponibilidad'] = 'Debes estar disponible de hoy hacia delante.';
        }

        //Campos opcionales
        $descripcion = trim($datos['descripcion'] ?? "");
        if($descripcion){
            $descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( !$descripcion) {
                $this->errores['descripcion'] = 'La descripción contiene caracteres no permitidos.';
            }
        }

        $gastos_incluidos = isset($datos["gastos_incluidos"]);
        $banio_propio = isset($datos["banio_propio"]);


        if (count($this->errores) === 0) {
            
            if(!$id_habitacion){
                $this->errores[] = "No se ha podido encontrar el piso. Vuelve a mis pisos";
            }
            else{
                $hab = Habitacion::buscaPorId($id_habitacion);

                $detalles = [
                    'tam_cama'=>$cama_cm,
                    'banio_privado'=>$banio_propio,
                    'precio'=>$precio,
                    'descripcion'=>$descripcion,
                    'gastos_incluidos'=>$gastos_incluidos,
                    'fecha_disponibilidad'=>$disponibilidad
                ];

                $hab->cambiaDatos($detalles);
                if(!$hab->guarda()){
                    $this->errores[] = "No se ha podido actualizar los datos";
                }
                else{
                    $datos = ['id_entidad'=>$hab->id_habitacion,
                        'carpeta'=>"habitaciones",
                        'tabla'=>"imagenes_habitaciones",
                        'entidad'=>"id_habitacion"];

                    $errores = Imagen::insertaImagen($datos);

                    foreach($errores as $err){
                        $this->errores['archivos'] = $err;
                    }
                }
            }
        }
    }
}
 
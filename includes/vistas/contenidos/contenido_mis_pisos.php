<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\Piso;
use es\ucm\fdi\aw\Imagen;


$app= Aplicacion::getInstance();

if($app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::ADMIN_ROLE)){
    $contenido="<div class='centrado'> Se ha agregado el piso correctamente </div> ";
}else{

    if($app->comprueba_permisos(Usuario::HOST_ROLE)){
        $pisos = es\ucm\fdi\aw\Piso::getPisosPorIdHost($app->idUsuario());

        if($pisos){
            $contenido = "";
            $ruta_color = $app->resuelve('/img/door_color.svg'); 
            $ruta = $app->resuelve('/img/door.svg'); 

            foreach ($pisos as $p) {
                $boton_editar_detalles =  new \es\ucm\fdi\aw\usuarios\FormularioBotonEditDetallesPiso($p->id);
                $boton_eliminar = new \es\ucm\fdi\aw\usuarios\FormularioBotonDeletePiso($p->id);
                $boton_ver_habitaciones = new \es\ucm\fdi\aw\usuarios\FormularioBotonVerHabitaciones($p->id);

                $dict_status_habitacion = Piso::numHabOcupadasyLibresDelPiso($p->id);
                $num_ocupadas = $dict_status_habitacion['ocupadas'];
                $num_libres = $dict_status_habitacion['libres'];

                $iconos_habitaciones = "<div class='rooms'>";
                for($i=0;$i<$num_ocupadas;$i++){
                    $iconos_habitaciones .= '<img src="'.$ruta_color.'" alt="Hab. Ocupada '.$i.'" width="50" height="50">';
                }

                for($i=0;$i<$num_libres;$i++){
                    $iconos_habitaciones .= '<img src="'.$ruta.'" alt="Hab. Libre '.$i.'" width="50" height="50">';
                }

                if($num_libres==0&&$num_ocupadas==0){
                    $iconos_habitaciones .='<p>No se ha registrado ninguna habitación. Añade una </p>';
                }

                $iconos_habitaciones .= "</div>";

                $rango_precios = $p->getPrecio_max()!=0
                    ?"<div class='precio'>{$p->getPrecio_min()} - {$p->getPrecio_max()} €/mes</div>"
                    : "";
                
                $datos = [
                    'id'=>$p->id,
                    'tabla'=>'imagenes_pisos',
                    'entidad'=>'id_piso'
                ];

                $html_img = Imagen::getPortada($datos);

                $contenido.= <<<EOS
                
                    <div class="centrado card">
                        <div class="card-header">
                            {$p->getCalle()}
                        </div>
                        <div class="card-body">
                        
                            <div class="grid-container">
                                {$html_img}
                                
                                $iconos_habitaciones
                                $rango_precios
                                
                                <div class="inline-block btns">
                                    {$boton_editar_detalles->gestiona()}
                                    {$boton_ver_habitaciones->gestiona()}
                                    {$boton_eliminar->gestiona()}

                                </div>
                            </div>

                        </div>
                    </div>
                EOS;

            }
        }
        else{
            $contenido= <<<EOS
                <div class="centrado card">
                    <div class="card-header">
                        No tienes ningún piso todavía
                    </div>
                    <div class="card-body">
                        <div class="grid-container">
                            <h2>Añade tu primer piso!</h2>
                            
                        </div>
                    </div>
                </div>
            EOS;

        }

        
    }
    else{
        $contenido = <<<EOS
            No tienes permisos para acceder a esta página
        EOS;
    }
}


           






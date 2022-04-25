<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\Piso;


$app= Aplicacion::getInstance();

if($app->comprueba_permisos(Usuario::HOST_ROLE)){
    $pisos = es\ucm\fdi\aw\Piso::getPisosPorIdHost($app->idUsuario());

    if($pisos){
        $contenido = "<div class='centrado'>";
        $ruta_color = $app->resuelve('/img/door_color.svg'); 
        $ruta = $app->resuelve('/img/door.svg'); 

        foreach ($pisos as $p) {
            $boton_editar_detalles =  new \es\ucm\fdi\aw\usuarios\FormularioBotonEditDetallesPiso($p->id);
            $boton_eliminar = new \es\ucm\fdi\aw\usuarios\FormularioBotonDeletePiso($p->id);
            $boton_ver_habitaciones = new \es\ucm\fdi\aw\usuarios\FormularioBotonVerHabitaciones($p->id);

            $num_ocupadas = $p->getPlazas_ocupadas();

            $iconos_habitaciones = "<div class='rooms'>";
            
            for($i=0;$i<$num_ocupadas;$i++){
                $iconos_habitaciones .= '<img src="'.$ruta_color.'" alt="Hab. Ocupada '.$i.'" width="50" height="50">';
            }

            $num_libres = $p->getPlazas_libres();
        
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
            
        
            $contenido.= <<<EOS
                <div class="centrado card">
                    <div class="card-header">
                        {$p->getCalle()}
                    </div>
                    <div class="card-body">
                    
                        <div class="grid-container">
                            <img class="h-100 w-10e fpiso" src="img/logo.png" alt="Imagen"></img>

                            $iconos_habitaciones
                            $rango_precios

                            <button class="clear-btn button more ">
                                <img class=h-20" src="img/three-dots-vertical.svg" alt="more options">
                            </button>
                            
                            <div class="inline-block btns">
                                {$boton_editar_detalles->gestiona()}
                                {$boton_eliminar->gestiona()}
                                {$boton_ver_habitaciones->gestiona()}
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

    
   

    // <ul class="inline-block clear-style clear-pm ">
    //                     <li class="inline-block">
    //                         <img class="h-100 w-10e" src="img/logo.png" alt="Imagen"></img>
    //                     </li>
    //                     <li class="inline-block">
    //                         <div class="grid-container">
    //                             $iconos_habitaciones
    //                             <div class="precio">{$p->getPrecio_min()} - {$p->getPrecio_max()} €/mes</div>
    //                             <div class="btns">
    //                                 {$formulario_editar_detalles->gestiona("1")}
    //                                 <button>Editar habitaciones</button>
    //                             </div>
    //                         </div>
                            
    //                     </li>
    //                 </ul>
    $contenido .="<div>";
}
else{
    $contenido = <<<EOS
        No tienes permisos para acceder a esta página
    EOS;
}



           






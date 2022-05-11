<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\Piso;
use es\ucm\fdi\aw\Imagen;


$app= Aplicacion::getInstance();

 $rol_necesario=Usuario::HOST_ROLE;
 if($app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::ADMIN_ROLE)){
    $rol_necesario=Usuario::ADMIN_ROLE;
}

if($app->comprueba_permisos($rol_necesario)){
    $id_piso = $app->getAtributoPeticion("id_piso");
    $app->putAtributoPeticion("id_piso", $id_piso);

    $habitaciones = es\ucm\fdi\aw\Piso::getHabitacionesPorIdPiso($id_piso);

    if($habitaciones){
        $contenido = "";
        $ruta_color = $app->resuelve('/img/door_color.svg'); 
        $ruta = $app->resuelve('/img/key.svg'); 

        foreach ($habitaciones as $h) {
            $boton_editar_detalles =  new \es\ucm\fdi\aw\usuarios\FormularioBotonEditDetallesHabitacion($h->id_habitacion);
            $boton_eliminar = new \es\ucm\fdi\aw\usuarios\FormularioBotonDeleteHabitacion($h->id_habitacion);

            $precio = $h->detalles['precio'];
            
            $datos = [
                'id'=>$h->id_habitacion,
                'tabla'=>'imagenes_habitaciones',
                'entidad'=>'id_habitacion'
            ];

            $html_img = Imagen::getPortada($datos);

            $contenido.= <<<EOS
                <div class="centrado card">
                    <div class="card-header">
                       Habitación #{$h->id_habitacion}
                    </div>
                    <div class="card-body">
                    
                        <div class="grid-container">
                            $html_img
                            <div class='precio'>$precio €/mes</div>
                            <div class="inline-block btns">
                                {$boton_editar_detalles->gestiona()}
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
                    No tienes ninguna habitación todavía
                </div>
                <div class="card-body">
                    <div class="grid-container">
                        <h2>Añade tu primera habitación!</h2>
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



           






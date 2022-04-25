<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\Piso;


$app= Aplicacion::getInstance();

if($app->comprueba_permisos(Usuario::HOST_ROLE)){
    $id_piso = $app->getAtributoPeticion("id_piso");
    $app->putAtributoPeticion("id_piso", $id_piso);

    $habitaciones = es\ucm\fdi\aw\Piso::getHabitacionesPorIdPiso($id_piso);

    if($habitaciones){
        $contenido = "<div class='centrado'>";
        $ruta_color = $app->resuelve('/img/door_color.svg'); 
        $ruta = $app->resuelve('/img/door.svg'); 

        foreach ($habitaciones as $h) {
            // $boton_editar_detalles =  new \es\ucm\fdi\aw\usuarios\FormularioBotonEditDetallesPiso($p->id);
            // $boton_eliminar = new \es\ucm\fdi\aw\usuarios\FormularioBotonDeletePiso($p->id);

            $precio = $h->detalles['precio'];
            $boton_editar_detalles =  new \es\ucm\fdi\aw\usuarios\FormularioBotonEditDetallesHabitacion($h->id_habitacion);

        
            $contenido.= <<<EOS
                <div class="centrado card">
                    <div class="card-header">
                       Nombre o algo asi por aqui
                    </div>
                    <div class="card-body">
                    
                        <div class="grid-container">
                            <img class="h-100 w-10e fpiso" src="img/room.svg" alt="Imagen"></img>
                            <div class='precio'>$precio €/mes</div>
                            <button class="clear-btn button more ">
                                <img class=h-20" src="img/three-dots-vertical.svg" alt="more options">
                            </button>
                            
                            <div class="inline-block btns">
                                {$boton_editar_detalles->gestiona()}
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
    $contenido .="<div>";
}
else{
    $contenido = <<<EOS
        No tienes permisos para acceder a esta página
    EOS;
}



           






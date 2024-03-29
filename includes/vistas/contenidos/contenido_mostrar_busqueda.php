<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Pisos;
use es\ucm\fdi\aw\Busqueda;
use es\ucm\fdi\aw\Imagen;



$app= Aplicacion::getInstance();
$bus = $app->getBusqueda();

$formulario_busqueda = new \es\ucm\fdi\aw\usuarios\FormularioBusqueda();
$form = $formulario_busqueda->gestiona();


$formulario_filtro = new \es\ucm\fdi\aw\usuarios\FormularioFiltro();
$formFil = $formulario_filtro->gestiona();

$contenido =<<<EOF
    <div class="pt-2e">
        $form
    </div>
EOF;
$contenido .= $formFil;

#$contenido = "";

/*
$formulario_busqueda = new FormularioBusqueda();
$form = $formulario_busqueda->gestiona();

$contenidoPrincipal ="";
$contenidoPrincipal .= "<div class = 'input-group rounded'>" . $form . "</div>";

#ob_start();
#include __DIR__."/includes/vistas/comun/barra_busqueda.php";
#include __DIR__."/includes/vistas/comun/filtros.php";

$contenidoPrincipal .= $bus->imprimirBusqueda();
#$app->delBusqueda();

$contenidoPrincipal .= ob_get_clean();
*/
##########################################################

$pisos = $bus->getResultados(); 

if (count($pisos) === 0) 
{
    $contenido .=<<<EOF
        <div class="centrado align-center">
            <p>No hay resultados disponibles</p>
        </div>
    EOF;
}
else
{
    $contenido .= "<div class='centrado'>";
    $ruta_color = $app->resuelve('/img/door_color.svg'); 
    $ruta = $app->resuelve('/img/door.svg'); 

    foreach ($pisos as $p) {
        $formulario_detalles =  new \es\ucm\fdi\aw\usuarios\FormularioDetalles($p);
        #$formulario_detalles->setPisoFormulario($p);
        /*
        $num_ocupadas = $p->getPlazas_ocupadas();

        $iconos_habitaciones = "<div>";
        
        for($i=0;$i<$num_ocupadas;$i++){
            $iconos_habitaciones .= '<img src="'.$ruta_color.'" alt="Hab. Ocupada '.$i.'" width="50" height="50">';
        }

        $num_libres = $p->getPlazas_libres();

        for($i=0;$i<$num_libres;$i++){
            $iconos_habitaciones .= '<img src="'.$ruta.'" alt="Hab. Libre '.$i.'" width="50" height="50">';
        }

        if($num_libres==0&&$num_ocupadas==0){
            $iconos_habitaciones .='<img src="'.$ruta.'" alt="Hab. Libre '.$i.'" width="50" height="50">';
        }

        $iconos_habitaciones .= "</div>";
        */

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
                    <ul class="inline-block clear-style clear-pm">
                        <li>
                            {$html_img}
                        </li>
                        <li>
                            $rango_precios
                            {$formulario_detalles->gestiona()}
                        </li>
                    </ul>
                </div>
            </div>
        EOS;
    }
    $contenido .="</div>";
}


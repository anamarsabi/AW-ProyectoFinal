<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Busqueda;
use es\ucm\fdi\aw\Piso;


class FormularioFiltro extends Form
{
    public function __construct() {
        parent::__construct('formFiltro', [
            'action' =>  Aplicacion::getInstance()->resuelve('/filtros.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/mostrar_busqueda.php')]);
    }
    
    protected function generaCamposFormulario($datos)
    {
        $barrio = $datos['barrio'] ?? '';
        $precio = $datos['precio'] ?? '';
        $plazas = $datos['plazas'] ?? '';

        $id_form = 'formFiltro';
        
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['barrio', 'precio', 'plazas'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <div class="input-group rounded">    
            <table class="izquierda">
                <tr><th>Filtros</th></tr>
                <tr><td>Ubicación
                    <input class="form-control mr-sm-2" name="barrio" type="search" value="$barrio" placeholder="Introduce el barrio" aria-label="Search">
                    {$erroresCampos['barrio']}
                </td></tr>
                <tr><td> Precio
                    <input class="form-control mr-sm-2" name="precio" type="search" value="$precio" placeholder="Precio max" aria-label="Search">
                    {$erroresCampos['precio']}
                </td> </tr>
                <tr><td>Plazas Libres
                    <input type="number" name ="plazas" min="1" max="9" value="$plazas">
                    {$erroresCampos['plazas']}
                </td></tr>
                <tr><td>                
                <input class ="button left-align" type="submit" form="$id_form" value="filtrar">
                </td></tr>
        
            </table>
        </div>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario($datos)
    {

        $app = Aplicacion::getInstance();

        $busqueda=$app->getBusqueda(); 
        $aux = 0;
        $resultado=$busqueda->getResultados();
        foreach($resultado as $piso) {

            if(($datos['precio']!="") && ($piso->getPrecio_min() > $datos['precio'])){
                unset($resultado[$aux]);
            } elseif (($datos['barrio']!="") && ($piso->getBarrio() != $datos['barrio'])){
                unset($resultado[$aux]);
            } elseif (($datos['plazas']!="") {
                $habs = numHabOcupadasyLibresDelPiso($piso->getId());
                if($piso->getPlazas_libres() <= $habs['libres'])){
                    unset($resultado[$aux]);
                }                
            }
            $aux++;
        }
        
        $busqueda->setResultados($resultado);
        $app->setBusqueda($busqueda);

        $res = $app->resuelve('/mostrar_busqueda.php');

        return $res;
      
    }
}
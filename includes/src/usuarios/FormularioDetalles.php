<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Piso;

class FormularioDetalles extends Form
{
    private $piso;
    private $id_piso;

    public function __construct($piso) {
        $this->piso = $piso;
        $this->id_piso = $piso->getId();
        //$this->id_piso = $id_piso;
        parent::__construct('formDetalles'.$this->id_piso, [
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/mostrar_piso.php')]);
    }


    protected function generaCamposFormulario($datos)
    {
        
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['piso'], $this->errores, 'span', array('class' => 'error text-danger'));
        
        $id_form = 'formDetalles'.$this->id_piso;
        
        $html = <<<EOF
        $htmlErroresGlobales
            <input class ="button left-align" type="submit" form="$id_form" value="Ver detalles">
        EOF;
        return $html;
    }
    
    protected function procesaFormulario($datos)
    {
        $app = Aplicacion::getInstance();

        $app->setPiso($this->piso);

        $app->putAtributoPeticion("id_piso", $this->id_piso);

        $res = $app->resuelve('/mostrar_piso.php');

        return $res;
    }
}
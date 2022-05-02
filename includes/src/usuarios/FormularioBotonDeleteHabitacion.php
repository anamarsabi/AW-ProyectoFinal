<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Habitacion;

class FormularioBotonDeleteHabitacion extends Form{

    private $id_hab;

    public function __construct($id_hab) {
        parent::__construct('formDeleteHab_'.$id_hab, ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mis_habitaciones.php')]);
        $this->id_hab = intval($id_hab);
    }

    protected function generaCamposFormulario($datos)
    {
        $id_form = 'formDeleteHab_'.$this->id_hab;
        $formulario = <<<EOS
            <div class="del-btn-bg">
                <button class="clear-btn button" type="submit" form="$id_form" value="Submit">
                    <img class="invert-color h-20" src="img/trashcan.svg" alt="delete" />
                </button>
            </div>
        EOS;
        return $formulario;
    }

    protected function procesaFormulario($datos)
    {
        
        $app = Aplicacion::getInstance();
        $hab = Habitacion::buscaPorId($this->id_hab);
        $hab->borrate();
    }
}
 
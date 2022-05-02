<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;


class FormularioBotonEditDetallesHabitacion extends Form{

    private $id_habitacion;

    public function __construct($id_habitacion) {
        parent::__construct('formEditDetallesHabitacion_'.$id_habitacion, ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/edit_habitacion.php')]);
        $this->id_habitacion = intval($id_habitacion);
    }

    protected function generaCamposFormulario($datos)
    {
        // < class="button left-align h-20" form="id_form" type="image" src="img/edit.svg" alt="edit" >
        $id_form = 'formEditDetallesHabitacion_'.$this->id_habitacion;
        $formulario = <<<EOS
            <div class="edit-btn-bg ">
                <button class="clear-btn button" type="submit" form="$id_form" value="Submit">
                    <img class="invert-color h-20" src="img/edit.svg" alt="edit" />
                </button>
            </div>
        EOS;
        return $formulario;
    }

    protected function procesaFormulario($datos)
    {
        $app = Aplicacion::getInstance();
        $app->putAtributoPeticion("id_habitacion", $this->id_habitacion);
    }
}
 
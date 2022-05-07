<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;


class FormularioBotonEditusuario extends Form{

    private $id_usuario;

    public function __construct($id_usuario) {
        parent::__construct('formEditDatosPersonales_'.$id_usuario, ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/edit_usuario.php')]);
        $this->id_usuario = intval($id_usuario);
    }

    protected function generaCamposFormulario($datos)
    {
        $id_form = 'formEditDatosPersonales_'.$this->id_usuario;
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
        $app->putAtributoPeticion("id_usuario", $this->id_usuario);

    }
}
 
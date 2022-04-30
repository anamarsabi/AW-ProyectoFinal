<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;


class FormularioBotonAddFotos extends Form{

    private $id_piso;

    public function __construct($id_piso) {
        parent::__construct('formAddFotos_'.$id_piso, ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/addFotos_piso.php')]);
        $this->id_piso = intval($id_piso);
    }

    protected function generaCamposFormulario($datos)
    {
        // < class="button left-align h-20" form="id_form" type="image" src="img/edit.svg" alt="edit" >
        $id_form = 'formAddFotos_'.$this->id_piso;
        $formulario = <<<EOS
            <div class="edit-btn-bg ">
                <button class="clear-btn button" type="submit" form="$id_form" value="Submit">Fotos</button>
            </div>
        EOS;
        return $formulario;
    }

    protected function procesaFormulario($datos)
    {

        $app = Aplicacion::getInstance();
        $app->putAtributoPeticion("id_piso", $this->id_piso);

    }
}
 
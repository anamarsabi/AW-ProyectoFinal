<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Piso;

class FormularioBotonDeletePiso extends Form{

    private $id_piso;

    public function __construct($id_piso) {
        parent::__construct('formDeletePiso_'.$id_piso, ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mis_pisos.php')]);
        $this->id_piso = intval($id_piso);
    }

    protected function generaCamposFormulario($datos)
    {
        // < class="button left-align h-20" form="id_form" type="image" src="img/edit.svg" alt="edit" >
        $id_form = 'formDeletePiso_'.$this->id_piso;
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
        $piso = Piso::buscaPorId($this->id_piso);

        $piso->borrate();
    }
}
 
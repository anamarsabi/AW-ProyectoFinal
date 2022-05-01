<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Imagen;

class FormularioBotonDeleteImagen extends Form{

    private $id_imagen;

    public function __construct($id_imagen) {
        parent::__construct('formDeleteImg_'.$id_imagen, ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/edit_piso.php')]);
        $this->id_imagen = intval($id_imagen);
    }

    protected function generaCamposFormulario($datos)
    {
        
        $id_form = 'formDeleteImg_'.$this->id_imagen;
        
        $formulario = <<<EOS
            <div class="del-btn-bg">
                <button class="clear-btn button" name="send" type="submit" value="delete">
                    <img class="invert-color h-20" src="img/trashcan.svg" alt="delete" />
                </button>
            </div>
        EOS;
        return $formulario;
    }

    protected function procesaFormulario($datos)
    {
        if($_POST['send']=='delete'){
            $app = Aplicacion::getInstance();
            $img = Imagen::borraPorId($this->id_imagen);
        }
        
    }
}
 
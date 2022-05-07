<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;


class FormularioBotonEliminarUsuario extends Form{

    private $id_usuario;

    public function __construct($id_usuario) {
        parent::__construct('formEliminarUsuario_'.$id_usuario, ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/eliminar_usuario.php')]);
        $this->id_usuario = intval($id_usuario);
    }

    protected function generaCamposFormulario($datos)
    {
        $formulario = <<<EOS
            <div class="del-btn-bg ">
                <button class="clear-btn button" type="submit"  value="Submit">
                    <img class="invert-color h-20" src="img/eliminar.svg" alt="eliminar" />
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
 
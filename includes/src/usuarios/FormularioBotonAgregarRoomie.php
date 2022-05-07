<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;


class FormularioBotonAgregarRoomie extends Form{

    private $id_usuario;

    public function __construct() {
        parent::__construct('formAgregarRoomie_',['urlRedireccion' => Aplicacion::getInstance()->resuelve('/registro_roomie.php')]);
    }

    protected function generaCamposFormulario($datos)
    {
        $formulario = <<<EOS
            <div class="save-btn-bg">
                <button class="clear-btn button" type="submit" value="Submit">
                    <img src="img/roomie.jpg" alt="agegarRoomie" />
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
 
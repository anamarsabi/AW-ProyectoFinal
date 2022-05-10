<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;


class FormularioBotonRegistroPiso extends Form{
    private $id_host;

    public function __construct() {
        parent::__construct('formRegistroPiso_',['urlRedireccion' => Aplicacion::getInstance()->resuelve('/registro_piso.php')]);
    }

    protected function generaCamposFormulario($datos)
    {
      
        $formulario = <<<EOS
            <div class="card-header">
                Id del usuario Host al que añade el nuevo piso
                <br />
                <input type="number" placeholder="Id Host" name="idHost" required>
            </div>
            <br /> <br />
            <div class="save-btn-bg">
                <button class="clear-btn button" type="submit" value="Submit">
                    <img src="img/top-hat.svg" alt="registroPiso" />
                </button>
            </div>
        EOS;
        return $formulario;
    }

    protected function procesaFormulario($datos)
    {
        $id_host=$datos['idHost'];

        $app = Aplicacion::getInstance();
        $app->putAtributoPeticion("id_host", $datos['idHost']);

    }
}
 
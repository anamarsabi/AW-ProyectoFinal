<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;
use es\ucm\fdi\aw\Imagen;


class FormularioBotonDeleteImagen extends Form{

    private $id_imagen;
    private $tabla;

    public function __construct($data, $url_redireccion) {
        $id_imagen = $data['id_imagen'];
        parent::__construct('formDeleteImg_'.$id_imagen, ['urlRedireccion' => Aplicacion::getInstance()->resuelve($url_redireccion)]);
        $this->id_imagen = intval($id_imagen);
        $this->tabla = $data['tabla'];
    }

    protected function generaCamposFormulario($datos)
    {
        $app= Aplicacion::getInstance();
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);

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
        $app= Aplicacion::getInstance();
        $id_piso = $app->getAtributoPeticion("id_piso");
        $app->putAtributoPeticion("id_piso", $id_piso);

        if($_POST['send']=='delete'){
            $app = Aplicacion::getInstance();
            $data = [
                'id_imagen'=>$this->id_imagen,
                'tabla'=>$this->tabla
            ];
            $img = Imagen::borraPorId($data);
        }
        
    }
}
 
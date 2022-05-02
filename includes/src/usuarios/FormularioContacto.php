<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioContacto extends Form
{
    public function __construct() {
        $app = Aplicacion::getInstance();
        $piso = $app->getPiso();
        $id_host = $piso->getIdHost(); 
        $user_host = Usuario::buscaPorId($id_host);
        
        parent::__construct('formContacto', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mostrar_chat.php')]);
    }
    
    protected function generaCamposFormulario($datos)
    {

        $msg = $datos['msg'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['msg'], $this->errores, 'span', array('class' => 'error text-danger'));
                $html = <<<EOF
            $htmlErroresGlobales
            <div id="chat">
                <div id="titulo">
                    <p>Bienvenido al Chat </p>                    
                </div>
     
                <div id="chatbox"></div>
     
                <form name="message" action="">
                    <input name="usermsg" type="text" size = 50 id="usermsg" value="$msg" placeholder="Escribe el mensaje que quieres enviar"/>
                    <input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
                </form>
            </div>

        EOF;
        return $html; 
    }
    
    
    protected function procesaFormulario($datos)
    {       
        $app = Aplicacion::getInstance();
        $chat->getChat($app->getIdUsuario());

        $this->errores = [];
        $mensaje = $datos['msg'] ?? '';
        if(empty($mensaje))
        {
            $this->errores['msg'] = "El mensaje no puede estar vacío.";
        }


        $piso = $app->getPiso();
        $id_piso = $piso->getId();
        $app->putAtributoPeticion("id_piso", $id_piso);

        $res = $app->resuelve('/mostrar_piso.php');

        return $res;
        
    }
}
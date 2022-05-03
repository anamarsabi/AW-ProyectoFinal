<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioContacto extends Form
{
    public function __construct() {

        parent::__construct('formBusqueda', [
            'action' =>  Aplicacion::getInstance()->resuelve('/barra_busqueda.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/mostrar_chat.php')]);


        $app = Aplicacion::getInstance();
        $piso = $app->getPiso();
        $id_host = $piso->getIdHost(); 
        new \es\ucm\fdi\aw\Chat($app->idUsuario(), $id_host);   
        $app->setChat($chat);     
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
        $chat = $app->getChat();
        
        $this->errores = [];
        $msg = $datos['msg'] ?? '';
        if(empty($msg))
        {
            $this->errores['msg'] = "El mensaje no puede estar vacío.";
        }

        $mensaje = $chat->getMensaje();
        $mensaje = $mensaje ?? $msg;
        
        $app->setContexo('mostrar_chat.php')

        $res = $app->resuelve('/mostrar_chat.php');

        return $res;
        
    }
}
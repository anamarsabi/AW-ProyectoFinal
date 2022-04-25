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
        $mail = $user_host->getCorreo();
        $mailto = "";
        $mailto .= "mailto:";
        $mailto .= $mail;
        parent::__construct('formContacto', ['action' => $mailto, 'urlRedireccion' => Aplicacion::getInstance()->resuelve('/mostrar_piso.php')]);
    }
    
    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['mensaje'], $this->errores, 'span', array('class' => 'error text-danger'));
        $app = Aplicacion::getInstance();
        $piso = $app->getPiso();
        $direccion = $piso->getCalle();
        $ciudad = $piso->getCiudad();

        $id_piso = $piso->getId();
        $app->putAtributoPeticion("id_piso", $id_piso);

        $nombre = $app->nombre();
        $html = <<<EOF
            $htmlErroresGlobales
            <div class="centrado card">
                <div class="card-header">
                    <h2>Contacta al host</h2>
                    <h3>{$direccion}, {$ciudad}</h3>
                </div>
                <div class="card-body">
                    <label for="mensaje">  </label>
                    <textarea class="w-100 px-10 max-w-100 min-w-50 h-150" name="mensaje" id="mensaje" placeholder="Hola! Soy {$nombre}, estaría interesado en alquilar esta propiedad..."></textarea><br>
                    {$erroresCampos['mensaje']}
                    <input class ="button left-align" type="submit" value="Enviar">
                </div>
            </div>
        EOF;
        return $html;
    }
    
    
    protected function procesaFormulario($datos)
    {
        $this->errores = [];
        $mensaje = $datos['mensaje'] ?? '';
        if(empty($mensaje))
        {
            $this->errores['mensaje'] = "El mensaje no puede estar vacío.";
        }
        $app = Aplicacion::getInstance();

        $mensajes = ['Has contactado a host. Espera su espuesta en tu email.'];
        $app->putAtributoPeticion('mensajes', $mensajes);

        $piso = $app->getPiso();
        $id_piso = $piso->getId();
        $app->putAtributoPeticion("id_piso", $id_piso);

        $res = $app->resuelve('/mostrar_piso.php');

        return $res;
        
    }
}
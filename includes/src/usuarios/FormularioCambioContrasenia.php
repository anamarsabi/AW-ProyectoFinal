<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioCambioContrasenia extends Form{
    public function __construct() {
        parent::__construct('formCambioContrasenia', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mi_perfil.php')]);
    }

    protected function generaCamposFormulario($datos)
    {

        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        //     $erroresCampos = self::generaErroresCampos(['contrasenia_actual', 'nueva_contrasenia', 'repetir_contrasenia'], $this->errores, 'span', array('class' => 'error text-danger'));
        // }
        // else{
        //     $erroresCampos = self::generaErroresCampos(['contrasenia_actual', 'nueva_contrasenia', 'repetir_contrasenia'], array(), 'span', array('class' => 'error text-danger'));
        // }

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['contrasenia_actual', 'nueva_contrasenia', 'repetir_contrasenia'], $this->errores, 'span', array('class' => 'error text-danger'));
        
        
        $app = Aplicacion::getInstance();
        $usuario = Usuario::buscaUsuario($app->correo());

        $nombre = $usuario->getCorreo();

        $formulario = <<<EOS

            <div class="formulario">
                <label class="mt-2">Contraseña actual<span class="text-danger">*</span></label>
                <div class="float-r">{$erroresCampos['contrasenia_actual']}</div>
                <input class="w-100 px-10-20 mx-8-0 inline default-input" type="password" name="contrasenia_actual" placeholder="Contraseña actual" required>
                

                <label class="mt-2">Nueva contraseña<span class="text-danger">*</span></label>
                <div class="float-r">{$erroresCampos['nueva_contrasenia']}</div>
                <input class="w-100 px-10-20 mx-8-0 inline default-input" type="password" name="nueva_contrasenia" placeholder="Nueva contraseña" required>
                

                <label class="mt-2">Repetir contraseña<span class="text-danger">*</span></label>
                <div class="float-r">{$erroresCampos['repetir_contrasenia']}</div>
                <input class="w-100 px-10-20 mx-8-0 inline default-input" type="password" name="repetir_contrasenia" placeholder="Repetir contraseña" required>
                
            </div>

            <input class="button left-align" type="submit" value="Cambiar">
        EOS;

        return $formulario;
    }


    protected function procesaFormulario($datos)
    {
        $this->errores = [];
        $app = Aplicacion::getInstance();
        $usuario = Usuario::buscaPorId($app->idUsuario());
        if (!$usuario) {
            $this->errores[] = "No se ha encontrado el usuario en la BD";

        } else {

            $contrasenia_actual = $datos['contrasenia_actual'];
            $coincideActual = $usuario->compruebaPassword($contrasenia_actual);
            
            if (!$contrasenia_actual || empty($contrasenia_actual) ) {
                $this->errores['contrasenia_actual'] = "**Este campo no puede estar vacío.";
            }
            else if(!$coincideActual){
                $this->errores['contrasenia_actual'] = "**No coincide con la contraseña actual";
            }

            $nueva_contrasenia = $datos['nueva_contrasenia'];
            if (!$nueva_contrasenia || empty($nueva_contrasenia) ) {
                $this->errores['nueva_contrasenia'] = "**Este campo no puede estar vacío.";
            }
            $repetir_contrasenia = $datos['repetir_contrasenia'];
            if (!$repetir_contrasenia || empty($repetir_contrasenia) ) {
                $this->errores['repetir_contrasenia'] = "**Este campo no puede estar vacío.";
            }
            if($nueva_contrasenia!==$repetir_contrasenia){
                $this->errores['repetir_contrasenia'] = "**Las contraseñas nuevas no coinciden";
            }

            if (count($this->errores) === 0) {
                $usuario->cambiaPassword($nueva_contrasenia);
                Usuario::actualiza_pwd($usuario);
                $app->login($usuario);

                // $app->putAtributoPeticion("pag_detalles_perfil", "id_2");
                
            }



        }
    }
}
 
<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioEditDatosPersonales extends Form{
    public function __construct() {
        parent::__construct('formEditDatosPersonales', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mi_perfil.php')]);
    }

    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'apellido1', 'apellido2'], $this->errores, 'span', array('class' => 'error text-danger'));
        
        $app = Aplicacion::getInstance();
        $usuario = Usuario::buscaUsuario($app->correo());

        $nombre = $usuario->getNombre()??"";
        $apellido1 = $usuario->getApellido1();
        $apellido2 = $usuario->getApellido2();

        // https://stackoverflow.com/questions/1990321/date-minus-1-year
        //Edad mínima = 16 años
        $time = strtotime("-16 year", time());
        $hace_16_anios = date("Y-m-d", $time);

        $time = strtotime("-80 year", time());
        $hace_80_anios = date("Y-m-d", $time);

        $formulario = <<<EOS
            <div class="formulario">
                <label class="mt-2">Nombre<span class="text-danger">*</span></label>
                <input class="w-100 px-10-20 mx-8-0 inline default-input" value="$nombre" type="text" name="nombre" placeholder="Nombre" required>
                {$erroresCampos['nombre']}

                <label class="mt-2">Primer apellido<span class="text-danger">*</span></label>
                <input class="w-100 px-10-20 mx-8-0 inline default-input" value="$apellido1" type="text" name="apellido1" placeholder="Primer apellido" required>
                {$erroresCampos['apellido1']}

                <label class="mt-2">Segundo apellido</label>
                <input class="w-100 px-10-20 mx-8-0 inline default-input" value="$apellido2" type="text" name="apellido2" placeholder="Segundo apellido">
                {$erroresCampos['apellido2']}

                <label class="mt-2">Fecha de nacimiento<span class="text-danger">*</span></label>
                <input class="w-100 px-10-20 mx-8-0 inline default-input" type="date" value={$usuario->getBirthday()} name="birthday" min=$hace_80_anios max=$hace_16_anios required />
            </div>

            <input class="button left-align" type="submit" value="Guardar">
        EOS;

        return $formulario;
    }


    protected function procesaFormulario($datos)
    {
        $this->errores = [];

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre) {
            $this->errores['nombre'] = 'El nombre de usuario contiene caracteres no permitidos.';
        }

        $apellido1 = trim($datos['apellido1'] ?? '');
        $apellido1 = filter_var($apellido1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $apellido1) {
            $this->errores['apellido1'] = 'El primer apellido contiene caracteres no permitidos.';
        }

        $apellido2 = trim($datos['apellido2']);
        if($apellido2){
            $apellido2 = filter_var($apellido2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! $apellido2) {
                $this->errores['apellido2'] = 'El segundo apellido contiene caracteres no permitidos.';
            }

        }
       

        $birthday = strtotime($datos["birthday"]);
        $birthday = date('Y-m-d', $birthday);

        $app = Aplicacion::getInstance();

        if (count($this->errores) === 0) {
            $datos = ['nombre'=>$nombre,'apellido1'=>$apellido1,'apellido2'=>$apellido2,'birthday'=>$birthday];

            $usuario = Usuario::buscaPorId($app->idUsuario());
            

            if (!$usuario) {
                $this->errores[] = "Usuario no ha sido encontrado usando su id";
            } else {
                $usuario->cambiaDatosPersonales($datos);
                if(Usuario::actualiza_dp($usuario)){
                    $app->login($usuario);
                }
                else{
                    $this->errores[] = "No se ha podido actualizar los datos";
                }
            }
        }
        else{
            //print("Hay errores");
        }
    }
}
 
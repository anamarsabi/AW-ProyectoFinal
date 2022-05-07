<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioEditDatosUsuario extends Form{

    public function __construct() { 
        parent::__construct('formEditDatosPersonales', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mi_perfil.php?pag=id_1')]);
    }

    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre','correo','apellido1','apellido2','contrasenia_actual','nueva_contrasenia','repetir_contrasenia'], $this->errores, 'span', array('class' => 'error text-danger'));
        
        $app = Aplicacion::getInstance();
        $id_usuario = $app->getAtributoPeticion("id_usuario");
        $app->putAtributoPeticion("id_usuario", $id_usuario);

        $usuario = Usuario::buscaPorId($id_usuario);

        $nombre = $usuario->getNombre()??"";
        $correo=  $usuario->getCorreo()??"";
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

            <div class="col-5 centrado index-banner-block">
                <label class="mt-2">Nombre<span class="text-danger">*</span></label>
                <input class="w-100 index-input inline default-input" value="$nombre" type="text" name="nombre" placeholder="Nombre" required>
                {$erroresCampos['nombre']}
                </div>

        
            <div class="col-5 centrado index-banner-block">
                <label class="mt-2">Primer apellido<span class="text-danger">*</span></label>
                <input class="w-100 index-input inline default-input" value="$apellido1" type="text" name="apellido1" placeholder="Primer apellido" required>
                {$erroresCampos['apellido1']}
            </div>

            <div class="col-5 centrado index-banner-block">
                <label class="mt-2">Segundo apellido</label>
                <input class="w-100 index-input inline default-input" value="$apellido2" type="text" name="apellido2" placeholder="Segundo apellido">
                {$erroresCampos['apellido2']}
            </div>

        <div class="col-5 centrado index-banner-block">
            <label class="mt-2">Fecha de nacimiento<span class="text-danger">*</span></label>
            <input class="w-100 index-input inline default-input" type="date" value={$usuario->getBirthday()} name="birthday" min=$hace_80_anios max=$hace_16_anios required />
        </div>
                <div class="col-5 centrado index-banner-block">
                <label class="mt-2">Correo<span class="text-danger">*</span></label>
                <input class="w-100 index-input inline default-input" value="$correo" type="text" name="correo" placeholder="correo" required>
                {$erroresCampos['correo']}
                </div>

                <div class="col-11 centrado index-banner-block">
                <label class="mt-2">Contraseña actual<span class="text-danger">*</span></label>
                <div class="float-r">{$erroresCampos['contrasenia_actual']}</div>
                <input class="w-100 index-input inline default-input" type="password" name="contrasenia_actual" placeholder="Contraseña actual" required>
                 </div>

                <div class="col-11 centrado index-banner-block">
                    <label class="mt-2">Nueva contraseña<span class="text-danger">*</span></label>
                    <div class="float-r">{$erroresCampos['nueva_contrasenia']}</div>
                    <input class="w-100 index-input inline default-input" type="password" name="nueva_contrasenia" placeholder="Nueva contraseña" required>
                </div>

                <div class="col-11 centrado index-banner-block">
                    <label class="mt-2">Repetir contraseña<span class="text-danger">*</span></label>
                    <div class="float-r">{$erroresCampos['repetir_contrasenia']}</div>
                    <input class="w-100 index-input inline default-input" type="password" name="repetir_contrasenia" placeholder="Repetir contraseña" required>
                    
                </div>
            </div>


            <input class="btn-registro" type="submit" value="Cambiar">
            <div class="clear"></div>
        EOS;

        return $formulario;
    }


    protected function procesaFormulario($datos)
    {
        $this->errores = [];
        $app = Aplicacion::getInstance();
        $id_usuario = $app->getAtributoPeticion("id_usuario");
        $app->putAtributoPeticion("id_usuario", $id_usuario);

        $usuario = Usuario::buscaPorId($id_usuario);

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
        $correo = trim($datos['correo'] ?? '');

        if ( ! $nombre) {
            $this->errores['nombre'] = 'El nombre de usuario contiene caracteres no permitidos.';
        }

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
            $datos = ['nombre'=>$nombre,'correo'=>$correo,'apellido1'=>$apellido1,'apellido2'=>$apellido2,'birthday'=>$birthday];
            
            if (!$usuario) {
                $this->errores[] = "Usuario no ha sido encontrado usando su id";
            } else {
                $usuario->cambiaDatosPersonales($datos);
                if(Usuario::actualiza_dp($usuario)){
                    $usuario->cambiaPassword($nueva_contrasenia);
                    Usuario::actualiza_pwd($usuario);
                }
                else{
                    $this->errores[] = "No se ha podido actualizar los datos";
                }
            }
            
        }     
    }
}
 
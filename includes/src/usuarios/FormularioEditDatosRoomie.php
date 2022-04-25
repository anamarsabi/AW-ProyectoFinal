<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioEditDatosRoomie extends Form{
    private $rol;
    public function __construct() {
        parent::__construct('formEditDatosRoomies', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/mi_perfil.php')]);
    }

    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['aficiones', 'descripcion', 'mascota'], $this->errores, 'span', array('class' => 'error text-danger'));
        
        $app = Aplicacion::getInstance();
        $usuario = Usuario::buscaUsuario($app->correo());
            $nombre = $usuario->getNombre()??"";
            $apellido1 = $usuario->getApellido1();

            $formulario = <<<EOS
                <div class="formulario">
                    <label class="mt-2">Nombre</label>
                    <input class="w-100 px-10-20 mx-8-0 inline default-input" value="$nombre" type="text" name="nombre" placeholder="Nombre" readonly>

                    <label class="mt-2">Primer apellido</label>
                    <input class="w-100 px-10-20 mx-8-0 inline default-input" value="$apellido1" type="text" name="apellido1" placeholder="Primer apellido" readonly>
                    <div></div>
                    <label class="mt-2">Elige una aficion:</label><br>
                        <input name="aficiones[]" type=checkbox value="Leer" />Leer
                        <input name="aficiones[]" type=checkbox value="Musica" />Musica
                        <input name="aficiones[]" type=checkbox value="Deportes" />Deportes
                        <input name="aficiones[]" type=checkbox value="Aire Libre" />Aire Libre
                        <input name="aficiones[]" type=checkbox value="Naturaleza" />Naturaleza<br>
                        <input name="aficiones[]" type=checkbox value="Historia" />Historia
                        <input name="aficiones[]"  type=checkbox value="Fiesta" />Fiesta
                        <input name="aficiones[]" type=checkbox value="Animales" />Animales
                    </div>
                    <br>
                    <div>
                    <label class="mt-2"> Una breve descripción sobre ti </label>
                    <textarea name="descripcion" rows="4" cols="50" placeholder="Soy una persona..."></textarea><br>
                    </div>
                    <br>
                    <div>
                    <label>¿Tienes mascotas?</label>
                    <input name="mascota" type=radio value="1" />Sí
                    <input name="mascota" type=radio value="0" />NO
                    </div>

                <input class="button left-align" type="submit" value="Guardar">
            EOS;
            return $formulario;
    }

    protected function procesaFormulario($datos) {
            $this->errores = [];
            if(!empty($datos['aficiones'])){
            $aficiones = $datos['aficiones'];
            }else{
                $aficiones =null;  
            }
            if(!empty($datos['descripcion'])){
                $descripcion = $datos['descripcion'];
            }else{
                $descripcion =null;
            }
            $mascota = $datos['mascota'];
            $app = Aplicacion::getInstance();

            if (count($this->errores) === 0) {
                $datos = ['descripcion'=>$descripcion,'mascota'=>$mascota,'aficiones'=>$aficiones];

                $usuarioRoomie = UsuarioRoomie::buscaRoomiePorId($app->idUsuario(),$aficiones);
            
                if (!$usuarioRoomie) {
                    $this->errores[] = "Usuario no ha sido encontrado usando su correo";
                } else {
                    $usuarioRoomie->cambiaDatosPersonales($datos);
                    if(UsuarioRoomie::actualiza_dp($usuarioRoomie)){
                        $usuario = Usuario::buscaPorId($app->idUsuario());
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
 
<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioRegistroHost extends Form
{
    public function __construct() {
        parent::__construct('form_registro_usuario', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario($datosIniciales)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'apellido1', 'apellido2', 'pwd', 'cpwd'], $this->errores, 'span', array('class' => 'error text-danger'));

        $time = strtotime("-16 year", time());
        $hace_16_anios = date("Y-m-d", $time);

        $time = strtotime("-80 year", time());
        $hace_80_anios = date("Y-m-d", $time);

        $steps = "";
        $nPag = 2;
        for($i=0; $i<$nPag; $i++){
            $steps .= "<span class='step'></span>";
        }

        $html = <<<EOF
            $htmlErroresGlobales

            <div class="formulario tab registro">

                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Nombre" name="nombre" required>
                        {$erroresCampos['nombre']}
                    </div>
                    <div class="flex-2col-item block"></div>
                </div>
            
                <div class="flex-registro">
                    <div class="flex-2col-item block">
                    <label>Primer apellido <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Primer apellido" name="apellido1" required>
                        {$erroresCampos['apellido1']}
                    </div>
                    <div class="flex-2col-item block num">
                        <label>Segundo apellido </label>
                        <input type="text" placeholder="Segundo apellido" name="apellido2">
                        {$erroresCampos['apellido2']}
                    </div>
                </div>

                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <h4>Fecha de nacimiento <span class="text-danger">*</span></h4>
                        <input type="date" name="birthday" min="$hace_80_anios" max="$hace_16_anios" required />
                    </div>
                    <div class="flex-2col-item block"></div>
                </div>
            </div>

            <div class="formulario tab registro">
                <h2>Datos de acceso</h2>
                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <label>Correo electrónico<span class="text-danger">*</span></label>
                        <input id="correo" type="email" name="email" placeholder="Email" /> 
                        <div id="email_err_msg"></div>
                    </div>
                    <div class="flex-2col-item block"></div>
                </div>

                <div class="flex-registro">
                    <div class="flex-2col-item block">
                        <label>Contraseña<span class="text-danger">*</span></label>
                        <input type="password" name="pwd" placeholder="Contraseña" /> 
                    </div>
                    <div class="flex-2col-item block">
                        <label>Confirmar contraseña<span class="text-danger">*</span></label>
                        <input type="password" name="cpwd" placeholder="Confirmar contraseña" />
                        {$erroresCampos['cpwd']}
                    </div>
                </div>
               
                <p class="err_msg text-danger">
                    **Contraseñas no coinciden**
                </p>
            </div>

            <div class='align-center mt-40'>
                $steps
            </div>

            
            <input  class="btn-registro"  type="button" id="nextBtn" onclick="nextPrev(1)" value="Siguiente">
            <input class="btn-registro" type="button" id="prevBtn" onclick="nextPrev(-1)" value="Anterior">
           
       
            <script>
                var currentTab = 0; // Current tab is set to be the first tab (0)
                showTab(currentTab); // Display the current tab
                document.getElementsByClassName("err_msg")[0].style.display = "";
            </script>
            
        EOF;
        return $html;
    }
    
    
    protected function procesaFormulario($datos){

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

        $apellido2 = trim($datos['apellido2'] ?? '');
        if($apellido2){
            $apellido2 = filter_var($apellido2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( !$apellido2) {
                $this->errores['apellido2'] = 'El segundo apellido contiene caracteres no permitidos.';
            }
        }

        $time = strtotime("-16 year", time());
        $hace_16_anios = date("Y-m-d", $time);

        $time = strtotime("-80 year", time());
        $hace_80_anios = date("Y-m-d", $time);

        $birthday = strtotime($datos["birthday"]);
        $birthday = date('Y-m-d', $birthday);
        if($birthday>$hace_16_anios || $birthday < $hace_80_anios){
            $this->errores['birthday'] = 'Debes tener más de 16 años y menos de 80 años.';
        }

        $email = trim($datos['email']);
        $password = $datos['pwd'];

        if (count($this->errores) === 0) {
            $usuario = Usuario::buscaUsuario($email);
	
            if ($usuario) {
                $this->errores[] = "El usuario ya existe";
            } else {
                $usuario = Usuario::crea($email, $nombre, $apellido1, $apellido2, $birthday, $password, Usuario::HOST_ROLE);
                $app = Aplicacion::getInstance();
                $app->login($usuario);
                $res = $app->resuelve('/index.php');

                return $res;
                
            }
        }
        
    }
}
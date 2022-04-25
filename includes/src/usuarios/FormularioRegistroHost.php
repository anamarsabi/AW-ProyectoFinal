<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioRegistroHost extends Form
{
    public function __construct() {
        parent::__construct('form_registro_host', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario($datosIniciales)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'password', 'password2'], $this->errores, 'span', array('class' => 'error text-danger'));

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
            <div class="tab marg st-input">
                <h2>Datos personales</h2>
                <div>
                    <label>Nombre <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Nombre" name="nombre" required>
                    {$erroresCampos['nombre']}
                </div>
                <span>
                    <label>Primer apellido <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Primer apellido" name="apellido1" required>
                </span>
                <span>
                    <label>Segundo apellido </label>
                    <input type="text" placeholder="Segundo apellido" name="apellido2">
                </span>
                <h4>Fecha de nacimiento <span class="text-danger">*</span></h4>
                <input type="date" name="birthday" min="$hace_80_anios" max="$hace_16_anios" required />
            </div>
            <div class="tab marg st-input">
                <h2>Datos de acceso</h2>
                <label>Correo electrónico<span class="text-danger">*</span></label>
                <input type="email" name="email" placeholder="Email" /> 

                <label>Contraseña<span class="text-danger">*</span></label>
                <input type="password" name="pwd" placeholder="Contraseña" /> 

                <label>Confirmar contraseña<span class="text-danger">*</span></label>
                <input type="password" name="cpwd" placeholder="Confirmar contraseña" />
                <p class="err_msg text-danger" style="display:none">
                    **Contraseñas no coinciden**
                </p>
            </div>

            <div class="overflow-auto">
                <div class="float-r">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Anterior</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Siguiente</button>
                </div>
            </div>

            <div class='align-center mt-40'>
                $steps
            </div>
       
            <script>
            var currentTab = 0; // Current tab is set to be the first tab (0)
            showTab(currentTab); // Display the current tab

            function showTab(n) {
                // This function will display the specified tab of the form...
                var x = document.getElementsByClassName("tab");
                x[n].style.display = "block";
                //... and fix the Previous/Next buttons:
                if (n == 0) {
                    document.getElementById("prevBtn").style.display = "none";
                } else {
                    document.getElementById("prevBtn").style.display = "inline";
                }
                if (n == (x.length - 1)) {
                    document.getElementById("nextBtn").innerHTML = "Finalizar";
                } else {
                    document.getElementById("nextBtn").innerHTML = "Siguiente";
                }
                //... and run a function that will display the correct step indicator:
                fixStepIndicator(n)
            }

            function nextPrev(n) {
                // This function will figure out which tab to display
                var x = document.getElementsByClassName("tab");
                // Exit the function if any field in the current tab is invalid:
                if (n == 1 && !validateForm()) return false;
                // Hide the current tab:
                x[currentTab].style.display = "none";
                // Increase or decrease the current tab by 1:
                currentTab = currentTab + n;
                // if you have reached the end of the form...
                if (currentTab >= x.length) {
                    // ... the form gets submitted:
                    var form_id = "form_registro_host";
                    document.getElementById(form_id).submit();
                    return false;
                }
                // Otherwise, display the correct tab:
                showTab(currentTab);
            }

            function validateForm() {
                // This function deals with validation of the form fields
                var x, y, i, valid = true;
                x = document.getElementsByClassName("tab");
                y = x[currentTab].getElementsByTagName("input");
                // A loop that checks every input field in the current tab:
                for (i = 0; i < y.length; i++) {
                    // If a field is empty...
                    if (y[i].value == "" && y[i].required) {
                        // add an "invalid" class to the field:
                        y[i].className += " invalid";
                        // and set the current valid status to false
                        valid = false;
                    }
                }
                msg = document.getElementsByClassName("err_msg")[0].style.display = "none";

                if(currentTab==1){
                    if(y["pwd"].value!=y["cpwd"].value){
                        valid=false;
                        y["pwd"].className += " invalid";
                        y["cpwd"].className += " invalid";

                        msg = document.getElementsByClassName("err_msg")[0].style.display = "";
                    }
                }
                
                // If the valid status is true, mark the step as finished and valid:
                if (valid) {
                    document.getElementsByClassName("step")[currentTab].className += " finish";
                }

                return valid; // return the valid status
            }

            function fixStepIndicator(n) {
                // This function removes the "active" class of all steps...
                var i, x = document.getElementsByClassName("step");
                
                for (i = 0; i < x.length; i++) {
                    x[i].className = x[i].className.replace(" active", "");
                }
                //... and adds the "active" class on the current step:
                x[n].className += " active";
            }
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
<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioLogin extends Form{
    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }

    protected function  generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['email', 'password'], $this->errores, 'span', array('class' => 'error text-danger'));
        
        $formulario = <<<EOS
        <div class="formulario align-center pt-5e">
            <img class="centrado" src="img/logo.png" height="170" alt="logo_roomie"/>
            <div class="main-login centrado">
                <div>
                    <input class="w-100 px-10-20 mx-8-0 inline default-input" id="correo" type="text" name="email" placeholder="Correo electrónico" required>
                    {$erroresCampos['email']}
                    <div id="email_err_msg"></div>
                </div>
                <div>
                    <input class="w-100 px-10-20 mx-8-0 inline default-input" type="password" placeholder="Contraseña" name="password" required>
                    {$erroresCampos['password']}
                </div>
                <input class="w-100" type="submit" value="Login" style="color:white;">
            
                ¿No tienes una cuenta?
                <p>
                    <a href="registro_roomie.php">Regístrate como roomie</a>
                </p>
                <p>
                    <a href="registro_host.php">Regístrate como propietario</a>
                </p>
            </div>
        </div>
 
        EOS;

        return $formulario;
    }


    protected function procesaFormulario($datos)
    {
        $this->errores = [];
        $email = trim($datos['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if ( ! $email || empty($email) ) {
            $this->errores['email'] = 'El campo correo no puede estar vacío';
        }
    
        $password = $datos['password'] ?? null;
        if ( empty($password) ) {
            $this->errores['password'] = "El password no puede estar vacío.";
        }

        if (count($this->errores) === 0) {
            $usuario = Usuario::login($email, $password);
        
            if (!$usuario) {
                $this->errores['password'] = "El usuario o el password no coinciden";
                //print "El usuario o el password no coinciden";
            } else {
                $app = Aplicacion::getInstance();
                $app->login($usuario);
            }
        }
    }
}
 
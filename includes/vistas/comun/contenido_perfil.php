<?php
    require_once __DIR__.'/../../config.php';


    $formulario_dp = new es\ucm\fdi\aw\usuarios\FormularioEditDatosPersonales();
    $formulario_cc = new es\ucm\fdi\aw\usuarios\FormularioCambioContrasenia();
    $formulario_dr = new es\ucm\fdi\aw\usuarios\FormularioEditDatosRoomie();

    $html_form_dp = $formulario_dp->gestiona();
    $html_form_cc = $formulario_cc->gestiona();

    $correo= es\ucm\fdi\aw\Aplicacion::getInstance()->correo();
    $id= es\ucm\fdi\aw\Aplicacion::getInstance()->idUsuario();
    $rol= es\ucm\fdi\aw\usuarios\Usuario::obtieneRol($id);

    if($rol==1){
        $html_form_dr = $formulario_dr->gestiona();
    }else{
        $html_form_dr = null;
    }

    $contenido = <<<EOS
        
        <div class="centrado pagina id_1">

            <div class="centrado card">
                <div class="card-header">
                    Datos personales
                </div>
                <div class="card-body">
                    $html_form_dp
                   
                </div>
            </div>
        </div>

        <div class="centrado pagina id_2">

            <div class="centrado card">
                <div class="card-header">
                    Correo electrónico
                </div>
                <div class="card-body">
                    <div class="formulario">
                        <label class="mt-2">Correo electrónico</label>
                        <input class="w-100 px-10-20 mx-8-0 inline default-input" value="$correo" type="text" name="email" placeholder="Nombre" disabled>
                    </div>
                </div>
            </div>
                
            <div class="centrado card">
                <div class="card-header">
                    Cambiar contraseña
                </div>
                <div class="card-body">
                    $html_form_cc
                </div>
            </div>
        </div>
        
        <div class="centrado pagina id_3">

            <div class="centrado card">
                <div class="card-header">
                    Datos para usuario Roomie
                </div>
                <div class="card-body">
                    $html_form_dr
                   
                </div>
            </div>
        </div>

        
    EOS;




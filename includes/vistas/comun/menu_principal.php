<?php
// Boostrap like dropdown --> https://codepen.io/abhndv/pen/ExYBYZa
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;

$app = Aplicacion::getInstance();

function opciones_menu() {
    $app = Aplicacion::getInstance();

    $contenido ="";
    if($app->tieneRol(Usuario::HOST_ROLE)){
       $contenido = <<<EOS
            <a href="mis_pisos.php">Mis pisos</a>
       EOS;
    }
    elseif($app->tieneRol(Usuario::ROOMIE_ROLE)){

    }
    return $contenido;
}

if ($app->usuarioLogueado()){
    $nombre = $app->nombre();
    $formLogout = new \es\ucm\fdi\aw\usuarios\FormularioLogout();
    $html_form_logout = $formLogout->gestiona();

    if($app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::HOST_ROLE)){
        $ruta_icon = "img/top-hat-2.svg";
    }
    else{
        $ruta_icon = "img/bedroom.svg";
    }


    $contexto= <<<EOF
        <div class="align-item-center diplay-flex">
            <div class="pr-10p">
                <a class="username clear-text-deco" href="mi_perfil.php">
                    $nombre
                </a>
            </div>
            <div class="dropdown" id="drop-down">
                
                <img class="user_icon" src="$ruta_icon"  alt="default_user" />
                <img src="img/arrow-down.svg"  alt="desplegable" />
                
                <div class="bloque_desplegable">
                    <input type="checkbox">
                    <ul>
                        <li class="desplegable_item"><a href="mi_perfil.php">Mi perfil</a></li>
                        <li class="desplegable_item">$html_form_logout</li>
                    </ul>
                </div>
            </div>
        </div>      
    EOF;
}
else{
    $contexto = <<<EOS
        <div class="align-item-center diplay-flex">
            <div class="pr-10p">
                <a href="login.php">
                    Inicia sesión
                </a> 
            </div>
            <div>
                <a href="login.php">
                    <img class="user_icon" src="img/default_user.svg" alt="default_user">
                </a> 
            </div>
        </div>
    EOS;
}

$contenido = opciones_menu();
?>


<div class="menu_principal">
    <a href="index.php">
        <img id="logo_menu" src="img/logo_texto.png" alt="nombre_pagina_web">
    </a>
    <?=$contenido?>
    <div id="perfil_menu" class="align-item-center diplay-flex menu_usuario">
        <?= $contexto ?>  
    </div>
</div>

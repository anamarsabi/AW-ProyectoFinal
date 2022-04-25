<?php
require_once __DIR__.'/includes/config.php';


$formulario_registro = new  es\ucm\fdi\aw\usuarios\FormularioRegistroRoomie();
$html_form_registro = $formulario_registro->gestiona();



$tituloPagina = 'Registro';
$form_name = "form_registro_roomie";


$contenidoPrincipal = <<<EOF
    <div class='pl-20p pr-20p pt-2e'>
        <h1>$tituloPagina</h1>
        $html_form_registro;
EOF;
 
$nPag = 3;
for($i=0; $i<$nPag; $i++){
    $contenidoPrincipal .= "<span class='step'></span>";
}
    $contenidoPrincipal.="</div>";

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_general.php', $params);

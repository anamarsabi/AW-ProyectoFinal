<?php
require_once __DIR__.'/includes/config.php';

$formulario_busqueda = new es\ucm\fdi\aw\usuarios\FormularioBusqueda();
$html = $formulario_busqueda->gestiona();
echo $html;
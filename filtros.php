<?php
require_once __DIR__.'/includes/config.php';

$formulario_filtro = new es\ucm\fdi\aw\usuarios\FormularioFiltro();
$html = $formulario_filtro->gestiona();
echo $html;
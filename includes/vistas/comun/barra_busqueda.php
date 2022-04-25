<?php

use es\ucm\fdi\aw\usuarios\FormularioBusqueda;


$formulario_busqueda = new FormularioBusqueda();
$form = $formulario_busqueda->gestiona();
$contenido = "<div class = 'input-group rounded'>" . $form . "</div>";




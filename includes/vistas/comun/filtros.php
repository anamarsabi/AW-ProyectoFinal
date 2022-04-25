<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\FormularioFiltro;

function mostrarBarraFiltros()
{
    $html = '';
    $formulario_filtro = new FormularioFiltro();
    $html = $formulario_filtro->gestiona();

    return $html;
}
?>

<div class = "input-group rounded">
    <?php echo mostrarBarraFiltros() ?>
</div>
<?php
    $params['app']->doInclude('/vistas/helpers/plantilla.php');
    $mensajes = mensajesPeticionAnterior();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?= $params['tituloPagina'] ?></title>
        <link rel="stylesheet" type="text/css" href="<?= $params['app']->resuelve('/css/estilo.css') ?>" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro&display=swap" rel="stylesheet">

        <link rel="icon"  href="<?= $params['app']->resuelve('/img/favicon.ico') ?>">
        
    </head>
    <body>
        <?= $mensajes ?>
        <div id="page-container">
            <div id="content-wrap">
                <?php
                    $params['app']->doInclude('/vistas/comun/menu_principal.php');
                    $params['app']->doInclude('/vistas/comun/sidebarIzq.php');
                ?>
                <main>
                    <?= $params['contenidoPrincipal'] ?>
                </main>
                <div class="separador-h-50"></div>
            </div>
                
            <?php
                $params['app']->doInclude('/vistas/comun/pie.php');
            ?>
            
        </div>
        <?= $javascript??"" ?>
    </body>
</html>




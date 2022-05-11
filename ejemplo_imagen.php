<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\Imagen;

$formUpload = new es\ucm\fdi\aw\usuarios\FormularioUpload();
$htmlFormUpload = $formUpload->gestiona();

$imagenesPublicas = Imagen::getImagenes();

RUTA_ALMACEN_PUBLICO
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Ejemplo de formulario con archivos</title>
    <script src="utils.js"></script>
	</head>
  <body>
    <h2>Subir nueva imagen</h2>
    <?= $htmlFormUpload ?>
    <h3>Cambiar privilegios</h3>
    <h2>Imagenes Públicas <?= count($imagenesPublicas)?> </h2>
<?php foreach($imagenesPublicas as $imagen): ?>
    <div><img src="almacenPublico/<?= $imagen->ruta?>"></div>
<?php endforeach ?>
  </body>
</html>
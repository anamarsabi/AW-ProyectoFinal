<?php

$usuarios = es\ucm\fdi\aw\usuarios\Usuario::getUsuarios();
$app =es\ucm\fdi\aw\Aplicacion::getInstance();

$botonAgregarPiso =  new \es\ucm\fdi\aw\usuarios\FormularioBotonRegistroPiso();

$contenido = <<<EOS
    <div class="col-5 centrado index-banner-block">
            <h1>Agregar Pisos</h1>
            {$botonAgregarPiso->gestiona()}
        </div> 

    <div class="col-5 centrado index-banner-block">
        <h1>Vista de pisos</h1>
    </div> 
EOS;

if($usuarios){
    foreach ($usuarios as $usuario) {
        $rol=$usuario->obtieneRol($usuario->getId());
        $botonEditarUsuario =  new \es\ucm\fdi\aw\usuarios\FormularioBotonEditUsuario($usuario->getId());
        $botonEliminarUsuario =  new \es\ucm\fdi\aw\usuarios\FormularioBotonEliminarUsuario($usuario->getId());
          $contenido.= <<<EOS

            <div class="centrado card">
                    <div class="card-header">
                        {$usuario->getNombre()}
                    </div>

                    <div class="card-body">
                       Id: #{$usuario->getId()}
                     </div>
                    <div class="card-body">
                        Apellidos: {$usuario->getApellido1()} {$usuario->getApellido2()}
                    </div>

                    <div class="card-body">
                        Fecha de nacimiento: {$usuario->getBirthday()}
                    </div>

                    <div class="card-body">
                        Correo: {$usuario->getCorreo()}
                    </div>

                    <div class="card-body">
                    Tipo de usuario: {$usuario->obtieneNombreRol($rol)}
                    </div>

                     <div class="card-body">
                        Editar: {$botonEditarUsuario->gestiona()}
                        Eliminar: {$botonEliminarUsuario->gestiona()}
                    </div>
                  
            </div>

    EOS;
    }
   
}  

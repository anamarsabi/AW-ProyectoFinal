<?php
    $datos_caracteristico_tipo_usuario ="";
    $app = es\ucm\fdi\aw\Aplicacion::getInstance();

    if($app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::HOST_ROLE)){
        
    }
    elseif($app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::ROOMIE_ROLE)){
        $datos_caracteristico_tipo_usuario = <<<EOS
            <li>
                <a class="clear-text-deco cursor-pointer" onclick="transicion('id_3')">
                    Datos de roomie
                </a>
            </li>
        EOS;
    }

?>

<div class="float-l">
    <ul class="clear-style detalles id_1">
        <li class="detalles-list-item active">
            <a onclick="transicion('id_1')">
                Datos personales
            </a>
        </li>
        <li class="detalles-list-item id_2">
            <a onclick="transicion('id_2')">
                Datos de acceso
            </a>
        </li>

        <?=$datos_caracteristico_tipo_usuario?>
        
    </ul>
</div>



<script>
//Busca una forma de pasarle una variable onload
//JS coge datos de formularios. Puedes pasar datos por GET y recogerlos
window.onload = function exampleFunction() {
    transicion("id_1");
}

function transicion(name){

   let elems = document.getElementsByClassName('pagina');
   let items = document.getElementsByClassName('detalles-list-item');

   for (i = 0; i < elems.length; i++) {
      if(!elems[i].classList.contains(name)){
        elems[i].style.display="none";
      }
      else{
        elems[i].style.display="";
      }
   }

   for(i=0; i<items.length; i++){
        if(!elems[i].classList.contains(name)){
            items[i].classList.remove("active");
        }
        else{
            items[i].classList.add("active");
        }

   }

  

   

   

}

</script>
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
    }elseif($app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::ADMIN_ROLE)){

        $datos_caracteristico_tipo_usuario = <<<EOS
            <li>
                <a class="clear-text-deco" href="mi_perfil.php?pag=id_4">
                     Administración
                </a>
            </li>
    EOS;
    }

?>

<div class="float-l">
    <ul class="clear-style detalles id_1">
        <li class="detalles-list-item active">
            <a class="clear-text-deco" href="mi_perfil.php?pag=id_1">
                Datos personales
            </a>
        </li>
        <li class="detalles-list-item id_2">
            <a class="clear-text-deco" href="mi_perfil.php?pag=id_2">
                Datos de acceso
            </a>
        </li>

     
        <?=$datos_caracteristico_tipo_usuario?>
        
    </ul>
</div>


<script>
//https://www.sitepoint.com/get-url-parameters-with-javascript/
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

window.onload = function exampleFunction() {
    var pag = urlParams.get('pag')??"id_1";
    transicion(pag);
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
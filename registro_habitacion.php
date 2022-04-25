<?php

$titulo_pagina = 'Registro habitación';

$contenido_principal = <<<EOS
    <div class="tab marg">
        <p>Añadir habitación</p>
 
        <label for="cama">Cama</label><br>
        <input type="number" placeholder="Dimensiones en centimentros " name="cama" required><br>
       
        <label for="precio">Precio</label><br>
        <input type="int" placeholder="Precio" name="precio" required><br><br>
       
        <label for="banio"> Baño
        <input type="checkbox" name="banio" value='banio'></label><br>
   
        <label for="gastos_incluidos">Gastos incluidos
        <input type="checkbox" name="gastos_incluidos" value="gastos_incluidos"><label for="gastos_incluidos"></label><br>
        
        Una breve descripción sobre la habitacion
        <textarea name="descripcion" rows="4" cols="100%" placeholder="Este habitacion ofrece..."></textarea><br>
       
        Disponibilidad
        <input type="date" name="date" aria-describedby="date-format" min="1950-01-01" max="2031-01-01" />
       
    </div>
    
    EOS;

include 'includes/templates/plantilla_register.php';
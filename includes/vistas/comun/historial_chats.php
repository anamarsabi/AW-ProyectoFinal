<?php

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();
$id = $app->idUsuario();
$sql = "SELECT * 
        FROM chat
        WHERE id_usuario ='$id' or id_host = '$id'";

$result = $conn->query($sql);
if ($result->num_rows > 0){
    // Tomamos el total de los resultados
    $total = mysqli_num_rows($result);
    if ($total == 0) {
        //Bo hay ningun chat de este usuario";
        exit;
    }
    
    $resultados = array();  #Array de chats del usuario
    while ($row = $result->fetch_assoc()) 
    {
        
        # Almacenamos en un objeto de la clase chat
        $chat = new \es\ucm\fdi\aw\Chat($row['id_usuario'], $row['id_host']);        
        #Añadimos el chat al array de resultados
        array_push($resultados, $chat);
        //Ahora hay que imp`rimir todos con un enlace a que se abra ese chat
    }
    $result->free();

}

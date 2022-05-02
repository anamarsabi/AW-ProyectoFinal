<?php
class Chat{
       
	/**
     * @var string id del usuario loguea
     */
    private $id_usuario;

    /**
     * @var string[] array de los chats que tiene guardados
     */
    private $chats;
	
    
    public function __construct($id_usuario){
		$app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();

		$this->$id_usuario = $id_usuario;

    }

	
}
?>
<?php
class Chat{
       
	/**
     * @var string id del usuario loguea
     */

    /**
     * @var string[] array de los chats que tiene guardados
     */
	
    private $id_chat;
    private $id_usuario;
    private $id_host;
    private $mensaje;
    
    public function __construct($id_usuario,$id_chat=null,$id_host,$mensaaje){

		    $this->$id_usuario = $id_usuario;
        $this->$id_chat = $id_chat;
        $this->$mensaje = $mensaje;
        $this->$id_host = $id_host;
    }

    private static function inserta($chat)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        $query = sprintf("INSERT INTO chat (id_usuario, id_host, mensaje)  
                        VALUES ('%s','%s', '%s')"
            , $conn->real_escape_string($chat->id_usuario)
            , $conn->real_escape_string($chat->id_host)
            , $conn->real_escape_string($chat->mensaje)
        );
        if ( $conn->query($query) ) {
            $chat->id_chat = $conn->insert_id;
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
	

    public function guarda()
    {
        if ($this->id_chat !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }


    public static function crea($id_usuario, $id_host, $mensaje){
      $chat = new Chat($id_usuario, $id_host, $mensaje);
      
      return $chat->guarda();
  }

  private static function actualiza($chat)
  {
      $result = false;
      $conn = Aplicacion::getInstance()->getConexionBd();
      $query=sprintf("UPDATE chat c SET mensaje = '%s' WHERE c.id_chat=%d"
          , $conn->real_escape_string($chat->mensaje)
          , $piso->id_chat
      );
      if ( $conn->query($query) == false) {
        error_log("Error BD ({$conn->errno}): {$conn->error}");
      } 
      else{
        $result = true;
      }
      
      return $result;
  }

  private static function borraMensaje($chat)
  {
      $conn = Aplicacion::getInstance()->getConexionBd();
      $query = sprintf("DELETE FROM chat WHERE id_chat=%d"
          , $chat->id_chat
      );
      if ( ! $conn->query($query) ) {
          error_log("Error BD ({$conn->errno}): {$conn->error}");
          return false;
      }
      return true;
  }

  public static function buscaPorId($idchat)
    {
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();

        $query = sprintf("SELECT * FROM chat WHERE id_chat=%d", $idchat);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Chat($fila['id_usuario'], $idchat, $fila['id_host'], $fila['mensaje']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function chatperteneceausuario($id_chat, $id_usuario){
      $conn = Aplicacion::getInstance()->getConexionBd();
      $query = sprintf("SELECT * 
                      FROM chat c JOIN usuario u ON c.id_usuario=u.id_usuario  WHERE c.id_chat=%d AND u.id_usuario=%d", $id_chat, $id_usuario);
      $rs = $conn->query($query);
      $result = false;
      if ($rs) {
         $result = true;
      } else {
          error_log("Error BD ({$conn->errno}): {$conn->error}");
      }
      return $result;
  }


   
  public function getIdchat()
  {
      return $this->id_chat;
  }

  public function getMensaje()
  {
      return $this->mensaje;
  }

  public function getIdhost()
  {
      return $this->id_host;
  }

  public function getIdusuario()
  {
      return $this->id_usuario;
  }

}
?>
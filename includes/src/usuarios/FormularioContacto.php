<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioContacto extends Form
{
    public function __construct() {
        $app = Aplicacion::getInstance();
        $piso = $app->getPiso();
        $id_host = $piso->getIdHost();
        $user_host = Usuario::buscaPorId($id_host);
        $mail = $user_host->getCorreo();
        $mailto = "";
        $mailto .= "mailto:";
        $mailto .= $mail;
        parent::__construct('formContacto', ['action' => $mailto, 'urlRedireccion' => Aplicacion::getInstance()->resuelve('/mostrar_piso.php')]);
    }
    
    protected function generaCamposFormulario($datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['mensaje'], $this->errores, 'span', array('class' => 'error text-danger'));
        $app = Aplicacion::getInstance();
        $piso = $app->getPiso();
        $direccion = $piso->getCalle();
        $ciudad = $piso->getCiudad();

        $id_piso = $piso->getId();
        $app->putAtributoPeticion("id_piso", $id_piso);

        $nombre = $app->nombre();
        $html = <<<EOF
            $htmlErroresGlobales
            <div class="chat">	
                <div id="frame">		
                    <div id="sidepanel">
                        <div id="profile">
                        <?php
                        include ('Chat.php');
                        $chat = new Chat();
                        $loggedUser = $chat->getUserDetails($_SESSION['userid'])
                        echo '<div class="wrap">';
                        $currentSession = '';
                        foreach ($loggedUser as $user) {
                            $currentSession = $user['current_session'];
                            echo '<img id="profile-img" src="userpics/'.$user['avatar'].'" class="online" alt="" />';
                            echo  '<p>'.$user['username'].'</p>';
                                echo '<i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>';
                                echo '<div id="status-options">';
                                echo '<ul>';
                                    echo '<li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>';
                                    echo '<li id="status-away"><span class="status-circle"></span> <p>Ausente</p></li>';
                                    echo '<li id="status-busy"><span class="status-circle"></span> <p>Ocupado</p></li>';
                                    echo '<li id="status-offline"><span class="status-circle"></span> <p>Desconectado</p></li>';
                                echo '</ul>';
                                echo '</div>';
                                echo '<div id="expanded">';			
                                echo '<a href="logout.php">Salir</a>';
                                echo '</div>';
                        }
                        echo '</div>';
                        ?>
                        </div>
                        <div id="search">
                            <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
                            <input type="text" placeholder="Buscar Contactos..." />					
                        </div>
                        <div id="contacts">	
                        <?php
                        echo '<ul>';
                        $chatUsers = $chat->chatUsers($_SESSION['userid']);
                        foreach ($chatUsers as $user) {
                            $status = 'offline';						
                            if($user['online']) {
                                $status = 'online';
                            }
                            $activeUser = '';
                            if($user['userid'] == $currentSession) {
                                $activeUser = "active";
                            }
                            echo '<li id="'.$user['userid'].'" class="contact '.$activeUser.'" data-touserid="'.$user['userid'].'" data-tousername="'.$user['username'].'">';
                            echo '<div class="wrap">';
                            echo '<span id="status_'.$user['userid'].'" class="contact-status '.$status.'"></span>';
                            echo '<img src="userpics/'.$user['avatar'].'" alt="" />';
                            echo '<div class="meta">';
                            echo '<p class="name">'.$user['username'].'<span id="unread_'.$user['userid'].'" class="unread">'.$chat->getUnreadMessageCount($user['userid'], $_SESSION['userid']).'</span></p>';
                            echo '<p class="preview"><span id="isTyping_'.$user['userid'].'" class="isTyping"></span></p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</li>'; 
                        }
                        echo '</ul>';
                        ?>
                        </div>
                        <div id="bottom-bar">	
                            <button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Agregar Contactos</span></button>
                            <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Configuracion</span></button>					
                        </div>
                    </div>			
                    <div class="content" id="content"> 
                        <div class="contact-profile" id="userSection">	
                        <?php
                        $userDetails = $chat->getUserDetails($currentSession);
                        foreach ($userDetails as $user) {										
                            echo '<img src="userpics/'.$user['avatar'].'" alt="" />';
                                echo '<p>'.$user['username'].'</p>';
                                echo '<div class="social-media">';
                                    echo '<i class="fa fa-facebook" aria-hidden="true"></i>';
                                    echo '<i class="fa fa-twitter" aria-hidden="true"></i>';
                                    echo '<i class="fa fa-instagram" aria-hidden="true"></i>';
                                echo '</div>';
                        }	
                        ?>						
                        </div>
                        <div class="messages" id="conversation">		
                        <?php
                        echo $chat->getUserChat($_SESSION['userid'], $currentSession);						
                        ?>
                        </div>
                        <div class="message-input" id="replySection">				
                            <div class="message-input" id="replyContainer">
                                <div class="wrap">
                                    <input type="text" class="chatMessage" id="chatMessage<?php echo $currentSession; ?>" placeholder="Escribe tu mensaje..." />
                                    <button class="submit chatButton" id="chatButton<?php echo $currentSession; ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>	
                                </div>
                            </div>					
                        </div>
                    </div>
                </div>
		</div>
        EOF;
        return $html; 
    }
    
    
    protected function procesaFormulario($datos)
    {
        $this->errores = [];
        $mensaje = $datos['mensaje'] ?? '';
        if(empty($mensaje))
        {
            $this->errores['mensaje'] = "El mensaje no puede estar vacío.";
        }
        $app = Aplicacion::getInstance();

        $mensajes = ['Has contactado a host. Espera su espuesta en tu email.'];
        $app->putAtributoPeticion('mensajes', $mensajes);

        $piso = $app->getPiso();
        $id_piso = $piso->getId();
        $app->putAtributoPeticion("id_piso", $id_piso);

        $res = $app->resuelve('/mostrar_piso.php');

        return $res;
        
    }
}
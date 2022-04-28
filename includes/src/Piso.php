<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\usuarios\FormularioDetalles;
use es\ucm\fdi\aw\usuarios\UsuarioRoomie;
use es\ucm\fdi\aw\usuarios\Usuario;

class Piso
{
    use MagicProperties;

    private $id;
    private $id_host;
    private $calle;
    private $barrio;
    private $ciudad;
    private $detalles;
    private $id_roomies; // Array de id de usuarios que son roomies del piso
    

    public function __construct($id_host, $calle, $barrio, $ciudad, $detalles = array(), $id = null)
    {
        $this->id = $id;
        $this->id_host = $id_host;
        $this->calle = $calle;
        $this->barrio = $barrio;
        $this->ciudad = $ciudad;
        $this->detalles = $detalles;
        $this->id_roomies = [];
    }

    public function aniadeDetalles($detalles){
        
        $this->detalles['mascota'] = $detalles['mascota'];
        $this->detalles['servicios'] = $detalles['servicios'];
        $this->detalles['fotos'] = $detalles['fotos'];
        $this->detalles['descripcion'] = $detalles['descripcion'];
        $this->detalles['precio_max'] = $detalles['precio_max'];
        $this->detalles['precio_min'] = $detalles['precio_min'];
        $this->detalles['num_banios'] = $detalles['num_banios'];

        $this->detalles['plazas_libres'] = $detalles['plazas_libres'];
        $this->detalles['plazas_ocupadas'] = $detalles['plazas_ocupadas'];
    }

    public static function crea($id_host, $calle, $barrio, $ciudad, $detalles){
        $piso = new Piso($id_host, $calle, $barrio, $ciudad);
        $piso->aniadeDetalles($detalles);
        return $piso->guarda();
    }

    public function guarda()
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    private static function inserta($piso)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        $query = sprintf("INSERT INTO pisos (id_host, calle, barrio, ciudad, num_banios, permite_mascota, descripcion_piso, precio_max, precio_min)  
                        VALUES ('%s','%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d')"
            , $conn->real_escape_string($piso->id_host)
            , $conn->real_escape_string($piso->calle)
            , $conn->real_escape_string($piso->barrio)
            , $conn->real_escape_string($piso->ciudad)
            , $conn->real_escape_string($piso->detalles['num_banios'])
            , $conn->real_escape_string($piso->detalles['mascota'])
            , $conn->real_escape_string($piso->detalles['descripcion'])
            , $conn->real_escape_string($piso->detalles['precio_max'])
            , $conn->real_escape_string($piso->detalles['precio_min'])
        );
        if ( $conn->query($query) ) {
            $piso->id = $conn->insert_id;
            $result = self::insertaServicios($piso);
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function actualiza($piso)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE pisos P SET calle = '%s', barrio='%s', ciudad='%s', permite_mascota='%d', descripcion_piso='%s', num_banios='%d' WHERE P.id_piso=%d"
            , $conn->real_escape_string($piso->calle)
            , $conn->real_escape_string($piso->barrio)
            , $conn->real_escape_string($piso->ciudad)
            , $conn->real_escape_string($piso->permiteMascota())
            , $conn->real_escape_string($piso->descripcion)
            , $conn->real_escape_string($piso->num_banios)
            , $piso->id
        );
        if ( $conn->query($query) ) {
            $result = self::borraServicios($piso);
            if ($result) {
                $result = self::insertaServicios($piso);
            }
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }


    private static function borraServicios($piso)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM detallespiso WHERE id_piso=%d"
            , $piso->id
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    private static function insertaServicios($piso)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $servicios_dict = Busqueda::getServicios();

        foreach($piso->detalles['servicios'] as $d) {
            //Dada el valor, busca la clave
            $id = array_search ($d, $servicios_dict);
            if($id){
                //Deberia llamarse serviciosPiso :T?
                $query = sprintf("INSERT INTO detallespiso(id_piso, id_servicio) VALUES (%d, %d)"
                    , $piso->id
                    , $id
                );
                if ( ! $conn->query($query) ) {
                    error_log("Error BD ({$conn->errno}): {$conn->error}");
                    return false;
                }
            }
        }
        return $piso;
    }


    //Retorna array de detalles del piso
    public static function getServiciosPorIdPiso($id){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM detallespiso WHERE id_piso=%d", $id);
        $rs = $conn->query($query);
        if ($rs) {
            $result = Array();
            $nombres = Busqueda::getServicios();

            while ($fila = $rs->fetch_assoc()) {
                $result[] = $nombres[$fila['id_servicio']];
            }
            
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            $result = false;
        }
        return $result;
    }



    public static function pisoPerteneceAHost($id_piso, $id_host){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM pisos WHERE id_host=%d AND id_piso=%d", $id_host, $id_piso);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
           $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }


    
    public function encuentraRoomies()
    {
        $id_piso = $this->id;
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $sql = "SELECT *
                FROM usuarios
                INNER JOIN roomies ON usuarios.id_usuario = roomies.id_usuario
                INNER JOIN habitaciones ON roomies.id_usuario = habitaciones.id_roomie
                WHERE habitaciones.id_piso ='$id_piso'
                ORDER BY usuarios.id_usuario";

        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            // Tomamos el total de los resultados
            $total = mysqli_num_rows($result);
            if ($total == 0) {
                //echo "No se han encontrado filas, nada a imprimir, asi que voy a detenerme.";
                exit;
            }

            $id_roomies = array();  // Array de id de usuarios que son roomies del piso, inicializado vacio.
            while ($row = $result->fetch_assoc()) 
            {
                array_push($id_roomies, $row['id_usuario']);
            }
            $result->free();
            $this->id_roomies = $id_roomies;
        }          
    }
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getCalle()
    {
        return $this->calle;
    }

    public function getBarrio()
    {
        return $this->barrio;
    }

    public function getIdHost()
    {
        return $this->id_host;
    }

    public function getCiudad()
    {
        return $this->ciudad;
    }

    public function getDetalles()
    {
        return $this->detalles;
    }

    public function permiteMascota(){
        return $this->detalles['mascota']==true;
    }

    public function getServicios()
    {
        return $this->detalles['servicios'];
    }

    public function getPrecio_max()
    {
        return $this->detalles['precio_max'];
    }

    public function getPrecio_min()
    {
        return $this->detalles['precio_min'];
    }

    public function getPlazas_libres()
    {
        return $this->detalles['plazas_libres'];
    }

    public function getPlazas_ocupadas()
    {
        return $this->detalles['plazas_ocupadas'];
    }

    public function getNum_banios()
    {
        return $this->detalles['num_banios'];
    }

    public function getDescripcion()
    {
        return $this->detalles['descripcion'];
    }

    /*
    public function setRoomies($users)
    {
        $this->roomies = $users;
    }
    */

    public function imprimirCorto()
    {

        $formulario_detalles = new FormularioDetalles();
        $formulario_detalles->setPisoFormulario($this);
        $html = $formulario_detalles->gestiona();
        

        $imp = "";
        $imp .= <<<EOF
        <div class = "piso">
                <h1>{$this->calle}</h1>
                <p>Habitaciones desde {$this->detalles['precio_min']} € - $this->detalles['precio_max']  € al mes</p>
                <p>Número de plazas libres {$this->detalles['plazas_libres']} </p>
        </div>
        EOF;
        $imp .= $html;

        return $imp;
    }

    
    public function imprimirDetalles(){
        // Parte roomies del piso
        self::encuentraRoomies();
        $html_roomies = "";
        if(count($this->id_roomies)===0)
        {
            $html_roomies .= <<<EOF
                <p>Aún no hay roomies en este piso. Sé el primero! </p>
            EOF;
        }
        else{
            //$html_roomies .= "<ul>";
            foreach($this->id_roomies as $id)
            {
                $user = Usuario::buscaPorId($id);
                $info_roomie = UsuarioRoomie::buscaRoomiePorId($id, []);
                $descripcion = $info_roomie->getDescripcion();
                $mascota = $info_roomie->getMascota();
                $tiene_mascota = "";
                if (intval($mascota) === 1)
                {
                    $tiene_mascota .= "Tengo una mascota";
                }
                $html_roomies .= <<<EOF
                    <h3>Conoce tus roomies: </h3>
                    <div class="centrado card">
                        <div class="card-header">
                            {$user->getNombre()}
                        </div>
                        <div class="card-body"> 
                            <ul class="inline-block clear-style clear-pm">
                                <li>
                                    <p>{$descripcion} </p>
                                </li>
                                <li>
                                    <p>{$tiene_mascota}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                EOF;
            }
            //$html_roomies .= "</ul>";
        }

        //Parte habitaciones libres y ocupadas del piso
        $app= Aplicacion::getInstance();
        $ruta_color = $app->resuelve('/img/door_color.svg'); 
        $ruta = $app->resuelve('/img/door.svg'); 

        $num_ocupadas = $this->getPlazas_ocupadas();

        $iconos_habitaciones = "<div>";
        
        for($i=0;$i<$num_ocupadas;$i++){
            $iconos_habitaciones .= '<img src="'.$ruta_color.'" alt="Hab. Ocupada '.$i.'" width="50" height="50">';
        }

        $num_libres = $this->getPlazas_libres();

        for($i=0;$i<$num_libres;$i++){
            $iconos_habitaciones .= '<img src="'.$ruta.'" alt="Hab. Libre '.$i.'" width="50" height="50">';
        }

        if($num_libres==0&&$num_ocupadas==0){
            $iconos_habitaciones .='<img src="'.$ruta.'" alt="Hab. Libre '.$i.'" width="50" height="50">';
        }

        $iconos_habitaciones .= "</div>";

        // Parte servicios del piso
        $servicios = $this->getServicios();
        $block ="";
        foreach($servicios as $s)
        {
            $block .=<<<EOF
                <div class="label-servicios mx-1e">
                    <p>{$s}</p>
                </div>
            EOF;
        }
        $html_servicios = "";
        $html_servicios .= <<<EOF
            <div class="flex-container-servicios wrap">
        EOF;
        $html_servicios .=<<<EOF
            $block
            <div class="label-servicios mx-1e">
                <p>{$this->getNum_banios()} baños </p>
            </div>
            </div>   
        EOF;

        $ruta_contacto = $app->resuelve('contacto.php');
        // HTML de todos los detalles del piso
        $detalles = <<<EOF
            <div class="centrado card">
                <div class="card-header">
                    {$this->calle}
                    <h6>{$this->barrio},{$this->ciudad} </h6>
                </div>
                <div class="card-body">
                    <form action="$ruta_contacto">
                        <input class="button align-r" type="submit" value="Contactar">  
                    </form>
                    <ul class="inline-block clear-style clear-pm">
                        <li>
                            <img class="h-100 w-10e" src="img/logo.png" alt="Imagen">
                        </li>
                        <li>
                            <h4>Habitaciones desde {$this->detalles['precio_min']} € - {$this->detalles['precio_max']} € al mes</h4>
                            <p>{$this->detalles['descripcion']}</p>
                            
                        </li>
                        <li>
                            $iconos_habitaciones
                        </li>
                    </ul>
                    $html_servicios
                    $html_roomies
                </div>
            </div>
        EOF;
        return $detalles;

    }

    //Retorna array de Pisos que pertenecen a id
    public static function getPisosPorIdHost($id){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM pisos WHERE id_host=%d", $id);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $detalles = Piso::getDetallesPiso($fila);
                //$id_host, $calle, $barrio, $ciudad, $detalles = array(), $id = null
                $result[] = new Piso($id, $fila['calle'], $fila['barrio'],  $fila['ciudad'], $detalles, $fila['id_piso']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function getDetallesPiso($fila){

        $detalles["servicios"] = self::getServiciosPorIdPiso($fila['id_piso']);
        $detalles['mascota'] = $fila['permite_mascota']??"";
        $detalles['descripcion'] = $fila['descripcion_piso']??"";
        $detalles['precio_max'] = $fila['precio_max']??"";
        $detalles['precio_min'] = $fila['precio_min']??"";
        $detalles['plazas_libres'] = $fila['plazas_libres']??"";
        $detalles['fotos'] = $fila['fotos']??"";
        $detalles['num_banios'] = $fila['num_banios']??0;
        
        
        $res = self::numHabOcupadasyLibresDelPiso($fila['id_piso']);

        $detalles['plazas_ocupadas'] = $res['ocupadas']??0;
        $detalles['plazas_libres'] = $res['libres']??0;

        return $detalles;

    }


     //Devuelve un diccionario con la cantidad de habitaciones ocupadas y libres
    private static function numHabOcupadasyLibresDelPiso($id){
        $habitaciones = self::getHabitacionesPorIdPiso($id);
        $ocupadas = 0;
        $libres = 0;
        foreach($habitaciones as $hab){
            if($hab->estaOcupada()){
                $ocupadas+=1;
            }
            else{
                $libres+=1;
            }
        }
        
        $result = ["ocupadas"=>$ocupadas, "libres"=>$libres];
        return $result;
    }

    public static function getHabitacionesPorIdPiso($id){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM habitaciones WHERE id_piso=%d", $id);
        $rs = $conn->query($query);
        if ($rs) {
            $result = Array();
            while ($fila = $rs->fetch_assoc()) {
                /*
                $id = $fila['id'];
                $id_piso = $fila['id_piso'];
                $tam_cama = $fila['cama'];
                $banio_privado = $fila['banio']==1;
                $precio  = $fila['precio'];
                $gastos_incluidos = $fila['gastos_incluidos']==1;
                $descripcion = $fila['descripcion'];
                $d = strtotime($fila['disponibilidad']);
                $fecha_disponibilidad = strtotime($fila['disponibilidad'])<=0
                    ?$fila['disponibilidad']
                    :NULL;
              
                $result[] = new Habitacion($id, $id_piso, $tam_cama, $banio_privado, $precio, $gastos_incluidos, $descripcion, $fecha_disponibilidad);
                */
                $id_habitacion = $fila['id_habitacion'];
                $tam_cama = $fila['cama_cm'];
                $banio_privado = $fila['banio_propio']==1;
                $precio  = $fila['precio'];
                $gastos_incluidos = $fila['gastos_incluidos']==1;
                $descripcion = $fila['descripcion'];
                $d = strtotime($fila['disponibilidad']);
                $fecha_disponibilidad = strtotime($fila['disponibilidad'])<=0
                    ?$fila['disponibilidad']
                    :NULL;

                $id_roomie = $fila['id_roomie'];
                
                $detalles = [
                    'tam_cama'=>$tam_cama,
                    'banio_privado'=>$banio_privado,
                    'precio'=>$precio,
                    'gastos_incluidos'=>$gastos_incluidos,
                    'descripcion'=>$descripcion,
                    'fecha_disponibilidad'=>$fecha_disponibilidad,
                ];
                $result[] = new Habitacion($id, $detalles, $id_roomie, $id_habitacion);
            }
            
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            $result = false;
        }
        return $result;
    }



    public static function buscaPorId($idPiso)
    {
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $id_host = $app->idUsuario();

        $query = sprintf("SELECT * FROM pisos WHERE id_piso=%d", $idPiso);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $detalles = self::getDetallesPiso($fila);
                $result = new Piso($id_host, $fila['calle'], $fila['barrio'],  $fila['ciudad'], $detalles, $fila['id_piso']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaIdPiso()
    {
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $id_host = $app->idUsuario();

        $query = sprintf("SELECT * FROM pisos WHERE id_host=%d", $id_host);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = $fila['id_piso'];
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public function cambiaDatos($calle, $barrio, $ciudad, $detalles)
    {
        // $this->id_host = $id_host;
        $this->calle = $calle;
        $this->barrio = $barrio;
        $this->ciudad = $ciudad;
        self::aniadeDetalles($detalles);
    }

    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }

    private static function borra($piso)
    {
        return self::borraPorId($piso->id);
    }

    private static function borraPorId($idPiso)
    {
        if (!$idPiso) {
            return false;
        } 
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM pisos WHERE id_piso = %d"
            , $idPiso
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }
    
    
}


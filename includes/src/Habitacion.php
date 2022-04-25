<?php
namespace es\ucm\fdi\aw;

// use es\ucm\fdi\aw\Aplicacion;
// use es\ucm\fdi\aw\MagicProperties;

class Habitacion
{
    use MagicProperties;

    private $id_habitacion;
    private $id_piso;
    private $id_roomie;
    private $detalles;
    // private $tam_cama;
    // private $banio_privado;
    // private $precio;
    // private $gastos_incluidos;
    // private $descripcion;
    // private $fecha_disponibilidad;

    // public function __construct($id_host, $calle, $barrio, $ciudad, $detalles = array(), $id = null

    public function __construct($id_piso, $detalles=array(), $id_roomie=null, $id = null)
    {
        $this->id_habitacion = $id;
        $this->id_piso = $id_piso;
        $this->id_roomie = $id_roomie;
        $this->detalles = $detalles;
    }

    public function aniadeDetalles($detalles){
        
        $this->detalles['tam_cama'] = $detalles['tam_cama'];
        $this->detalles['banio_privado'] = $detalles['banio_privado'];
        $this->detalles['precio'] = $detalles['precio'];
        $this->detalles['descripcion'] = $detalles['descripcion'];
        $this->detalles['gastos_incluidos'] = $detalles['gastos_incluidos'];
        $this->detalles['fecha_disponibilidad'] = $detalles['fecha_disponibilidad'];
    }

    public static function crea($id_piso, $detalles){
        $hab = new Habitacion($id_piso);
        $hab->aniadeDetalles($detalles);
        return $hab->guarda();
    }

    public function guarda()
    {
        if ($this->id_habitacion !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    private static function inserta($hab)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();

        $aux = print_r($hab->detalles);
        
        $query = sprintf("INSERT INTO habitaciones (id_piso, cama_cm, banio_propio, precio, gastos_incluidos, descripcion, disponibilidad)  
                        VALUES ('%d','%d', '%d', '%d', '%d', '%s', '%s')"
            , $conn->real_escape_string($hab->id_piso)
            , $conn->real_escape_string($hab->detalles['tam_cama'])
            , $conn->real_escape_string($hab->detalles['banio_privado'])
            , $conn->real_escape_string($hab->detalles['precio'])
            , $conn->real_escape_string($hab->detalles['gastos_incluidos'])
            , $conn->real_escape_string($hab->detalles['descripcion'])
            , $conn->real_escape_string($hab->detalles['fecha_disponibilidad'])
        );
        if ( $conn->query($query) ) {
            $hab->id_habitacion = $conn->insert_id;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function actualiza($hab)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE habitaciones SET cama_cm = '%d', banio_propio='%d', precio='%d', gastos_incluidos='%d', descripcion='%s', disponibilidad='%s' WHERE id_habitacion=%d"
            , $conn->real_escape_string($hab->cama_cm)
            , $conn->real_escape_string($hab->banio_propio)
            , $conn->real_escape_string($hab->precio)
            , $conn->real_escape_string($hab->gastos_incluidos)
            , $conn->real_escape_string($hab->descripcion)
            , $conn->real_escape_string($hab->disponibilidad)
            , $hab->id
        );
        if ( !$conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }

    public static function habitacionPerteneceAHost($id_hab, $id_host){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * 
                        FROM pisos p JOIN habitaciones h ON p.id_piso=h.id_piso  WHERE p.id_host=%d AND h.id_habitacion=%d", $id_host, $id_hab);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
           $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaPorId($idHab)
    {
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $id_host = $app->idUsuario();

        $query = sprintf("SELECT * FROM habitaciones WHERE id_habitacion=%d", $idHab);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $detalles = self::getDetallesHabitacion($fila);
                $result = new Habitacion($fila['id_piso'], $detalles, null, $fila['id_habitacion']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function getDetallesHabitacion($fila){
        $detalles = Array();
        $detalles['tam_cama'] = $fila['cama_cm'];
        $detalles['banio_privado'] = $fila['banio_propio'];
        $detalles['precio'] = $fila['precio'];
        $detalles['descripcion'] = $fila['descripcion'];
        $detalles['gastos_incluidos'] = $fila['gastos_incluidos'];
        $detalles['fecha_disponibilidad'] = $fila['disponibilidad'];
    
        return $detalles;
    }


    public function getDetalles(){
        return $this->detalles;
    }

    public function getId_habitacion()
    {
        return $this->id_habitacion;
    }

    // public function getIdPiso()
    // {
    //     return $this->id_piso;
    // }

    // public function getTamCama()
    // {
    //     return $this->tam_cama;
    // }

    // public function tieneBanioPrivado()
    // {
    //     return $this->banio_privado==true;
    // }

    // public function getPrecio()
    // {
    //     return $this->precio;
    // }

    // public function tieneGastosIncluidos()
    // {
    //     return $this->gastos_incluidos==true;
    // }

    // public function getDescripcion()
    // {
    //     return $this->descripcion;
    // }

    // public function getFechaDisponibilidadn()
    // {
    //     return $this->fecha_disponibilidad;
    // }

    public function estaOcupada()
    {
        return !$this->id_roomie||$this->id_roomie == "";
    }

    // public function imprimirCorto()
    // {
    //     $imp = "";
    //     $imp .= <<<EOF
    //     <div id = "piso">
    //             <h1>$this->calle</h1>
    //             <p>Habitaciones desde $this->precio_min € - $this->precio_max € al mes</p>
    //             <p>Número de plazas libres $this->plazas_libres</p>
    //     </div>
    //     EOF;

    //     return $imp;
    // }

    // public function imprimirDetalles()
    // {
    //     $imp = "";
    //     $imp .= <<<EOF
    //         <h1>$this->calle</h1>
    //         <a href="contacto.php">Contactar</a>
    //         <div class="imagenes">
    //             <p>Fotos</p>
    //         </div>
    //         <p>Habitaciones desde $this->precio_min € - $this->precio_max € al mes</p>
    //         <p>$this->descripcion_piso</p>
    //         <p>Falta servicios</p>
    //         <p>Falta roomies</p>

    // public function imprimirDetalles(){
    //     $detalles = <<<EOS
    //         <div class="centrado card">
    //             <div class="card-header">
    //                 {$this->calle} - Avenida Rivas de Somewhere, 4, 6A
    //             </div>
    //             <div class="card-body">
    //                 <ul class="inline-block clear-style clear-pm">
    //                     <li>
    //                         <img class="va-middle h-100 w-10e" src="img/logo.png" alt="Imagen"></img>
    //                     </li>
    //                     <li>
    //                         <p>2/3 habitaciones libres</p>
    //                         <p>120-560 euros/mes</p>
    //                         <p>
    //                             <button>Editar detalles</button>
    //                             <button>Editar habitaciones</button>
    //                         </p>
    //                     </li>
    //                 </ul>
    //             </div>
    //         </div>
    //     EOS;
    //     return $detalles;
    // }
}


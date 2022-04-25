<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Piso;

/**
 * Clase base para la gestión de búsquedas.
 */
class Busqueda
{
    /**
     * @var string nombre de la ciudad por la que se ejecuta una busqueda
     */
    private $ciudad;

    /**
     * @var string fecha por la que se ejecuta una búsqueda
     */
    private $fecha;

    /**
     * @var string barrio que se va usar para el filtro
     */
    private $barrio;

    /**
     * @var int precio maximo de una habitacionq ue queremos buscar
     */
    private $precio;

    /**
     * @var int plazas libres de piso que queremos buscar
     */
    private $plazas;

    /**
     * @var Piso[] array de pisos resultado a la búsqueda por ciudad y fecha
     */
    private $resultados;

    public function __construct($ciudad, $fecha, $pisos = [])
    {
        $this->ciudad = $ciudad;
        $this->fecha = $fecha;
        $this->resultados = $pisos;
        $this->precio = null;
        $this->plazas = null;
        $this->barrio = null;

    }

    public function setResultados($pisos)
    {
        $this->resultados = $pisos;
    }

    public function getResultados(){
        return $this->resultados;
    }

    public function imprimirBusqueda(){
        $res = "";
        if (count($this->resultados) === 0) {$res.="<p>No hay resultados disponibles</p>";}
        else
        {
            foreach($this->resultados as $piso) {    
                $res.=$piso->imprimirCorto();
            }
        }

        return $res;
    }
    





    

    //Devuelve diccionario de {id_servicio => nombre}
    public static function getServicios(){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM servicios";
        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[$fila["id_servicio"]] = $fila["nombre"];
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            $result = false;
        }
        return $result;
    }

    



   




    

    // private static function habitacionOcupada($id){
    //     $conn = Aplicacion::getInstance()->getConexionBd();
    //     $query = sprintf("SELECT * FROM habitacion_roomie WHERE id_habitacion=%d", $id);
    //     $rs = $conn->query($query);
    //     if ($rs) {
    //         $result = true;
    //         $rs->free();
    //     } else {
    //         error_log("Error BD ({$conn->errno}): {$conn->error}");
    //         $result = false;
    //     }
    //     return $result;
    // }




   
}

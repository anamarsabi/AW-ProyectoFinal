<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Habitacion
{
    use MagicProperties;

    private $id_usuario;
    private $id_piso;
    private $tam_cama;
    private $banio_privado;
    private $precio;
    private $gastos_incluidos;
    private $descripcion;
    private $fecha_disponibilidad;

    public function __construct($id, $id_piso, $tam_cama, $banio_privado, $precio, 
        $gastos_incluidos, $descripcion, $fecha_disponibilidad)
    {
        $this->id_usuario = $id;
        $this->id_piso = $id_piso;
        $this->tam_cama = $tam_cama;
        $this->banio_privado = $banio_privado;
        $this->precio = $precio;
        $this->gastos_incluidos = $gastos_incluidos;
        $this->descripcion = $descripcion;
        $this->fecha_disponibilidad = $fecha_disponibilidad;
        
    }


    public function getId()
    {
        return $this->id_usuario;
    }

    public function getIdPiso()
    {
        return $this->id_piso;
    }

    public function getTamCama()
    {
        return $this->tam_cama;
    }

    public function tieneBanioPrivado()
    {
        return $this->banio_privado==true;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function tieneGastosIncluidos()
    {
        return $this->gastos_incluidos==true;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getFechaDisponibilidadn()
    {
        return $this->fecha_disponibilidad;
    }

    public function estaOcupada()
    {
        return $this->fecha_disponibilidad==null||$this->fecha_disponibilidad == "";
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


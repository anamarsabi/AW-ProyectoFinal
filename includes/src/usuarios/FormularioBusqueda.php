<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;


class FormularioBusqueda extends Form
{
    public function __construct() {
        parent::__construct('formBusqueda', [
            'action' =>  Aplicacion::getInstance()->resuelve('/barra_busqueda.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/mostrar_busqueda.php')]);
    }
    
    protected function generaCamposFormulario($datos)
    {
        $ciudad = $datos['ciudad'] ?? '';
        $fecha = $datos['fecha'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['ciudad', 'fecha'], $this->errores, 'span', array('class' => 'error text-danger'));

        $html = <<<EOF
        $htmlErroresGlobales
        <div>
            <table class="centrado">
                <tr>
                    <td>         
                        <input class="w-100 px-10-20 mx-8-0 inline default-input" id="ciudad" name="ciudad" type="search" value="$ciudad" placeholder="Ciudad" aria-label="Search" required>
                        {$erroresCampos['ciudad']}
                    </td>
                    <td>
                        <div>
                            <label for="fecha">Fecha de entrada</label>
                        </div>
                        <input class="w-100 px-10-20 mx-8-0 inline default-input" type="date" name="fecha" id="fecha" min="2021-03-01" max="2031-01-01" required>
                    </td>
                    <td>
                        <input class="w-100" type="submit" value="Buscar">  
                    </td>
                </tr>
            </table>
        </div>
        EOF;
        return $html;
    }

/* <input class="w-100 px-10-20 mx-8-0 inline default-input" type="text" name="email" placeholder="Correo electrónico" required> */
/* <button type="submit" name="buscador">Buscar</button> */    
/*<button type="submit" name="buscador"><input type="image" src='img/lupa.png' alt="buscador" width="19"
                            height=auto/></button>*/
    protected function procesaFormulario($datos)
    {
        $ciudad = $datos['ciudad'] ?? '';
        $fecha = $datos['fecha'] ?? '';
        $busqueda = new \es\ucm\fdi\aw\Busqueda($ciudad, $fecha);
    
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $sql = "SELECT * 
                FROM pisos
                INNER JOIN habitaciones ON pisos.id_piso = habitaciones.id_piso
                WHERE ciudad='$ciudad' and disponibilidad < '$fecha'
                GROUP BY habitaciones.id_piso
                ORDER BY pisos.id_piso DESC";

        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            // Tomamos el total de los resultados
            $total = mysqli_num_rows($result);
            if ($total == 0) {
                //echo "No se han encontrado filas, nada a imprimir, asi que voy a detenerme.";
                exit;
            }
            
            /* Almacenamos en un objeto de la clase busqueda la ciudad y la fecha de la busqueda 
                En este objeto también guardaremos en un array los pisos resultado de la busqueda
            */

            $resultados = array();  #Array de pisos resultado, inicializado vacio.
            while ($row = $result->fetch_assoc()) 
            {
                $detalles = array('mascota' => $row['permite_mascota'], 'descripcion' => $row['descripcion_piso'], 'fotos' => $row['fotos'],
                                    'precio_max' => $row['precio_max'], 'precio_min' => $row['precio_min'], 'plazas_libres' => $row['plazas_libres'], 'num_baños' => $row['num_banios']);
                
                # Almacenamos en un objeto de la clase piso
                $piso = new \es\ucm\fdi\aw\Piso($row['id_host'], $row['calle'], $row['barrio'], $row['ciudad'], $detalles, $row['id_piso']);
                $id_piso = $row['id_piso'];
                
                #Añadimos el piso al array de resultados
                array_push($resultados, $piso);
            }
            $result->free();

            # Añadimos los resultados a la búsqueda
            $busqueda->setResultados($resultados); 

        }

        $app->setBusqueda($busqueda);

        $res = $app->resuelve('/mostrar_busqueda.php');

        return $res;
    }
}
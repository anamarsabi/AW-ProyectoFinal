<?php

namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;


class Vista{

    protected $contenido = "";
    private static $instancia;

    /**
     * Devuele una instancia de {@see Vista}.
     *
     * @return Applicacion Obtiene la única instancia de la <code>Vista</code>
     */
    public static function getInstance()
    {
        if (!self::$instancia instanceof self) {
            self::$instancia = new static();
        }
        return self::$instancia;
    }


   

    public function get_contenido() {
        return $this->contenido;
    }

    public function carga_contenido($paths) {
        $app = Aplicacion::getInstance();
        
        foreach ($paths as $p) {
            $this->contenido .= $app->getHTML($p);
        }
       
    }
}
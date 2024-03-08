<?php

class Tarea {

    private $descripcion;
    
    public function __construct($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

}
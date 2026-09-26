<?php

require_once __DIR__ . "/Entrada.php";
require_once __DIR__ . "/Salida.php";

class ReporteBalance
{
    private $entrada;
    private $salida;

    public function __construct()
    {
        $this->entrada = new Entrada();
        $this->salida = new Salida();
    }

    public function obtenerTotalEntradas()
    {
        return $this->entrada->obtenerTotal();
    }

    public function obtenerTotalSalidas()
    {
        return $this->salida->obtenerTotal();
    }

    public function obtenerBalance()
    {
        $totalEntradas = $this->obtenerTotalEntradas();
        $totalSalidas = $this->obtenerTotalSalidas();

        return $totalEntradas - $totalSalidas;
    }
}

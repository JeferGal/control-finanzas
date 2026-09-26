<?php

require_once __DIR__ . "/../config/Database.php";

class Entrada
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Registrar una nueva entrada
    public function registrar($tipo, $monto, $fecha, $factura)
    {
        $sql = "INSERT INTO entradas (tipo, monto, fecha, factura)
                VALUES (:tipo, :monto, :fecha, :factura)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":monto", $monto);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":factura", $factura);

        return $stmt->execute();
    }

    // Obtener todas las entradas
    public function obtenerTodas()
    {
        $sql = "SELECT * FROM entradas ORDER BY fecha DESC, id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener una entrada por su ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM entradas WHERE id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Obtener el total de entradas
    public function obtenerTotal()
    {
        $sql = "SELECT COALESCE(SUM(monto), 0) AS total
                FROM entradas";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch();

        return $resultado["total"];
    }
}
<?php

require_once __DIR__ . "/../config/Database.php";

class Salida
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Registrar una nueva salida
    public function registrar($tipo, $monto, $fecha, $factura)
    {
        $sql = "INSERT INTO salidas (tipo, monto, fecha, factura)
                VALUES (:tipo, :monto, :fecha, :factura)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":monto", $monto);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":factura", $factura);

        return $stmt->execute();
    }

    // Obtener todas las salidas
    public function obtenerTodas()
    {
        $sql = "SELECT * FROM salidas ORDER BY fecha DESC, id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Obtener una salida por su ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM salidas WHERE id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Obtener el total de salidas
    public function obtenerTotal()
    {
        $sql = "SELECT COALESCE(SUM(monto), 0) AS total
                FROM salidas";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch();

        return $resultado["total"];
    }
}

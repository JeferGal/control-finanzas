<?php

require_once __DIR__ . "/../config/Database.php";

class Login
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    public function iniciarSesion($correo, $password)
    {
        $sql = "SELECT id, nombre, correo, password 
                FROM usuarios 
                WHERE correo = :correo 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();

        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario["password"])) {

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];
            $_SESSION["usuario_correo"] = $usuario["correo"];

            return true;
        }

        return false;
    }

    public function cerrarSesion()
    {
        session_unset();
        session_destroy();
    }

    public function estaAutenticado()
    {
        return isset($_SESSION["usuario_id"]);
    }
}
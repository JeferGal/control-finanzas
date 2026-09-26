<?php

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../login.php");
    exit;
}

$nombreUsuario = $_SESSION["usuario_nombre"] ?? "Usuario";

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo $tituloPagina ?? "Control de Finanzas"; ?>
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body>

<div class="app">

    <header class="topbar">

        <div class="topbar-title">

            <h1>Control de Finanzas</h1>

        </div>

        <div class="topbar-user">

            Bienvenido,
            <strong>
                <?php echo htmlspecialchars($nombreUsuario); ?>
            </strong>

        </div>

    </header>

    <div class="layout">

<?php

session_start();

require_once "classes/Entrada.php";

// Verificar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Crear objeto Entrada
$entrada = new Entrada();

// Obtener todas las entradas
$entradas = $entrada->obtenerTodas();

$tituloPagina = "Ver entradas";

require_once "includes/header.php";
require_once "includes/sidebar.php";

?>

<div class="card">

<h2>Entradas registradas</h2>
    <p>Consulta todas las entradas financieras registradas.</p>

    <?php if (empty($entradas)): ?>

        <p>No hay entradas registradas.</p>

    <?php else: ?>

        <div class="table-container">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo de entrada</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Factura</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($entradas as $item): ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($item["id"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($item["tipo"]); ?>
                            </td>

                            <td>
                                $<?php echo number_format((float)$item["monto"], 2); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($item["fecha"]); ?>
                            </td>

                            <td>

                                <?php if (!empty($item["factura"])): ?>

                                    <a
                                        href="<?php echo htmlspecialchars($item["factura"]); ?>"
                                        target="_blank"
                                        class="btn"
                                    >
                                        Ver factura
                                    </a>

                                <?php else: ?>

                                    Sin factura

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<?php require_once "includes/footer.php"; ?>
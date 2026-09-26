<?php

session_start();

require_once "classes/ReporteBalance.php";
require_once "classes/Entrada.php";
require_once "classes/Salida.php";

// Verificar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Crear objeto ReporteBalance
$reporte = new ReporteBalance();

// Obtener totales
$totalEntradas = $reporte->obtenerTotalEntradas();
$totalSalidas = $reporte->obtenerTotalSalidas();
$balance = $reporte->obtenerBalance();

// Obtener registros
$entrada = new Entrada();
$salida = new Salida();

$entradas = $entrada->obtenerTodas();
$salidas = $salida->obtenerTodas();

$tituloPagina = "Balance financiero";

require_once "includes/header.php";
require_once "includes/sidebar.php";

?>

<div class="card">

    <!-- ENCABEZADO -->

    <h2>Balance financiero</h2>

    <p>
        Resumen general de las entradas y salidas registradas.
    </p>

    <br>


    <!-- TOTAL DE ENTRADAS -->

    <h3>Total de entradas</h3>

    <p class="balance-amount">
        $<?php echo number_format((float)$totalEntradas, 2); ?>
    </p>

    <br>


    <!-- TABLA DE ENTRADAS -->

    <h3>Entradas registradas</h3>

    <p>
        Historial de ingresos financieros.
    </p>

    <br>

    <?php if (empty($entradas)): ?>

        <p>No hay entradas registradas.</p>

    <?php else: ?>

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
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


    <br>
    <hr>
    <br>


    <!-- TOTAL DE SALIDAS -->

    <h3>Total de salidas</h3>

    <p class="balance-amount">
        $<?php echo number_format((float)$totalSalidas, 2); ?>
    </p>

    <br>


    <!-- TABLA DE SALIDAS -->

    <h3>Salidas registradas</h3>

    <p>
        Historial de gastos financieros.
    </p>

    <br>

    <?php if (empty($salidas)): ?>

        <p>No hay salidas registradas.</p>

    <?php else: ?>

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Factura</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($salidas as $item): ?>

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


    <br>
    <hr>
    <br>


    <!-- BALANCE -->

    <h3>Balance actual</h3>

    <p class="balance-amount">
        $<?php echo number_format((float)$balance, 2); ?>
    </p>


    <br>


    <!-- GRÁFICA -->

    <h3>Distribución de entradas y salidas</h3>

    <div class="chart-container">

        <canvas id="graficaBalance"></canvas>

    </div>


    <br>
    <hr>
    <br>


    <!-- REPORTE -->

    <h3>Reporte</h3>

    <p>
        Genera un documento PDF con el resumen financiero.
    </p>

    <br>

    <a href="exportar_pdf.php" class="btn">
        📄 Exportar balance a PDF
    </a>

</div>


<!-- CHART.JS -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const totalEntradas =
        <?php echo (float)$totalEntradas; ?>;

    const totalSalidas =
        <?php echo (float)$totalSalidas; ?>;

    const ctx =
        document.getElementById("graficaBalance");


    new Chart(ctx, {

        type: "pie",

        data: {

            labels: [
                "Entradas",
                "Salidas"
            ],

            datasets: [

                {
                    data: [
                        totalEntradas,
                        totalSalidas
                    ]
                }

            ]

        },

        options: {

            responsive: true,

            plugins: {

                legend: {
                    position: "bottom"
                },

                title: {

                    display: true,

                    text: "Entradas vs. Salidas"

                }

            }

        }

    });

</script>


<?php require_once "includes/footer.php"; ?>
<?php

session_start();

require_once "classes/ReporteBalance.php";
require_once "classes/Entrada.php";
require_once "classes/Salida.php";
require_once __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;

// Verificar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Crear objetos
$reporte = new ReporteBalance();

$entrada = new Entrada();
$salida = new Salida();

// Obtener información
$totalEntradas = (float) $reporte->obtenerTotalEntradas();
$totalSalidas = (float) $reporte->obtenerTotalSalidas();
$balance = (float) $reporte->obtenerBalance();

$entradas = $entrada->obtenerTodas();
$salidas = $salida->obtenerTodas();


// =====================================================
// CALCULAR PORCENTAJES PARA LA GRÁFICA
// =====================================================

$totalGeneral = $totalEntradas + $totalSalidas;

if ($totalGeneral > 0) {

    $porcentajeEntradas = ($totalEntradas / $totalGeneral) * 100;
    $porcentajeSalidas = ($totalSalidas / $totalGeneral) * 100;

} else {

    $porcentajeEntradas = 0;
    $porcentajeSalidas = 0;

}


// =====================================================
// CALCULAR ÁNGULOS DE LA GRÁFICA
// =====================================================

$anguloEntradas = ($porcentajeEntradas / 100) * 360;

$anguloSalidas = ($porcentajeSalidas / 100) * 360;


// =====================================================
// FUNCIÓN PARA CREAR UNA PORCIÓN DEL GRÁFICO
// =====================================================

function crearSector($anguloInicio, $anguloFin, $radio = 100)
{
    $centroX = 125;
    $centroY = 125;

    $x1 = $centroX + $radio * cos(deg2rad($anguloInicio - 90));
    $y1 = $centroY + $radio * sin(deg2rad($anguloInicio - 90));

    $x2 = $centroX + $radio * cos(deg2rad($anguloFin - 90));
    $y2 = $centroY + $radio * sin(deg2rad($anguloFin - 90));

    $largeArcFlag = ($anguloFin - $anguloInicio) > 180 ? 1 : 0;

    return "
        M {$centroX},{$centroY}
        L {$x1},{$y1}
        A {$radio},{$radio} 0 {$largeArcFlag},1 {$x2},{$y2}
        Z
    ";
}


// Sector de entradas
$sectorEntradas = crearSector(
    0,
    $anguloEntradas
);


// Sector de salidas
$sectorSalidas = crearSector(
    $anguloEntradas,
    360
);


// =====================================================
// HTML DEL PDF
// =====================================================

$html = '
<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<style>

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #333;
        margin: 30px;
    }

    .titulo {
        text-align: center;
        margin-bottom: 25px;
    }

    .titulo h1 {
        font-size: 22px;
        margin-bottom: 5px;
    }

    .titulo p {
        color: #666;
    }

    .resumen {
        width: 100%;
        margin-bottom: 25px;
    }

    .resumen td {
        width: 33.33%;
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .resumen-titulo {
        font-weight: bold;
        font-size: 12px;
    }

    .monto {
        font-size: 17px;
        font-weight: bold;
        margin-top: 8px;
    }

    .seccion {
        margin-top: 25px;
        margin-bottom: 10px;
    }

    .seccion h2 {
        font-size: 16px;
        border-bottom: 1px solid #333;
        padding-bottom: 5px;
    }

    .grafica {
        text-align: center;
        margin-top: 15px;
        margin-bottom: 20px;
    }

    .leyenda {
        text-align: center;
        margin-top: 10px;
    }

    .leyenda span {
        margin: 0 10px;
    }

    table.datos {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table.datos th {
        background-color: #eeeeee;
        font-weight: bold;
    }

    table.datos th,
    table.datos td {
        border: 1px solid #cccccc;
        padding: 7px;
        text-align: left;
    }

    .pie {
        margin-top: 30px;
        text-align: center;
        font-size: 9px;
        color: #777;
    }

</style>

</head>

<body>


<div class="titulo">

    <h1>CONTROL DE FINANZAS</h1>

    <p>Reporte general de balance financiero</p>

    <p>
        Fecha de generación:
        ' . date("d/m/Y H:i") . '
    </p>

</div>


<!-- ================================================= -->
<!-- RESUMEN -->
<!-- ================================================= -->

<table class="resumen">

    <tr>

        <td>

            <div class="resumen-titulo">
                TOTAL DE ENTRADAS
            </div>

            <div class="monto">
                $' . number_format($totalEntradas, 2) . '
            </div>

        </td>


        <td>

            <div class="resumen-titulo">
                TOTAL DE SALIDAS
            </div>

            <div class="monto">
                $' . number_format($totalSalidas, 2) . '
            </div>

        </td>


        <td>

            <div class="resumen-titulo">
                BALANCE
            </div>

            <div class="monto">
                $' . number_format($balance, 2) . '
            </div>

        </td>

    </tr>

</table>


<!-- ================================================= -->
<!-- GRÁFICA -->
<!-- ================================================= -->

<div class="seccion">

    <h2>Distribución de entradas y salidas</h2>

</div>


<div class="grafica">

    <svg
        width="250"
        height="250"
        viewBox="0 0 250 250"
        xmlns="http://www.w3.org/2000/svg"
    >';


// Si existen datos, dibujamos los sectores
if ($totalGeneral > 0) {

    $html .= '

        <path
            d="' . $sectorEntradas . '"
            fill="#2563eb"
        />

        <path
            d="' . $sectorSalidas . '"
            fill="#dc2626"
        />

    ';

} else {

    $html .= '

        <circle
            cx="125"
            cy="125"
            r="100"
            fill="#d1d5db"
        />

    ';

}


$html .= '

    </svg>


    <div class="leyenda">

        <span>
            ■ Entradas:
            $' . number_format($totalEntradas, 2) . '
            (' . number_format($porcentajeEntradas, 1) . '%)
        </span>

        <span>
            ■ Salidas:
            $' . number_format($totalSalidas, 2) . '
            (' . number_format($porcentajeSalidas, 1) . '%)
        </span>

    </div>

</div>


<!-- ================================================= -->
<!-- TABLA DE ENTRADAS -->
<!-- ================================================= -->

<div class="seccion">

    <h2>Entradas registradas</h2>

</div>
';


// =====================================================
// TABLA DE ENTRADAS
// =====================================================

if (!empty($entradas)) {

    $html .= '

    <table class="datos">

        <thead>

            <tr>

                <th>ID</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Fecha</th>

            </tr>

        </thead>

        <tbody>
    ';


    foreach ($entradas as $item) {

        $html .= '

            <tr>

                <td>
                    ' . htmlspecialchars($item["id"]) . '
                </td>

                <td>
                    ' . htmlspecialchars($item["tipo"]) . '
                </td>

                <td>
                    $' . number_format((float)$item["monto"], 2) . '
                </td>

                <td>
                    ' . htmlspecialchars($item["fecha"]) . '
                </td>

            </tr>

        ';

    }


    $html .= '

        </tbody>

    </table>

    ';

} else {

    $html .= '

        <p>No hay entradas registradas.</p>

    ';

}


// =====================================================
// TABLA DE SALIDAS
// =====================================================

$html .= '

<div class="seccion">

    <h2>Salidas registradas</h2>

</div>
';


if (!empty($salidas)) {

    $html .= '

    <table class="datos">

        <thead>

            <tr>

                <th>ID</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Fecha</th>

            </tr>

        </thead>

        <tbody>
    ';


    foreach ($salidas as $item) {

        $html .= '

            <tr>

                <td>
                    ' . htmlspecialchars($item["id"]) . '
                </td>

                <td>
                    ' . htmlspecialchars($item["tipo"]) . '
                </td>

                <td>
                    $' . number_format((float)$item["monto"], 2) . '
                </td>

                <td>
                    ' . htmlspecialchars($item["fecha"]) . '
                </td>

            </tr>

        ';

    }


    $html .= '

        </tbody>

    </table>

    ';

} else {

    $html .= '

        <p>No hay salidas registradas.</p>

    ';

}


// =====================================================
// PIE DEL DOCUMENTO
// =====================================================

$html .= '

<div class="pie">

    Documento generado automáticamente por
    Control de Finanzas.

</div>


</body>

</html>
';


// =====================================================
// GENERAR PDF
// =====================================================

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper("A4", "portrait");

$dompdf->render();

$dompdf->stream(
    "reporte_balance.pdf",
    [
        "Attachment" => true
    ]
);

exit;
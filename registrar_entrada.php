<?php

session_start();

require_once "classes/Entrada.php";
require_once __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;

// Verificar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo = trim($_POST["tipo"] ?? "");
    $monto = trim($_POST["monto"] ?? "");
    $fecha = trim($_POST["fecha"] ?? "");

    // Validar campos
    if (empty($tipo) || empty($monto) || empty($fecha)) {

        $mensaje = "Por favor, completa todos los campos.";
        $tipoMensaje = "error";

    } elseif (!is_numeric($monto) || $monto <= 0) {

        $mensaje = "El monto debe ser un número mayor que cero.";
        $tipoMensaje = "error";

    } else {

        // -----------------------------------------
        // 1. Crear nombre único para la factura
        // -----------------------------------------

        $nombreFactura = "factura_" . date("Ymd_His") . "_" . uniqid() . ".pdf";

        // Ruta física donde se guardará el PDF
        $carpetaFactura = __DIR__ . "/uploads/entradas/";

        $rutaFisica = $carpetaFactura . $nombreFactura;

        // Ruta que se guardará en MySQL
        $rutaFactura = "uploads/entradas/" . $nombreFactura;


        // -----------------------------------------
        // 2. Crear contenido de la factura
        // -----------------------------------------

        $htmlFactura = '
        <!DOCTYPE html>
        <html lang="es">

        <head>

            <meta charset="UTF-8">

            <style>

                body {
                    font-family: Arial, sans-serif;
                    margin: 40px;
                }

                .encabezado {
                    text-align: center;
                    margin-bottom: 30px;
                }

                .titulo {
                    font-size: 24px;
                    font-weight: bold;
                }

                .factura {
                    border: 1px solid #333;
                    padding: 20px;
                }

                .dato {
                    margin-bottom: 12px;
                }

                .total {
                    margin-top: 25px;
                    padding-top: 15px;
                    border-top: 2px solid #333;
                    font-size: 20px;
                    font-weight: bold;
                }

                .pie {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 12px;
                }

            </style>

        </head>

        <body>

            <div class="encabezado">

                <div class="titulo">
                    CONTROL DE FINANZAS
                </div>

                <div>
                    FACTURA DE ENTRADA
                </div>

            </div>

            <div class="factura">

                <div class="dato">
                    <strong>Tipo de entrada:</strong>
                    ' . htmlspecialchars($tipo) . '
                </div>

                <div class="dato">
                    <strong>Fecha:</strong>
                    ' . htmlspecialchars($fecha) . '
                </div>

                <div class="total">
                    Monto: $' . number_format((float)$monto, 2) . '
                </div>

            </div>

            <div class="pie">
                Documento generado automáticamente por Control de Finanzas.
            </div>

        </body>

        </html>
        ';


        // -----------------------------------------
        // 3. Generar PDF
        // -----------------------------------------

        $dompdf = new Dompdf();

        $dompdf->loadHtml($htmlFactura);

        $dompdf->setPaper("A4", "portrait");

        $dompdf->render();

        $pdf = $dompdf->output();


        // -----------------------------------------
        // 4. Guardar el PDF en el servidor
        // -----------------------------------------

        if (file_put_contents($rutaFisica, $pdf) === false) {

            $mensaje = "No se pudo guardar la factura.";
            $tipoMensaje = "error";

        } else {

            // -----------------------------------------
            // 5. Registrar entrada en MySQL
            // -----------------------------------------

            $entrada = new Entrada();

            if ($entrada->registrar(
                $tipo,
                $monto,
                $fecha,
                $rutaFactura
            )) {

                $mensaje = "Entrada registrada correctamente. Factura generada.";
                $tipoMensaje = "exito";

            } else {

                // Si MySQL falla, eliminar el PDF
                if (file_exists($rutaFisica)) {
                    unlink($rutaFisica);
                }

                $mensaje = "No se pudo registrar la entrada en la base de datos.";
                $tipoMensaje = "error";
            }
        }
    }
}

$tituloPagina = "Registrar entrada";

require_once "includes/header.php";
require_once "includes/sidebar.php";

?>

<div class="card">

<h2>Registrar entrada</h2>

    <p>
        Registra un nuevo ingreso financiero.
    </p>


    <?php if (!empty($mensaje)): ?>

        <p>
            <?php echo htmlspecialchars($mensaje); ?>
        </p>

        <br>

    <?php endif; ?>


    <form method="POST">

        <div>

            <label for="tipo">
                Tipo de entrada:
            </label>

            <br><br>

            <input
                type="text"
                id="tipo"
                name="tipo"
                placeholder="Ejemplo: Venta"
                required
            >

        </div>

        <br>


        <div>

            <label for="monto">
                Monto:
            </label>

            <br><br>

            <input
                type="number"
                id="monto"
                name="monto"
                step="0.01"
                min="0.01"
                placeholder="Ejemplo: 150.00"
                required
            >

        </div>

        <br>


        <div>

            <label for="fecha">
                Fecha:
            </label>

            <br><br>

            <input
                type="date"
                id="fecha"
                name="fecha"
                required
            >

        </div>

        <br>


        <div>

            <label>
                Factura:
            </label>

            <p>
                La factura será creada automáticamente
                por el sistema en formato PDF.
            </p>

        </div>

        <br>


        <button type="submit">
            Registrar entrada
        </button>

    </form>

</div>

<?php

require_once "includes/footer.php";

?>

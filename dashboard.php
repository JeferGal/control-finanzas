<?php

session_start();

require_once "classes/Login.php";

$login = new Login();

if (!$login->estaAutenticado()) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Dashboard";

require_once "includes/header.php";
require_once "includes/sidebar.php";

?>

<main class="main-content">

    <div class="page-title">

        <h2>Dashboard</h2>

        <p>
            Bienvenido,
            <strong>
                <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>
            </strong>
        </p>

    </div>

    <div class="card">

        <h3>Panel principal</h3>

        <p>
            Desde este panel puedes administrar las entradas,
            salidas y el balance financiero.
        </p>

    </div>

</main>

<?php

require_once "includes/footer.php";

?>
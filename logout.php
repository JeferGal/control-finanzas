<?php

session_start();

require_once "classes/Login.php";

$login = new Login();

$login->cerrarSesion();

header("Location: login.php");
exit;
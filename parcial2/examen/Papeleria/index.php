<?php
session_start();

if (!isset($_SESSION['user_rol'])) {
    header("Location: inicio.php");
    exit;
}

if ($_SESSION['user_rol'] == 'admin') {
    echo '<a href="gestion_usuarios.php">Gestionar Usuarios</a>';
    header("Location: gestion_productos.php");
} else {
    header("Location: compras.php");
}
?>

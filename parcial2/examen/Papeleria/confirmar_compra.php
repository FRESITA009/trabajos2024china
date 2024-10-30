<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirige al login si no ha iniciado sesión
    exit();
}

// Verifica si el carrito existe y no está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>No hay productos en el carrito.</p>";
    echo '<form action="login.php"><button type="submit">Volver al Login</button></form>';
    exit();
}

// Incluir la conexión a la base de datos
include 'conexion.php';

// Inicializar total
$total = 0;

// Procesar la compra
foreach ($_SESSION['carrito'] as $producto_id => $item) {
    // Obtener el producto de la base de datos
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch();

    if ($producto) {
        // Calcular subtotal
        $subtotal = $producto['precio'] * $item['cantidad'];
        $total += $subtotal;

        // Verificar stock suficiente
        if ($producto['stock'] >= $item['cantidad']) {
            // Reducir la existencias en la base de datos
            $nuevo_stock = $producto['stock'] - $item['cantidad'];
            $update_stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
            $update_stmt->execute([$nuevo_stock, $producto_id]);
        } else {
            echo "<p>No hay suficiente Existencias para el producto: " . htmlspecialchars($producto['nombre']) . ".</p>";
            continue; // Continúa al siguiente producto
        }
    }
}

// Vaciar el carrito después de la compra
unset($_SESSION['carrito']);

// Mostrar el total y un mensaje de agradecimiento
echo "<p>Gracias por tu compra. Tu pedido ha sido confirmado. Total: " . htmlspecialchars($total) . "</p>";

// Botón para volver al inicio
echo '<form action="inicio.php"><button type="submit">Volver al Login</button></form>';


?>

<?php  
session_start(); // Inicia la sesión para el usuario  

// Verifica si el usuario ha iniciado sesión   
if (!isset($_SESSION['user_id'])) {  
    header("Location: login.php"); // Redirige al inicio de sesión si no ha iniciado sesión  
    exit(); // Termina el script para prevenir acceso no autorizado  
}  

// Verifica si se ha enviado una solicitud POST  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    
    // Incluir la conexión a la base de datos  
    include 'conexion.php'; // Conecta con la base de datos  

    $producto_id = $_POST['producto_id']; // Obtiene el ID del producto desde el formulario  
    $cantidad = $_POST['cantidad']; // Obtiene la cantidad solicitada desde el formulario  

    // Obtiene el producto de la base de datos usando su ID  
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?"); // Prepara la consulta para obtener el producto  
    $stmt->execute([$producto_id]); // Ejecuta la consulta con el ID del producto  
    $producto = $stmt->fetch(); // Almacena el resultado en la variable $producto  

    // Verifica si hay suficientes existencias  
    if ($producto && $producto['stock'] >= $cantidad) {  
        // Si hay suficientes existencias, reduce la cantidad disponible  
        $nuevo_stock = $producto['stock'] - $cantidad; // Calcula el nuevo stock  
        $update_stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?"); // Prepara la consulta para actualizar el stock  
        $update_stmt->execute([$nuevo_stock, $producto_id]); // Ejecuta la actualización en la base de datos  

        // Mensaje de éxito al usuario  
        echo "<p>Has comprado " . htmlspecialchars($cantidad) . " unidades de " . htmlspecialchars($producto['nombre']) . ".</p>";  
        echo "<p>Stock restante: " . htmlspecialchars($nuevo_stock) . " unidades.</p>";  

        // Botón para volver a la página de ventas  
        echo '<form action="ventas.php"><button type="submit">Volver a la Página de Ventas</button></form>';  
    } else {  
        // Mensaje de error si no hay suficientes existencias del producto  
        echo "<p>No hay suficiente stock para el producto seleccionado.</p>";  
        echo '<form action="ventas.php"><button type="submit">Volver a la Página de Ventas</button></form>';  
    }  
} else {  
    // Mensaje en caso de que la solicitud no sea válida  
    echo "<p>Solicitud no válida.</p>";  
}  

?>

<link rel="stylesheet" href="css/estilo.css"> 
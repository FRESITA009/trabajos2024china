<?php  
include 'conexion.php'; // Incluye el archivo que establece la conexión a la base de datos.  
session_start(); // Inicia la sesión para manejar variables de sesión.  

// Verifica si el usuario ha iniciado sesión  
if (!isset($_SESSION['user_id'])) {  
    header("Location: inicio.php"); // Redirige al login si no ha iniciado sesión.  
    exit();  
}  

// Obtener productos de la base de datos  
$stmt = $conn->query("SELECT * FROM productos"); // Ejecuta la consulta para obtener todos los productos.  
$productos = $stmt->fetchAll(); // Almacena los resultados en una variable.  
?>  

<!DOCTYPE html>  
<html lang="es">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Página de Ventas</title>  
    <link rel="stylesheet" href="css/estilo.css">   
</head>  
<body>  
    <div class="container">  
        <h1>Bienvenido a la Página de Ventas</h1>  
        <p>Aquí puedes ver y comprar productos.</p>  

        <h2>Lista de Productos</h2>  
        <table>  
            <tr>  
                <th>Nombre</th>  

                <th>Precio</th>

                <th>No. de Existencias</th> 
                 
                <th>Acciones</th>  
            </tr>  
            <?php foreach ($productos as $producto): ?>  
                <tr>  
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>   
                    <td><?php echo htmlspecialchars($producto['precio']); ?></td> 
                    <td><?php echo htmlspecialchars($producto['stock']); ?></td> 
                    <td>  
                        <form method="post" action="carrito.php"> 
                            <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>"> 
                            <input type="number" name="cantidad" min="1" max="<?php echo $producto['stock']; ?>" required> 
                            <button type="submit">Comprar</button>  
                        </form>  
                    </td>  
                </tr>  
            <?php endforeach; ?>  
        </table>  
        
        <!-- Botón para volver al  -->  
        <form action="inicio.php" method="get">  
            <button type="submit">Volver al inicio</button> 
    </div>  
</body>  
</html>

<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nombre, $email, $password, $rol]);

    echo "Usuario registrado correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/estilo.css"> 
</head>
<body>
    <div class="container">
        <h2>Registrar Usuario</h2>
        <form method="post" action="">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="rol">
                <option value="cliente">Cliente</option>
                <option value="admin">Admin</option>
            </select>
            <br>
            <button type="submit">Registrar</button>
        </form>

        <!-- Botón para volver a la página de inicio -->
        <p>¿Ya tienes una cuenta?</p>
        <a href="inicio.php">
            <button class="inicio-button">Volver al inicio</button>
        </a>
    </div>
</body>
</html>

<?php  
include 'conexion.php'; // Conectar a la base de datos  
session_start(); // Iniciar la sesión  

// Comprobar si la solicitud es de tipo POST  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    $email = $_POST['email']; // Obtener el email del formulario  
    $password = $_POST['password']; // Obtener la contraseña del formulario  

    // Consulta para buscar el usuario según el email  
    $sql = "SELECT * FROM usuarios WHERE email = ?";  
    $stmt = $conn->prepare($sql); // Preparar la sentencia SQL  
    $stmt->execute([$email]); // Ejecutar la consulta  
    $usuario = $stmt->fetch(); // Obtener los resultados  

    // Verificar si el usuario existe y la contraseña es correcta  
    if ($usuario && password_verify($password, $usuario['password'])) {  
        // Iniciar sesión y establecer variables de sesión  
        $_SESSION['user_id'] = $usuario['id'];  
        $_SESSION['user_rol'] = $usuario['rol'];  
        
        // Redirigir según el rol del usuario  
        if ($usuario['rol'] == 'admin') {  
            header("Location: gestion_productos.php"); // Redirigir a la página de gestión de productos para administradores  
        } else {  
            header("Location: ventas.php"); // Redirigir a la página de ventas para clientes  
        }  
        exit(); // Finalizar el script  
    } else {  
        $error = "Email o contraseña incorrectos."; // Mensaje de error en caso de fallo  
    }  
}  
?>  

<!DOCTYPE html>  
<html lang="es">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Iniciar Sesión</title>  
    <link rel="stylesheet" href="css/estilo.css"> 
</head>  
<body>  
    <div class="container">  
        <h1>PAPELERIA CETis 84</h2>
        <h1>Iniciar Sesión</h1>  

        <?php if (isset($error)) { echo "<p>$error</p>"; } ?> 
        <form method="post" action="">  
            <input type="email" name="email" placeholder="Email" required> 
            <input type="password" name="password" placeholder="Contraseña" required> 
            <br>  
            <button type="submit">Iniciar Sesión</button> 
        </form>  
        <p>No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p> 
        <footer><i>
        Elaboro: Gutierrez Martinez Kimberly <br>
        Sánchez Basurto Alexa<br>
        Fecha: 30/10/2024
    </i></footer>
    </div>
    </div>  

</body>  
</html>
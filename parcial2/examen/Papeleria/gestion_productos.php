<?php
include 'conexion.php';
session_start();

// Verifica que el usuario sea administrador
if ($_SESSION['user_rol'] != 'admin') {
    echo "No tienes permiso para acceder a esta página.";
    exit;
}

// Gestión de productos y usuarios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar_producto'])) {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        $sql = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $precio, $stock]);
        echo "Producto añadido correctamente.";
    } elseif (isset($_POST['eliminar_producto'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        echo "Producto eliminado correctamente.";
    } elseif (isset($_POST['editar_usuario'])) {
        $id = $_POST['id'];
        $rol = $_POST['rol'];

        $sql = "UPDATE usuarios SET rol = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$rol, $id]);
        echo "Rol de usuario actualizado correctamente.";
    } elseif (isset($_POST['eliminar_usuario'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        echo "Usuario eliminado correctamente.";
    } elseif (isset($_POST['registrar_usuario'])) {
        $nombre = $_POST['nombre_usuario'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        $rol = $_POST['rol'];

        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $email, $password, $rol]);
        echo "Usuario registrado correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Indice de Productos</h1>

        
        <section>
            <h2>Agregar Producto</h2>
            <form method="post" action="">
                <input type="text" name="nombre" placeholder="Nombre del producto" required>
                <input type="number" step="0.01" name="precio" placeholder="Precio" required>
                <input type="number" name="stock" placeholder="Stock" required>
                <button type="submit" name="agregar_producto">Agregar Producto</button>
            </form>
            
            <h2>Lista de Productos</h2>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>No. de Existencias </th>
                    <th>Acciones</th>
                </tr>
                <?php
                $stmt = $conn->query("SELECT * FROM productos");
                while ($producto = $stmt->fetch()) {
                    echo "<tr>
                            <td>{$producto['nombre']}</td>
                            <td>{$producto['precio']}</td>
                            <td>{$producto['stock']}</td>
                            <td>
                                <form method='post' action='' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$producto['id']}'>
                                    <button type='submit' name='eliminar_producto'>Eliminar</button>
                                </form>
                            </td>
                        </tr>";
                }
                ?>
            </table>
        </section>

        <!-- Gestión de Usuarios -->
        <section>
            <h2>Gestión de Usuarios</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
                <?php
                $stmt = $conn->query("SELECT * FROM usuarios");
                while ($usuario = $stmt->fetch()) {
                    echo "<tr>
                            <td>{$usuario['id']}</td>
                            <td>{$usuario['nombre']}</td>
                            <td>{$usuario['email']}</td>
                            <td>
                                <form method='post' action=''>
                                    <input type='hidden' name='id' value='{$usuario['id']}'>
                                    <select name='rol'>
                                        <option value='cliente' " . ($usuario['rol'] == 'cliente' ? 'selected' : '') . ">Cliente</option>
                                        <option value='admin' " . ($usuario['rol'] == 'admin' ? 'selected' : '') . ">Admin</option>
                                    </select>
                                    <button type='submit' name='editar_usuario'>Actualizar</button>
                                </form>
                            </td>
                            <td>
                                <form method='post' action=''>
                                    <input type='hidden' name='id' value='{$usuario['id']}'>
                                    <button type='submit' name='eliminar_usuario'>Eliminar</button>
                                </form>
                            </td>
                        </tr>";
                }
                ?>
            </table>

            <!-- Formulario para Registrar Nuevo Usuario -->
            <h2>Registrar Nuevo Usuario</h2>
            <form method="post" action="">
                <input type="text" name="nombre_usuario" placeholder="Nombre del usuario" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <select name="rol" required>
                    <option value="cliente">Cliente</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="registrar_usuario">Registrar Usuario</button>
                <a href="inicio.php">volver al inicio</a>
            </form>
        </section>
    </div>
</body>
</html>

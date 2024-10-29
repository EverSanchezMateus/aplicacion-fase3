<?php
session_start();
include('includes/db.php'); // Asegúrate de que este archivo esté correctamente incluido

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos del formulario
    $nombre = $_POST['nombre']; // Cambiado de nombre_usuario a nombre
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol']; // Campo para el rol

    // Hashea la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepara la consulta SQL para insertar el nuevo usuario
    $query = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nombre, $email, $hashed_password, $rol);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Registro exitoso. Puedes iniciar sesión.";
        header("Location: login.php"); // Redirige al usuario a la página de inicio de sesión
        exit();
    } else {
        echo "Error: " . $stmt->error; // Muestra el error si hay un problema
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="css/styles_registro.css"> <!-- Enlace al archivo CSS para estilos -->
</head>
<body>
    <h2>Registrar Usuario</h2>
    <form action="register.php" method="POST">
        <label for="nombre">Nombre:</label> <!-- Cambiado de nombre_usuario a nombre -->
        <input type="text" id="nombre" name="nombre" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="rol">Rol:</label>
        <select id="rol" name="rol" required>
            <option value="cliente">Cliente</option>
            <option value="admin">Admin</option>
        </select>

        <input type="submit" value="Registrar">
    </form>
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html>

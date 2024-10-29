<?php
session_start(); // Asegúrate de que esta línea esté al inicio
include('includes/db.php');
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Establecer las variables de sesión
            $_SESSION['email'] = $user['email']; // Asegúrate de que 'email' está correctamente establecido
            $_SESSION['rol'] = $user['rol']; // Si deseas usar el rol más adelante
            header("Location: productos.php"); // Redirigir a la página de productos
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.');</script>"; // Mensaje de error
        }
    } else {
        echo "<script>alert('Usuario no encontrado.');</script>"; // Mensaje de error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Ferretería XYZ</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Estilos generales -->
    <link rel="stylesheet" href="css/styles_registro.css"> <!-- Estilos para el formulario -->
</head>
<body>
    <header>
        <h1>Iniciar Sesión</h1>
    </header>

    <main>
        <form action="login.php" method="POST" class="form-container">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Iniciar Sesión" class="submit-button">
        </form>
        <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
    </main>

    <footer>
        <p>© 2024 Ferretería XYZ. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

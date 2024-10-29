<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aquí puedes agregar la lógica para manejar el mensaje, como enviarlo a tu correo electrónico o guardarlo en la base de datos.
    // Por simplicidad, solo se mostrará un mensaje en el alert.
    echo "<script>alert('¡Mensaje enviado!');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Ferretería XYZ</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Estilos generales -->
    <link rel="stylesheet" href="css/styles_contacto.css"> <!-- Estilos específicos para el formulario de contacto -->

</head>
<body>
    <header>
        <h1>Bienvenido a Ferretería XYZ</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="carrito.php">Carrito</a></li>
                <?php if (isset($_SESSION['rol'])): ?>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <li><a href="gestion_productos.php">Agregar Producto</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Contacto</h2>
        <form method="post" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" required></textarea>

            <button type="submit">Enviar</button>
        </form>
    </main>

    <footer>
        <p>© 2024 Ferretería XYZ. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

<?php

include('includes/db.php'); // Asegúrate de que este archivo esté correctamente incluido
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ferretería XYZ</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de tener el archivo CSS -->
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

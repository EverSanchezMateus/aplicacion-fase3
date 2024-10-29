<?php
session_start();
include('includes/db.php'); // Asegúrate de que este archivo esté correctamente incluido

// Verifica si hay productos en el carrito en la sesión
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = []; // Inicializa el carrito si no existe
}

// Lógica para calcular el total del carrito
$totalCarrito = 0;

// Lógica para eliminar un producto del carrito
if (isset($_POST['eliminar'])) {
    $nombre = $_POST['nombre'];
    foreach ($_SESSION['carrito'] as $key => $item) {
        if ($item['nombre'] === $nombre) {
            unset($_SESSION['carrito'][$key]);
            break; // Salir del bucle una vez eliminado
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Carrito de Compras - Ferretería XYZ</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Estilos generales -->
    <link rel="stylesheet" href="css/styles_carrito.css"> <!-- Estilos específicos para el carrito -->
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
        <h2>Tu Carrito de Compras</h2>
        <table>
            <thead>
                <tr>
                    <th>Imagen</th> <!-- Nueva columna para la imagen -->
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th> <!-- Nueva columna para las acciones -->
                </tr>
            </thead>
            <tbody>
                <?php if (empty($_SESSION['carrito'])): ?>
                    <tr>
                        <td colspan="6">No hay productos en tu carrito.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($_SESSION['carrito'] as $item): ?>
                        <?php
                        // Verifica que cada item tenga los valores requeridos
                        $nombre = isset($item['nombre']) ? $item['nombre'] : 'Desconocido';
                        $precio = isset($item['precio']) ? $item['precio'] : 0;
                        $cantidad = isset($item['cantidad']) ? $item['cantidad'] : 0;
                        $total = $precio * $cantidad;
                        $imagen = isset($item['imagen']) ? $item['imagen'] : 'ruta/a/imagen/por/defecto.jpg'; // Cambia esto a una imagen por defecto si no hay

                        // Sumar al total general del carrito
                        $totalCarrito += $total;
                        ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($nombre); ?>" class="imagen-producto"></td> <!-- Columna para la imagen -->
                            <td><?php echo htmlspecialchars($nombre); ?></td>
                            <td>$<?php echo number_format($precio, 2); ?></td>
                            <td><?php echo htmlspecialchars($cantidad); ?></td>
                            <td>$<?php echo number_format($total, 2); ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                                    <button type="submit" name="eliminar" class="delete-button">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <h3>Total del Carrito: $<?php echo number_format($totalCarrito, 2); ?></h3>

        <?php if (!empty($_SESSION['carrito'])): ?>
            <form method="post" action="pagar.php">
                <button type="submit" class="pay-button">Pagar</button>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>© 2024 Ferretería XYZ. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

<?php
session_start();
include('includes/db.php'); // Asegúrate de que este archivo esté correctamente incluido

// Lógica para obtener productos de la base de datos
$query = "SELECT * FROM productos"; // Ajusta tu consulta según tu base de datos
$result = mysqli_query($conn, $query);

// Verificar si se ha enviado un formulario para agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $imagen = $_POST['imagen'];

    // Verificar el stock disponible
    $stockQuery = "SELECT stock FROM productos WHERE id = $id";
    $stockResult = mysqli_query($conn, $stockQuery);
    $stockRow = mysqli_fetch_assoc($stockResult);
    
    if ($stockRow) {
        $stockDisponible = $stockRow['stock'];

        // Comprobar si la cantidad solicitada no excede el stock disponible
        if ($cantidad <= $stockDisponible) {
            // Agregar al carrito
            $_SESSION['carrito'][] = [
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'imagen' => $imagen
            ];
            echo "Producto agregado al carrito.";
        } else {
            echo "No hay suficiente stock disponible. Stock disponible: $stockDisponible.";
        }
    } else {
        echo "Producto no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Ferretería XYZ</title>
    <link rel="stylesheet" href="css/productos.css">
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
        <h2>Lista de Productos</h2>
        <div class="product-container">
            <?php while ($producto = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="product-image">
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?></p>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                        <input type="hidden" name="nombre" value="<?php echo $producto['nombre']; ?>">
                        <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
                        <input type="hidden" name="imagen" value="<?php echo $producto['imagen']; ?>">
                        <input type="number" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" class="quantity-input"> <!-- Limitar la cantidad -->
                        <button type="submit" name="agregar" class="add-to-cart-button">Agregar al Carrito</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer>
        <p>© 2024 Ferretería XYZ. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

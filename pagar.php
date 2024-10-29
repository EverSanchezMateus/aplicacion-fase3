<?php
session_start();
include('includes/db.php'); // Asegúrate de que este archivo esté correctamente incluido

// Verifica si hay productos en el carrito en la sesión
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = []; // Inicializa el carrito si no existe
}

// Lógica para calcular el total del carrito
$totalCarrito = 0; // Inicializamos la variable
foreach ($_SESSION['carrito'] as $item) {
    $precio = isset($item['precio']) ? $item['precio'] : 0;
    $cantidad = isset($item['cantidad']) ? $item['cantidad'] : 0;
    $totalCarrito += $precio * $cantidad; // Sumar al total general del carrito
}

// Procesamiento del formulario de pago
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica si todas las claves existen en el arreglo $_POST
    if (isset($_POST['nombre_usuario'], $_POST['direccion'], $_POST['telefono'], $_POST['total'])) {
        $nombreUsuario = $_POST['nombre_usuario'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $total = $_POST['total'];

        // Lógica para restar los productos de la base de datos
        foreach ($_SESSION['carrito'] as $item) {
            $nombreProducto = $item['nombre'];
            $cantidad = $item['cantidad'];
            $query = "UPDATE productos SET stock = stock - $cantidad WHERE nombre = '$nombreProducto'";
            mysqli_query($conn, $query);
        }

        // Limpiar el carrito después del pago
        $_SESSION['carrito'] = [];
        
        echo "Pago realizado con éxito. Gracias por tu compra!";
        exit; // Salimos para evitar mostrar el resto de la página
    } else {
        echo "Error: Datos incompletos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar - Ferretería XYZ</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Pagar - Ferretería XYZ</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="carrito.php">Carrito</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Detalles de Pago</h2>
        <form method="post" action="">
            <label for="nombre_usuario">Nombre:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
            <br>
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>
            <br>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>
            <br>
            <input type="hidden" name="total" value="<?php echo number_format($totalCarrito, 2); ?>"> <!-- Mostrar el total del carrito -->
            <button type="submit" class="pay-button">Confirmar Pago</button>
        </form>
    </main>

    <footer>
        <p>© 2024 Ferretería XYZ. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

<?php
session_start();
include('includes/db.php');

// Verificar si se ha enviado un producto al carrito
if (isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Obtener información del producto de la base de datos
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        
        // Verificar si el producto ya está en el carrito
        $productoEnCarrito = false;
        foreach ($_SESSION['carrito'] as $item) {
            if ($item['id'] === $producto['id']) {
                $productoEnCarrito = true;
                break;
            }
        }

        if (!$productoEnCarrito) {
            // Agregar el producto al carrito
            $_SESSION['carrito'][] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
            ];
        } else {
            // Puedes manejar que el producto ya está en el carrito
            echo "<script>alert('El producto ya está en el carrito.');</script>";
        }
    } else {
        echo "<script>alert('Producto no encontrado.');</script>";
    }
}

header('Location: productos.php');
exit();

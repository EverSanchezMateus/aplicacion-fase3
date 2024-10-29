<?php
session_start();
include('includes/db.php');

// Verificar si el usuario es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php"); // Redirigir a inicio de sesión si no es admin
    exit();
}

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar nuevo producto
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $imagen = $_POST['imagen'];
        $descripcion = $_POST['descripcion'];

        // Preparar y ejecutar la consulta para insertar el producto
        $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, stock, imagen, descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiss", $nombre, $precio, $stock, $imagen, $descripcion);
        
        if ($stmt->execute()) {
            echo "<script>alert('Producto agregado exitosamente.');</script>";
        } else {
            echo "<script>alert('Error al agregar producto: " . $conn->error . "');</script>";
        }
        $stmt->close();
    }

    // Manejo para eliminar productos
    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        
        // Preparar y ejecutar la consulta para eliminar el producto
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Producto eliminado exitosamente.');</script>";
        } else {
            echo "<script>alert('Error al eliminar producto: " . $conn->error . "');</script>";
        }
        $stmt->close();
    }

    // Manejo para editar productos
    if (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $imagen = $_POST['imagen'];
        $descripcion = $_POST['descripcion'];

        // Preparar y ejecutar la consulta para actualizar el producto
        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ?, imagen = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("sdissi", $nombre, $precio, $stock, $imagen, $descripcion, $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Producto actualizado exitosamente.');</script>";
        } else {
            echo "<script>alert('Error al actualizar producto: " . $conn->error . "');</script>";
        }
        $stmt->close();
    }
}

// Obtener productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Estilo general -->
    <link rel="stylesheet" href="css/gestion_productos.css"> <!-- Estilo específico para gestión de productos -->
</head>
<body>
    <header>
        <h1>Gestión de Productos</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Lista de Productos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo number_format($row['precio'], 2); ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>" width="50"></td>
                        <td><?php echo htmlspecialchars($row['descripcion'] ?? 'Sin descripción'); ?></td>
                        <td>
                            <form action="gestion_productos.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="eliminar" value="Eliminar">
                            </form>
                            <button onclick="editProduct(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nombre']); ?>', <?php echo $row['precio']; ?>, <?php echo $row['stock']; ?>, '<?php echo addslashes($row['imagen']); ?>', '<?php echo addslashes($row['descripcion'] ?? ''); ?>')">Editar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Agregar / Editar Producto</h2>
        <form action="gestion_productos.php" method="POST">
            <input type="hidden" name="id" value="" id="edit-id"> <!-- Campo oculto para ID del producto en edición -->
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
            
            <label for="precio">Precio:</label>
            <input type="number" name="precio" id="precio" step="0.01" required>
            
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>
            
            <label for="imagen">URL de Imagen:</label>
            <input type="text" name="imagen" id="imagen" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required></textarea>
            
            <input type="submit" name="agregar" value="Agregar Producto" id="submit-button"> <!-- Inicialmente el botón es para agregar -->
            <input type="submit" name="editar" value="Actualizar Producto" style="display:none;" id="update-button"> <!-- Botón de actualizar oculto -->
        </form>
    </main>

    <script>
        function editProduct(id, nombre, precio, stock, imagen, descripcion) {
            document.getElementById('edit-id').value = id; // Establecer el ID del producto a editar
            document.getElementById('nombre').value = nombre;
            document.getElementById('precio').value = precio;
            document.getElementById('stock').value = stock;
            document.getElementById('imagen').value = imagen;
            document.getElementById('descripcion').value = descripcion || '';
            
            // Cambiar el nombre del botón a "Actualizar Producto"
            document.getElementById('submit-button').style.display = 'none'; // Ocultar botón de agregar
            document.getElementById('update-button').style.display = 'inline'; // Mostrar botón de actualizar
        }
    </script>

    <footer>
        <p>© 2024 Ferretería XYZ. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

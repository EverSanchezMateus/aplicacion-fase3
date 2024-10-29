<?php
session_start();
include('includes/db.php');

// Consulta para obtener los 3 últimos productos, ordenando por 'id' o la columna que exista
$query = "SELECT * FROM productos ORDER BY id DESC LIMIT 3"; // Cambiar a 'id' o la columna correspondiente
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store Nuts and Bolts</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Agrega tu archivo CSS aquí -->
</head>
<body>
    <header>
        <h1>Bienvenido a Hardware Store Nuts and Bolts</h1>
        
        <!-- Saludo al usuario logueado -->
        <?php if (isset($_SESSION['email'])): ?>
            <div class="user-info">
                <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong> | Rol: <strong><?php echo htmlspecialchars($_SESSION['rol']); ?></strong></p>
            </div>
        <?php endif; ?>

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
        <section class="about">
            <h2>Sobre Nosotros</h2>
            <p>
                Bienvenidos a Hardware Store Nuts and Bolts, una empresa familiar liderada por el señor Juan Pablo Arbeláez Pinto. 
                Con 28 años de experiencia en el mercado, nos dedicamos a ofrecer una amplia gama de productos para la construcción, 
                así como herramientas y materiales para talleres de ornamentación, latonería y pintura.
            </p>
            <p>
                En Hardware Store, entendemos la importancia de estar a la vanguardia y por ello estamos desarrollando nuestra 
                plataforma en línea para brindar a nuestros clientes la facilidad de explorar nuestro catálogo de productos desde 
                la comodidad de su hogar.
            </p>
            <p>
                Nuestra gerencia ha reconocido el valor agregado que ofrecen las empresas competidoras al tener un portafolio de 
                productos en internet, por lo que nos hemos propuesto crear una página web interactiva donde podrás realizar 
                cotizaciones y compras en línea de manera fácil y segura.
            </p>
        </section>

        <section class="services">
            <h2>Nuestros Servicios</h2>
            <ul>
                <li>Cotizaciones personalizadas para tus proyectos.</li>
                <li>Ventas en línea de productos de calidad.</li>
                <li>Asesoramiento especializado en materiales y herramientas.</li>
            </ul>
        </section>

        <section class="recent-products">
    <h2>Últimos Productos</h2>
    <div class="product-container">
        <?php while ($producto = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="product-image">
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?></p>
                <a href="productos.php?id=<?php echo $producto['id']; ?>" class="view-product-button">Ver Producto</a>
            </div>
        <?php endwhile; ?>
    </div>
</section>

    </main>

    <footer>
        <p>© 2024 Hardware Store Nuts and Bolts. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

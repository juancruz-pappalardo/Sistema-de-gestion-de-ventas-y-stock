<?php
include "../bd.php"; // Incluye el archivo de conexión a la base de datos

// Número de ventas por página
$ventas_por_pagina = 10; // Define el número de ventas que se mostrarán por página

// Determinar el número de página actual
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Obtiene el número de página actual de la URL, o establece 1 si no está definido
if ($pagina_actual < 1) {
    $pagina_actual = 1; // Asegura que el número de página no sea menor que 1
}

// Calcular el desplazamiento
$offset = ($pagina_actual - 1) * $ventas_por_pagina; // Calcula el desplazamiento para la consulta SQL

// Inicializar criterios de búsqueda
$filtro_fecha = isset($_GET['fecha']) ? $_GET['fecha'] : ''; // Obtiene el filtro de fecha de la URL, o lo establece vacío si no está definido
$filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : ''; // Obtiene el filtro de producto de la URL, o lo establece vacío si no está definido

// Construir la consulta de búsqueda
$where = "WHERE 1=1"; // Inicia la cláusula WHERE
if ($filtro_fecha != '') {
    $where .= " AND v.fecha_venta = '$filtro_fecha'"; // Añade una condición para la fecha si se ha proporcionado un filtro de fecha
}
if ($filtro_producto != '') {
    $where .= " AND v.idventa IN (SELECT dv.idventa FROM detalle_venta dv JOIN producto p ON dv.codproducto = p.codproducto WHERE p.nombre_producto LIKE '%$filtro_producto%')"; // Añade una condición para el producto si se ha proporcionado un filtro de producto
}

// Contar el número total de ventas con el filtro
$query_total = mysqli_query($connection, "SELECT COUNT(*) as total_ventas FROM venta v $where"); // Realiza una consulta para contar el número total de ventas que cumplen los criterios de búsqueda
if (!$query_total) {
    die("Error en la consulta: " . mysqli_error($connection)); // Si la consulta falla, muestra un mensaje de error y detiene la ejecución del script
}
$row_total = mysqli_fetch_assoc($query_total); // Obtiene el resultado de la consulta
$total_ventas = $row_total['total_ventas']; // Asigna el total de ventas a la variable

// Calcular el número total de páginas
$total_paginas = ceil($total_ventas / $ventas_por_pagina); // Calcula el número total de páginas

// Query para extraer ventas con límite y desplazamiento y filtros
$query = mysqli_query($connection, "
    SELECT v.idventa, v.fecha_venta, u.nombre AS nombre_usuario, c.nombre AS nombre_cliente, v.total, GROUP_CONCAT(p.nombre_producto SEPARATOR ', ') AS productos
    FROM venta v
    JOIN usuario u ON v.idusuario = u.idusuario
    JOIN cliente c ON v.codcliente = c.idcliente
    JOIN detalle_venta dv ON v.idventa = dv.idventa
    JOIN producto p ON dv.codproducto = p.codproducto
    $where
    GROUP BY v.idventa
    LIMIT $ventas_por_pagina OFFSET $offset
"); // Realiza la consulta para obtener las ventas que cumplen los criterios de búsqueda con paginación
if (!$query) {
    die("Error en la consulta: " . mysqli_error($connection)); // Si la consulta falla, muestra un mensaje de error y detiene la ejecución del script
}

// Consulta para obtener el producto más vendido
$query_producto_mas_vendido = mysqli_query($connection, "
    SELECT p.nombre_producto AS producto, COUNT(dv.idventa) AS cantidad_vendida
    FROM detalle_venta dv
    JOIN producto p ON dv.codproducto = p.codproducto
    JOIN venta v ON dv.idventa = v.idventa
    $where
    GROUP BY dv.codproducto
    ORDER BY cantidad_vendida DESC
    LIMIT 1
");
$producto_mas_vendido = mysqli_fetch_assoc($query_producto_mas_vendido);

// Consulta para obtener el usuario con más ventas
$query_usuario_mas_ventas = mysqli_query($connection, "
    SELECT u.nombre AS nombre_usuario, COUNT(v.idventa) AS cantidad_ventas
    FROM venta v
    JOIN usuario u ON v.idusuario = u.idusuario
    $where
    GROUP BY v.idusuario
    ORDER BY cantidad_ventas DESC
    LIMIT 1
");
$usuario_mas_ventas = mysqli_fetch_assoc($query_usuario_mas_ventas);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de ventas</title>
    <?php include "includes/scripts.php"; // Incluye scripts adicionales ?>
    <?php include "includes/header.php"; // Incluye el encabezado ?>
</head>
<body>
    <section id="container">
        <h1>Lista de ventas</h1>
        <a href="registro_venta.php" class="btn_new">Registrar venta</a> <!-- Enlace para registrar una nueva venta -->
        
        <form action="" method="get" class="form_search"> <!-- Formulario para búsqueda de ventas -->
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo $filtro_fecha; ?>"> <!-- Campo para filtrar por fecha -->
            <label for="producto">Producto:</label>
            <input type="text" name="producto" id="producto" placeholder="Nombre Producto" value="<?php echo $filtro_producto; ?>"> <!-- Campo para filtrar por producto -->
            <input type="submit" value="Buscar" class="btn_search"> <!-- Botón para enviar el formulario de búsqueda -->
        </form>

        <?php if ($producto_mas_vendido) : ?>
            <h2>Producto más vendido</h2>
            <p>Nombre del producto: <?php echo $producto_mas_vendido['producto']; ?></p>
            <p>Cantidad vendida: <?php echo $producto_mas_vendido['cantidad_vendida']; ?></p>
        <?php else : ?>
            <p>No hay datos disponibles</p>
        <?php endif; ?>

        <?php if ($usuario_mas_ventas) : ?>
            <h2>Usuario con más ventas</h2>
            <p>Nombre del usuario: <?php echo $usuario_mas_ventas['nombre_usuario']; ?></p>
            <p>Cantidad de ventas: <?php echo $usuario_mas_ventas['cantidad_ventas']; ?></p>
        <?php else : ?>
            <p>No hay datos disponibles</p>
        <?php endif; ?>

        <table>
            <tr>
              
                <th>Fecha de Venta</th>
                <th>Nombre de Usuario</th>
                <th>Nombre de Cliente</th>
                <th>Total</th>
                <th>Productos</th>
            </tr>

            <?php 
            if($query && mysqli_num_rows($query) > 0){ // Comprueba si hay resultados de ventas
                while($data = mysqli_fetch_array($query)){ // Itera sobre los resultados de la consulta
            ?>            
            <tr>
                <td><?php echo isset($data['fecha_venta']) ? $data['fecha_venta'] : ''; ?></td> <!-- Muestra la fecha de la venta -->
                <td><?php echo isset($data['nombre_usuario']) ? $data['nombre_usuario'] : ''; ?></td> <!-- Muestra el nombre del usuario -->
                <td><?php echo isset($data['nombre_cliente']) ? $data['nombre_cliente'] : ''; ?></td> <!-- Muestra el nombre del cliente -->
                <td><?php echo isset($data['total']) ? $data['total'] : ''; ?></td> <!-- Muestra el total de la venta -->
                <td><?php echo isset($data['productos']) ? $data['productos'] : ''; ?></td> <!-- Muestra los productos comprados -->
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6">No hay ventas registradas</td></tr>'; // Muestra un mensaje si no hay ventas registradas
            }
            ?>
        </table>

        <div class="paginador">
            <ul>
                <?php 
                if($pagina_actual > 1){ // Muestra el enlace "Anterior" si no estamos en la primera página
                ?>
                <li><a href="?pagina=<?php echo $pagina_actual - 1; ?>&fecha=<?php echo $filtro_fecha; ?>&producto=<?php echo $filtro_producto; ?>">&laquo; Anterior</a></li>
                <?php
                }

                for($i = 1; $i <= $total_paginas; $i++){ // Itera sobre el número total de páginas para mostrar los enlaces de paginación
                    if($i == $pagina_actual){
                        echo "<li class='pageSelected'>$i</li>"; // Muestra la página actual como seleccionada
                    } else {
                        echo "<li><a href='?pagina=$i&fecha=$filtro_fecha&producto=$filtro_producto'>$i</a></li>"; // Muestra los enlaces a otras páginas
                    }
                }

                if($pagina_actual < $total_paginas){ // Muestra el enlace "Siguiente" si no estamos en la última página
                ?>
                <li><a href="?pagina=<?php echo $pagina_actual + 1; ?>&fecha=<?php echo $filtro_fecha; ?>&producto=<?php echo $filtro_producto; ?>">Siguiente &raquo;</a></li>
                <?php } ?>
            </ul>
        </div>
    </section>
    <?php include "includes/footer.php"; // Incluye el pie de página ?>
</body>
</html>

<?php
session_start();

include "../bd.php";
$alert = '';

// Verificar si se proporciona un codproveedor en la URL
if (isset($_GET['codproveedor'])) {
    $codproveedor = $_GET['codproveedor'];

    // Obtener el nombre del proveedor
    $query_proveedor = mysqli_query($connection, "SELECT proveedor FROM proveedor WHERE codproveedor = '$codproveedor'");
    $proveedor_data = mysqli_fetch_assoc($query_proveedor);
    $proveedor_nombre = $proveedor_data['proveedor'];

    // Obtener la lista de productos disponibles del proveedor seleccionado
    $query_productos = mysqli_query($connection, "SELECT codproducto, nombre_producto FROM producto WHERE proveedor = '$codproveedor'");
    $productos_disponibles = [];
    if ($query_productos && mysqli_num_rows($query_productos) > 0) {
        while ($row = mysqli_fetch_assoc($query_productos)) {
            $productos_disponibles[$row['codproducto']] = $row['nombre_producto'];
        }
    }

    if (!empty($_POST)) {
        if (empty($_POST['producto']) || empty($_POST['cantidad']) || empty($_POST['precio_unitario'])) {
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {
            $codproducto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $precio_unitario = $_POST['precio_unitario'];

            // Insertar la compra
            $query_insert_compra = mysqli_query($connection, "INSERT INTO compra (fecha_compra, codproducto, cantidad, precio_unitario, total, codproveedor) VALUES (NOW(), '$codproducto', '$cantidad', '$precio_unitario', '$cantidad' * '$precio_unitario', '$codproveedor')"); // la funcion now()garantiza que la columna fecha_venta tendrá la fecha y hora exactas en que se realizó la inserción de la fila en la tabla 

            if ($query_insert_compra) {
                // Actualizar el stock del producto
                $query_actualizar_existencia = mysqli_query($connection, "UPDATE producto SET existencia = existencia + '$cantidad' WHERE codproducto = '$codproducto'");
                if ($query_actualizar_existencia) {
                    $alert = '<p class="msg_save">Compra registrada correctamente</p>';
                } else {
                    $alert = '<p class="msg_error">Error al actualizar la existencia del producto: ' . mysqli_error($connection) . '</p>';
                }
            } else {
                $alert = '<p class="msg_error">Error al registrar la compra: ' . mysqli_error($connection) . '</p>';
            }
        }
    }
} else {
    // Redireccionamos si no se proporciona un codproveedor válido
    header("Location: lista_proveedores.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generar Compra</title>
    <?php include "includes/scripts.php"; ?>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            <h1>Generar Compra</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
            <h3>Proveedor: <?php echo $proveedor_nombre; ?></h3>
            <form action="" method="post">
                <label for="producto">Producto</label>
                <select name="producto" id="producto">
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos_disponibles as $codproducto => $nombre_producto) { ?>
                        <option value="<?php echo $codproducto; ?>"><?php echo $nombre_producto; ?></option>
                    <?php } ?>
                </select>

                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad a comprar">

                <label for="precio_unitario">Precio Unitario</label>
                <input type="number" name="precio_unitario" id="precio_unitario" placeholder="Precio unitario">

                <input type="submit" value="Generar Compra" class="btn-save">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>

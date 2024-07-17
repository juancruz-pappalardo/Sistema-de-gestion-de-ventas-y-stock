<?php 
session_start();

include "../bd.php";
$alert = ''; // Variable para almacenar mensajes de alerta

// Función para obtener el precio del producto desde la base de datos
function obtener_precio_producto($codproducto) {
    global $connection;
    $query = mysqli_query($connection, "SELECT precio FROM producto WHERE codproducto = '$codproducto'");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        return $row['precio'];
    }
    return 0; // Si no se encuentra el producto o no tiene precio, devolvemos 0 o un valor por defecto.
}

// Obtener la lista de productos disponibles
$query_productos = mysqli_query($connection, "SELECT codproducto, nombre_producto, existencia FROM producto");
$productos_disponibles = [];
if ($query_productos && mysqli_num_rows($query_productos) > 0) {
    while ($fila = mysqli_fetch_assoc($query_productos)) {
        $productos_disponibles[$fila['codproducto']] = ['nombre' => $fila['nombre_producto'], 'existencia' => $fila['existencia']];
    }
}

// Obtener la lista de usuarios
$query_usuarios = mysqli_query($connection, "SELECT idusuario, nombre FROM usuario");
$usuarios = [];
if ($query_usuarios && mysqli_num_rows($query_usuarios) > 0) {
    while ($fila = mysqli_fetch_assoc($query_usuarios)) {
        $usuarios[$fila['idusuario']] = $fila['nombre'];
    }
}

// Obtener la lista de clientes
$query_clientes = mysqli_query($connection, "SELECT idcliente, nombre FROM cliente");
$clientes = [];
if ($query_clientes && mysqli_num_rows($query_clientes) > 0) {
    while ($fila = mysqli_fetch_assoc($query_clientes)) {
        $clientes[$fila['idcliente']] = $fila['nombre'];
    }
}

if (!empty($_POST)) {
    if (empty($_POST['idusuario']) || empty($_POST['codcliente']) || empty($_POST['producto']) || empty($_POST['cantidad']) || empty($_POST['clave'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {
        $idusuario = $_POST['idusuario'];
        $codcliente = $_POST['codcliente'];
        $codproducto = $_POST['producto'];
        $cantidad = $_POST['cantidad'];
        $clave = $_POST['clave'];
        $clave_hasheada = md5($clave); // Hasheamos la clave ingresada

        // Verificar la clave del usuario
        $query_clave = mysqli_query($connection, "SELECT clave FROM usuario WHERE idusuario = '$idusuario'");
        if ($query_clave && mysqli_num_rows($query_clave) > 0) {
            $row = mysqli_fetch_assoc($query_clave);
            if ($row['clave'] !== $clave_hasheada) {
                $alert = '<p class="msg_error">Clave incorrecta</p>';
            } else {
                // Obtener el precio unitario del producto seleccionado
                $precio_unitario = obtener_precio_producto($codproducto);

                // Calcular el total de la venta
                $total = $cantidad * $precio_unitario;

                // Verificar si la cantidad a vender es mayor que la existencia del producto
                if ($cantidad > $productos_disponibles[$codproducto]['existencia']) {
                    $alert = '<p class="msg_error">La cantidad a vender supera la existencia del producto. Disponible: ' . $productos_disponibles[$codproducto]['existencia'] . '</p>';
                } else {
                    // Iniciar transacción
                    mysqli_begin_transaction($connection);

                    // Calcular el nuevo stock del producto
                    $nueva_existencia = $productos_disponibles[$codproducto]['existencia'] - $cantidad;

                    // Actualizar el stock del producto
                    $query_actualizar_existencia = mysqli_query($connection, "UPDATE producto SET existencia = '$nueva_existencia' WHERE codproducto = '$codproducto'");
                    if ($query_actualizar_existencia) {
                        // Insertar la venta
                        $query_insert_venta = mysqli_query($connection, "INSERT INTO venta (fecha_venta, idusuario, codcliente, total) VALUES (NOW(), '$idusuario', '$codcliente', '$total')");

                        if ($query_insert_venta) {
                            // Obtener el id de la venta recién insertada
                            $idventa = mysqli_insert_id($connection);

                            // Insertar el detalle de la venta
                            $query_insert_detalle = mysqli_query($connection, "INSERT INTO detalle_venta (idventa, codproducto, cantidad, precio_unitario) VALUES ('$idventa', '$codproducto', '$cantidad', '$precio_unitario')");

                            if ($query_insert_detalle) {
                                // Confirmar la transacción
                                mysqli_commit($connection);
                                $alert = '<p class="msg_save">Venta registrada exitosamente</p>';
                            } else {
                                // Revertir la transacción si falla la inserción del detalle
                                mysqli_rollback($connection);
                                $alert = '<p class="msg_error">Error al registrar el detalle de la venta</p>';
                            }
                        } else {
                            // Revertir la transacción si falla la inserción de la venta
                            mysqli_rollback($connection);
                            $alert = '<p class="msg_error">Error al registrar la venta</p>';
                        }
                    } else {
                        // Revertir la transacción si falla la actualización del stock
                        mysqli_rollback($connection);
                        $alert = '<p class="msg_error">Error al actualizar la existencia del producto</p>';
                    }
                }
            }
        } else {
            $alert = '<p class="msg_error">Usuario no encontrado</p>';
        }
    }
    echo $alert; // Esto permite que el mensaje de alerta se muestre en la respuesta AJAX
    exit; // Salir para evitar que se ejecute más código después de la respuesta AJAX
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Registro de venta</title>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            <h1>Registro de venta</h1>
            <hr>
            <div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>
            <form action="" method="post" id="registro_venta_form">
                <label for="idusuario">Usuario</label>
                <select name="idusuario" id="idusuario">
                    <option value="">Seleccione un usuario</option>
                    <?php foreach ($usuarios as $idusuario => $nombre_usuario) { ?>
                        <option value="<?php echo $idusuario; ?>"><?php echo $nombre_usuario; ?></option>
                    <?php } ?>
                </select>

                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" placeholder="Clave del usuario">

                <label for="codcliente">Cliente</label>
                <select name="codcliente" id="codcliente">
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $codcliente => $nombre_cliente) { ?>
                        <option value="<?php echo $codcliente; ?>"><?php echo $nombre_cliente; ?></option>
                    <?php } ?>
                </select>

                <label for="producto">Producto</label>
                <select name="producto" id="producto">
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos_disponibles as $codproducto => $producto) { ?>
                        <option value="<?php echo $codproducto; ?>" data-precio="<?php echo obtener_precio_producto($codproducto); ?>"><?php echo $producto['nombre']; ?></option>
                    <?php } ?>
                </select>

                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad a llevar">

                <label for="total">Total</label>
                <input type="number" name="total" id="total" placeholder="Total de la venta" readonly>

                <input type="hidden" name="ajax" value="1">
                <button type="submit" class="btn-save">Registrar venta</button>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
   <!-- Librería jQuery  -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Filtrar productos cuando se escribe en el campo de búsqueda
        $('#buscar_producto').on('keyup', function () {
            var searchTerm = $(this).val().toLowerCase();
            $('#producto option').each(function () {
                var producto = $(this).text().toLowerCase();
                if (producto.indexOf(searchTerm) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Este evento se activa cuando se selecciona un producto o se cambia la cantidad
        $('#producto, #cantidad').on('change', function () {
            // Obtiene el precio del producto seleccionado desde el atributo data-precio
            var precio = parseFloat($('#producto option:selected').data('precio'));
            // Obtiene la cantidad ingresada por el usuario
            var cantidad = parseFloat($('#cantidad').val());
            // Calcula el total multiplicando el precio por la cantidad
            var total = (precio * cantidad).toFixed(2); // .toFixed(2) asegura que el total tenga dos decimales
            // Establece el valor calculado en el campo de entrada del total
            $('#total').val(total);
        });

        // Maneja el evento de envío del formulario
        $('#registro_venta_form').submit(function (event) {
            event.preventDefault(); // Evita que el formulario se envíe de la manera tradicional

            var form = $(this); // Guarda la referencia al formulario
            var formData = form.serialize(); // Serializa los datos del formulario para enviarlos en una solicitud AJAX

            $.ajax({
                type: 'POST', // Define el método de envío como POST
                url: '<?php echo $_SERVER['PHP_SELF']; ?>', // Establece la URL de destino a la misma página PHP
                data: formData, // Los datos del formulario serializados
                success: function (response) {
                    // Muestra la respuesta del servidor en la sección de alerta
                    $('.alert').html(response);
                }
            });
        });
    });
</script>
</body>

</html>
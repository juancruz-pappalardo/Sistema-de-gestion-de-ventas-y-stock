<?php
    include "../bd.php"; 

    $alert = '';

    if (!empty($_POST)) {
        if (empty($_POST['codproducto'])) {
            $alert = '<p class="msg_error">Código de producto no proporcionado</p>';
        } else {
            $codproducto = $_POST['codproducto'];

            $query_delete = mysqli_query($connection, "DELETE FROM producto WHERE codproducto = '$codproducto'");

            if ($query_delete) {
                header("location: lista_producto.php"); 
                exit;
            } else {
                $alert = "Error al eliminar el producto";
            }
        }
    }

    if (empty($_GET['id'])) {
        header("location: lista_producto.php"); 
        exit;
    } else {
        $codproducto = $_GET['id'];

        $query = mysqli_query($connection, "SELECT nombre_producto, proveedor FROM producto WHERE codproducto = '$codproducto'");
        
        if ($query) {
            $result = mysqli_num_rows($query); 
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $nombre_producto = $data['nombre_producto']; 
                    $proveedor = $data['proveedor']; 
                }
            } else {
                echo "Producto no encontrado";
                exit;
            }
        } else {
            echo "Error en la consulta SQL: " . mysqli_error($connection);
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <?php include "includes/header.php"; ?>
    <title>Eliminar producto</title>
</head>
<body>
    <section id="container">
        <div class="data_delete">
            <h2>¿Estás seguro de que deseas eliminar el siguiente producto?</h2>
            <p>Nombre del Producto: <span><?php echo $nombre_producto; ?></span></p>
            <p>Proveedor: <span><?php echo $proveedor; ?></span></p>

            <form method="post">
                <input type="hidden" name="codproducto" value="<?php echo $codproducto?>"></input>
                <a href="lista_producto.php" class="btn-cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn-ok"></input>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>
</html>

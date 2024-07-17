<?php
    include "../bd.php";

    $alert = '';

    if (!empty($_POST)) { 
        if (empty($_POST['proveedor']) || empty($_POST['nombre_producto']) || empty($_POST['precio']) || empty($_POST['existencia'])) { 
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>'; 
        } else { 
            $codproducto = $_POST['codproducto']; 
            $proveedor = $_POST['proveedor']; 
            $nombre_producto = $_POST['nombre_producto']; 
            $precio = $_POST['precio']; 
            $existencia = $_POST['existencia']; 

            $query = mysqli_query($connection, "UPDATE producto SET proveedor = '$proveedor', nombre_producto = '$nombre_producto', precio = '$precio', existencia = '$existencia' WHERE codproducto = $codproducto");

            if ($query) { 
                $alert='<p class="msg_save">Producto actualizado correctamente</p>'; 
            } else { 
                $alert='<p class="msg_error">Error al actualizar producto</p>'; 
            } 
        } 
    } 

    if (empty($_GET['id'])) { 
        header('Location: lista_producto.php'); 
        exit; 
    } 

    $codproducto = $_GET['id']; 
    $sql = mysqli_query($connection, "SELECT * FROM producto WHERE codproducto = $codproducto");

    $result_sql = mysqli_num_rows($sql); 
    if ($result_sql == 0) { 
        header('Location: lista_producto.php'); 
        exit; 
    } else { 
        $data = mysqli_fetch_array($sql); 
        $codproducto = $data['codproducto']; 
        $proveedor = $data['proveedor']; 
        $nombre_producto = $data['nombre_producto']; 
        $precio = $data['precio']; 
        $existencia = $data['existencia']; 
    } 
?> 

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar producto</title>
    <?php include "includes/scripts.php"; ?>

</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1>Actualizar producto</h1>
            <hr>

            <div class="alert"><?php echo $alert; ?></div>

            <form action="" method="post"> 
                <input type="hidden" name="codproducto" value="<?php echo $codproducto; ?>">

                <label for="proveedor">Proveedor</label>
                <select name="proveedor" id="proveedor">
                    <?php
                    $query_proveedores = mysqli_query($connection, "SELECT codproveedor, proveedor FROM proveedor ORDER BY proveedor ASC"); 
                    if(mysqli_num_rows($query_proveedores) > 0){
                        while($row = mysqli_fetch_array($query_proveedores)){
                            $seleccionado = ($row['codproveedor'] == $proveedor) ? "seleccionado" : "";
                            echo "<option value='{$row['codproveedor']}' {$seleccionado}>{$row['proveedor']}</option>";
                        }
                    }
                    ?>
                </select>

                <label for="nombre_producto">Nombre del Producto</label>
                <input type="text" name="nombre_producto" id="nombre_producto" placeholder="Nombre del producto" value="<?php echo $nombre_producto; ?>"> 

                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del producto" value="<?php echo $precio; ?>"> 

                <label for="existencia">Existencia</label>
                <input type="number" name="existencia" id="existencia" placeholder="Existencia del producto" value="<?php echo $existencia; ?>"> 

                <input type="submit" value="Actualizar producto" class="btn-save">
            </form>
        </div>
    </section>
</body>
</html>

<?php
session_start(); 

include "../bd.php";
$alert = '';

if (!empty($_POST)) { 
    if (empty($_POST['proveedor']) || empty($_POST['precio']) || empty($_POST['existencia']) || empty($_POST['nombre_producto'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {
        $proveedor = $_POST['proveedor'];
        $precio = $_POST['precio'];
        $existencia = $_POST['existencia'];
        $nombre_producto = $_POST['nombre_producto']; 
        
        // Verificar si $_SESSION['iduser'] está definido
        $usuario_id = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : null;
        
        $query_insert = mysqli_query($connection, "INSERT INTO producto(proveedor, precio, existencia, nombre_producto) VALUES ('$proveedor', '$precio', '$existencia', '$nombre_producto')");
        if ($query_insert) {
            $alert = '<p class="msg_save">Producto registrado correctamente</p>';
        } else {
            $alert = '<p class="msg_error">Error al registrar producto</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Registro de producto</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1>Registro de producto</h1>
            <hr>
            <div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>
            <form action="" method="post">
            <label for="proveedor">Proveedor</label>
                <select name="proveedor" id="proveedor">
                    <?php
                    $query_proveedor = mysqli_query($connection, "SELECT codproveedor, proveedor FROM proveedor ORDER BY proveedor ASC"); 
                    if(mysqli_num_rows($query_proveedor) > 0){
                        while($proveedor = mysqli_fetch_array($query_proveedor)){
                    ?>
                    <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>


                <label for="nombre_producto">Nombre del producto</label>
                <input type="text" name="nombre_producto" id="nombre_producto" placeholder="Nombre del producto">

                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del producto">

                <label for="existencia">Existencia</label>
                <input type="number" name="existencia" id="existencia" placeholder="Existencia del producto">

             

                <input type="submit" value="Registrar producto" class="btn-save">
            </form>
        </div>
    </section>
    <?php include "includes/footer.php"; ?>
</body>
</html>


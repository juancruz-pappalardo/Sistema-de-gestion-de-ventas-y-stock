<?php
session_start(); 

include "../bd.php";
$alert = '';

if (!empty($_POST)) { 
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {
        $cuit = $_POST['cuit'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion']; 
        
        $cliente_id = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : null;
        
        $result = 0; 
        if(is_numeric($cuit)){
            $query = mysqli_query($connection, "SELECT * FROM cliente WHERE cuit = '$cuit'");
            $result = mysqli_fetch_array($query);
        }

        if($result > 0){
            $alert = '<p class="msg_error">El número de CUIT ya existe</p>';
        }else{
            $query_insert = mysqli_query($connection, "INSERT INTO cliente(cuit, nombre, telefono, direccion) VALUES ('$cuit', '$nombre', '$telefono', '$direccion')");
            if ($query_insert) {
                $alert = '<p class="msg_save">Cliente registrado correctamente</p>';
            } else {
                $alert = '<p class="msg_error">Error al registrar cliente</p>';
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>

    <title>Registro de cliente</title>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            <h1>Registro cliente</h1>
            <hr>
            <div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>
            <form action="" method="post">
                <label for="cuit">CUIT</label>
                <input type="number" name="cuit" id="cuit" placeholder="CUIT del cliente">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del cliente">

                <label for="telefono">Teléfono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Teléfono del cliente">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección del cliente">

                <input type="submit" value="Registrar cliente" class="btn-save">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>

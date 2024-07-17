<?php
session_start(); 

include "../bd.php";
$alert = '';

if (!empty($_POST)) { 
    if (empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {
        $proveedor = $_POST['proveedor'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion']; 
        
        $query_insert = mysqli_query($connection, "INSERT INTO proveedor(proveedor, contacto, telefono, direccion) VALUES ('$proveedor', '$contacto', '$telefono', '$direccion')");
        if ($query_insert) {
            $alert = '<p class="msg_save">Proveedor registrado correctamente</p>';
        } else {
            $alert = '<p class="msg_error">Error al registrar proveedor</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>

    <title>Registro de proveedor</title>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            <h1>Registro proveedor</h1>
            <hr>
            <div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>
            <form action="" method="post">
                <!-- No se solicita el campo codproveedor ya que es autoincremental en la base de datos -->
                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del proveedor">

                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Persona de contacto">

                <label for="telefono">Teléfono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Teléfono del proveedor">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección del proveedor">

                <input type="submit" value="Registrar proveedor" class="btn-save">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>
</html>

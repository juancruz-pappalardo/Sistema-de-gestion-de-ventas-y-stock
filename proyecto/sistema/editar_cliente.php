<?php
    include "../bd.php";

    $alert = '';

    if (!empty($_POST)) { 
        if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) { 
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>'; 
        } else { 
            $idcliente = $_POST['idcliente']; 
            $nombre = $_POST['nombre']; 
            $telefono = $_POST['telefono']; 
            $direccion = $_POST['direccion']; 

            $query = mysqli_query($connection, "UPDATE cliente SET nombre = '$nombre', telefono = '$telefono', direccion = '$direccion' WHERE idcliente = $idcliente");

            if ($query) { 
                $alert='<p class="msg_save">Cliente actualizado correctamente</p>'; 
            } else { 
                $alert='<p class="msg_error">Error al actualizar cliente</p>'; 
            } 
        } 
    } 

    if (empty($_GET['id'])) { 
        header('Location: lista_cliente.php'); 
        exit; 
    } 

    $idcliente = $_GET['id']; 
    $sql = mysqli_query($connection, "SELECT * FROM cliente WHERE idcliente = $idcliente");

    $result_sql = mysqli_num_rows($sql); 
    if ($result_sql == 0) { 
        header('Location: lista_cliente.php'); 
        exit; 
    } else { 
        $data = mysqli_fetch_array($sql); 
        $idcliente = $data['idcliente']; 
        $nombre = $data['nombre']; 
        $telefono = $data['telefono']; 
        $direccion = $data['direccion']; 
    } 
?> 

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar cliente</title>
    <?php include "includes/scripts.php"; ?>

</head>
<body>
<?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1>Actualizar cliente</h1>
            <hr>

            <div class="alert"><?php echo $alert; ?></div>

            <form action="" method="post"> 
                <input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del cliente" value="<?php echo $nombre; ?>"> 

                <label for="telefono">Teléfono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Teléfono del cliente" value="<?php echo $telefono; ?>">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección del cliente" value="<?php echo $direccion; ?>">

                <input type="submit" value="Actualizar cliente" class="btn-save">
            </form>
        </div>
    </section>
</body>
</html>

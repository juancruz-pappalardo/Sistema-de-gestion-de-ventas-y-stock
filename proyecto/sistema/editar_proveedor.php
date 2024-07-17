<?php
    include "../bd.php";

    $alert = '';

    if (!empty($_POST)) { 
        if (empty($_POST['nombre']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])) { 
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>'; 
        } else { 
            $codproveedor = $_POST['codproveedor']; 
            $nombre = $_POST['nombre']; 
            $contacto = $_POST['contacto']; 
            $telefono = $_POST['telefono']; 
            $direccion = $_POST['direccion']; 

            $query = mysqli_query($connection, "UPDATE proveedor SET proveedor = '$nombre', contacto = '$contacto', telefono = '$telefono', direccion = '$direccion' WHERE codproveedor = $codproveedor");

            if ($query) { 
                $alert='<p class="msg_save">Proveedor actualizado correctamente</p>'; 
            } else { 
                // Mostrar el error de MySQL si la consulta falla (eliminar al resolvel problema)
                $alert='<p class="msg_error">Error al actualizar proveedor: ' . mysqli_error($connection) . '</p>'; 
            } 
        } 
    } 

    if (empty($_GET['id'])) { 
        header('Location: lista_proveedor.php'); 
        exit; 
    } 

    $codproveedor = $_GET['id']; 
    $sql = mysqli_query($connection, "SELECT * FROM proveedor WHERE codproveedor = $codproveedor");

    $result_sql = mysqli_num_rows($sql); 
    if ($result_sql == 0) { 
        header('Location: lista_proveedor.php'); 
        exit; 
    } else { 
        $data = mysqli_fetch_array($sql); 
        $codproveedor = $data['codproveedor']; 
        $nombre = $data['proveedor']; 
        $contacto = $data['contacto']; 
        $telefono = $data['telefono']; 
        $direccion = $data['direccion']; 
    } 
?> 

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar proveedor</title>
    <?php include "includes/scripts.php"; ?>
</head>
<body>
<?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1>Actualizar proveedor</h1>
            <hr>

            <div class="alert"><?php echo $alert; ?></div>

            <form action="" method="post"> 
                <input type="hidden" name="codproveedor" value="<?php echo $codproveedor; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del proveedor" value="<?php echo $nombre; ?>"> 

                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Contacto del proveedor" value="<?php echo $contacto; ?>"> 

                <label for="telefono">Teléfono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Teléfono del proveedor" value="<?php echo $telefono; ?>">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección del proveedor" value="<?php echo $direccion; ?>">

                <input type="submit" value="Actualizar proveedor" class="btn-save">
            </form>
        </div>
    </section>
</body>
</html>

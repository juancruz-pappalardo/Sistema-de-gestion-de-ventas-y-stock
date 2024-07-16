<?php
    include "../bd.php"; 
    if(!empty($_POST)){
        $codproveedor = $_POST['codproveedor']; 

        $query_delete = mysqli_query($connection, "DELETE FROM proveedor WHERE codproveedor = $codproveedor"); 

        if($query_delete){
            header("location: lista_proveedor.php"); 
            exit;
        }else{
            echo "Error al eliminar"; 
            exit;
        }
    }
    if(empty($_REQUEST['id'])){
        header("location: lista_proveedor.php"); 
        exit;
    } else {
        $codproveedor = $_REQUEST['id'];

        $query = mysqli_query($connection, "SELECT proveedor, contacto, telefono, direccion FROM proveedor WHERE codproveedor = '$codproveedor'");
        if($query){
            $result = mysqli_num_rows($query); 
            if($result > 0){
                while ($data = mysqli_fetch_array($query)){
                    $proveedor = $data['proveedor']; 
                    $contacto = $data['contacto']; 
                    $telefono = $data['telefono']; 
                    $direccion = $data['direccion']; 
                }
            } else {
                echo "Proveedor no encontrado";
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
    <title>Eliminar proveedor</title>
</head>
<body>
    <section id="container">
        <div class="data_delete">
            <h2>¿Estás seguro de que deseas eliminar el siguiente proveedor?</h2>
            <p>Proveedor: <span><?php echo $proveedor; ?></span></p>
            <p>Contacto: <span><?php echo $contacto; ?></span></p>
            <p>Teléfono: <span><?php echo $telefono; ?></span></p>
            <p>Dirección: <span><?php echo $direccion; ?></span></p>

            <form method="post">
                <input type="hidden" name="codproveedor" value="<?php echo $codproveedor?>"></input>
                <a href="lista_proveedor.php" class="btn-cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn-ok"></input>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>
</html>

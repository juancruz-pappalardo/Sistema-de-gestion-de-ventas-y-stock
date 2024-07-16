<?php
    include "../bd.php"; 
    if(!empty($_POST)){
        $idcliente = $_POST['idcliente']; 

        $query_delete = mysqli_query($connection, "DELETE FROM cliente WHERE idcliente = $idcliente"); 

        if($query_delete){
            header("location: lista_cliente.php"); 
        }else{
            echo "Error al eliminar"; 
        }
    }
    if(empty($_REQUEST['id'])){
        header("location: lista_cliente.php"); 
    } else {
        $idcliente = $_REQUEST['id'];

        $query = mysqli_query($connection, "SELECT nombre, telefono, direccion FROM cliente WHERE idcliente = $idcliente");
        $result = mysqli_num_rows($query); 

        if($result > 0){
            while ($data = mysqli_fetch_array($query)){
                $nombre = $data['nombre']; 
                $telefono = $data['telefono']; 
                $direccion = $data['direccion']; 
            }	 
        } else {
            header("location: lista_cliente.php");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <?php include "includes/header.php"; ?>
    <title>Eliminar cliente</title>
</head>
<body>
    <section id="container">
        <div class="data_delete">
            <h2>¿Estás seguro de que deseas eliminar el siguiente cliente?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>Teléfono: <span><?php echo $telefono; ?></span></p>
            <p>Dirección: <span><?php echo $direccion; ?></span></p>

            <form method="post">
                <input type="hidden" name="idcliente" value="<?php echo $idcliente?>"></input>
                <a href="lista_cliente.php" class="btn-cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn-ok"></input>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>
</html>

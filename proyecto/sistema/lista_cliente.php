<?php
    include "../bd.php"; 

    // Inicializar la variable $nombre_cliente para la búsqueda
    $nombre_cliente = '';

    // Verificar si se ha enviado el formulario de búsqueda
    if (isset($_GET['nombre_cliente'])) {
        $nombre_cliente = $_GET['nombre_cliente'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de clientes</title>
    <?php include "includes/scripts.php"; ?>
    <?php include "includes/header.php"; ?>
</head>
<body>
    <section id="container">
        <h1>Lista de clientes</h1>
        <a href="registro_cliente.php" class="btn_new">Crear un cliente</a>

        <!-- Formulario de búsqueda -->
        <form action="" method="GET" class="form_search">
            <input type="text" name="nombre_cliente" id="nombre_cliente" placeholder="Buscar por nombre..." value="<?php echo $nombre_cliente; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>
       
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>CUIT</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>

            <?php
            // Query para extraer clientes
            if (!empty($nombre_cliente)) {
                $query = mysqli_query($connection, "SELECT * FROM cliente WHERE nombre LIKE '%$nombre_cliente%'");
            } else {
                $query = mysqli_query($connection, "SELECT * FROM cliente");
            }

            $result = mysqli_num_rows($query);

            if($result > 0){
                while($data = mysqli_fetch_array($query)){
            ?>            
            <tr>
                <td><?php echo $data['idcliente']; ?></td>
                <td><?php echo $data['nombre']; ?></td>
                <td><?php echo $data['cuit']; ?></td>
                <td><?php echo $data['telefono']; ?></td>
                <td><?php echo $data['direccion']; ?></td>
                <td>
                    <a class="link_edit" href="editar_cliente.php?id=<?php echo $data['idcliente']; ?>">Editar</a>
                    <a class="link_delete" href="eliminar_cliente.php?id=<?php echo $data['idcliente']; ?>">Eliminar</a>
                </td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6">No hay clientes registrados o no se encontraron resultados para la búsqueda.</td></tr>';
            }
            ?>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>
</body>
</html>

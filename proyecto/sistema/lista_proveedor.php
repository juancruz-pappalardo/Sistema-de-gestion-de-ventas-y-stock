<?php
    include "../bd.php"; 

    // Inicializar la variable $nombre para la búsqueda
    $nombre = '';

    // Verificar si se ha enviado el formulario de búsqueda
    if (isset($_GET['nombre'])) {
        $nombre = $_GET['nombre'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de proveedores</title>
    <?php include "includes/scripts.php"; ?>
    <?php include "includes/header.php"; ?>
</head>
<body>
    <section id="container">
        <h1>Lista de proveedores</h1>
        <a href="registro_proveedor.php" class="btn_new">Crear un proveedor</a>

        <!-- Formulario de búsqueda -->
        <form action="" method="GET" class="form_search">
            <input type="text" name="nombre" id="nombre" placeholder="Buscar por nombre..." value="<?php echo $nombre; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>
       
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>

            <?php
            // Query para extraer proveedores
            if (!empty($nombre)) {
                $query = mysqli_query($connection, "SELECT * FROM proveedor WHERE proveedor LIKE '%$nombre%'");
            } else {
                $query = mysqli_query($connection, "SELECT * FROM proveedor");
            }

            if($query && mysqli_num_rows($query) > 0){
                while($data = mysqli_fetch_array($query)){
            ?>            
            <tr>
                <td><?php echo isset($data['codproveedor']) ? $data['codproveedor'] : ''; ?></td>
                <td><?php echo isset($data['proveedor']) ? $data['proveedor'] : ''; ?></td>
                <td><?php echo isset($data['contacto']) ? $data['contacto'] : ''; ?></td>
                <td><?php echo isset($data['telefono']) ? $data['telefono'] : ''; ?></td>
                <td><?php echo isset($data['direccion']) ? $data['direccion'] : ''; ?></td>
                <td>
                    <a class="link_edit" href="editar_proveedor.php?id=<?php echo isset($data['codproveedor']) ? $data['codproveedor'] : ''; ?>">Editar</a>
                    <a class="link_delete" href="eliminar_proveedor.php?id=<?php echo isset($data['codproveedor']) ? $data['codproveedor'] : ''; ?>">Eliminar</a>
                    <a class="link_edit" href="generar_compra.php?codproveedor=<?php echo isset($data['codproveedor']) ? $data['codproveedor'] : ''; ?>">Generar Compra</a>
                </td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6">No hay proveedores registrados o no se encontraron resultados para la búsqueda.</td></tr>';
            }
            ?>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>
</body>
</html>

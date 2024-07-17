<?php
    include "../bd.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de productos</title>
    <?php include "includes/scripts.php"; ?>
    <?php include "includes/header.php"; ?>
</head>
<body>
    <section id="container">
        <h1>Lista de productos</h1>
        <a href="registro_producto.php" class="btn_new">Crear un producto</a>
        
        <form action="" method="get" class="form_search">
            <input type="text" name="producto" id="producto" placeholder="Nombre del Producto" value="<?php echo isset($_GET['producto']) ? $_GET['producto'] : ''; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>
       
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre del Producto</th>
                <th>Proveedor</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Acciones</th>
            </tr>

            <?php 
            // Obtener el filtro de producto
            $filtro_producto = isset($_GET['producto']) ? $_GET['producto'] : '';

            // Construir la consulta de búsqueda
            $where = "";
            if ($filtro_producto != '') {
                $where = "WHERE p.nombre_producto LIKE '%$filtro_producto%'";
            }

            // Query para extraer productos con el filtro
            $query = mysqli_query($connection, "SELECT p.codproducto, p.nombre_producto, pr.proveedor, p.precio, p.existencia FROM producto p JOIN proveedor pr ON p.proveedor = pr.codproveedor $where");

            if($query && mysqli_num_rows($query) > 0){
                while($data = mysqli_fetch_array($query)){
            ?>            
            <tr>
                <td><?php echo isset($data['codproducto']) ? $data['codproducto'] : ''; ?></td>
                <td><?php echo isset($data['nombre_producto']) ? $data['nombre_producto'] : ''; ?></td>
                <td><?php echo isset($data['proveedor']) ? $data['proveedor'] : ''; ?></td>
                <td><?php echo isset($data['precio']) ? $data['precio'] : ''; ?></td>
                <td><?php 
                        $existencia = isset($data['existencia']) ? $data['existencia'] : 0;
                        echo $existencia >= 0 ? $existencia : 0;
                        // Verificar si la existencia es menor a 10
                        if ($existencia <= 0) {
                            echo '<span style="color: red;"> (Sin existencia)</span>';
                        } elseif ($existencia < 10) {
                            echo '<span style="color: red;"> (Poca existencia)</span>';
                        }
                    ?></td>
                <td>
                    <a class="link_edit" href="editar_producto.php?id=<?php echo isset($data['codproducto']) ? $data['codproducto'] : ''; ?>">Editar</a>
                    <a class="link_delete" href="eliminar_producto.php?id=<?php echo isset($data['codproducto']) ? $data['codproducto'] : ''; ?>">Eliminar</a>
                </td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6">No hay productos registrados</td></tr>';
            }
            ?>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>
</body>
</html>

<?php
    include "../bd.php"; 
    include "verificar_rol.php";

    if (!es_admin()) {
        header("Location: ../index.php");
        exit();
    }

    // Inicializar la variable $nombre_usuario para la búsqueda
    $nombre_usuario = '';

    // Verificar si se ha enviado el formulario de búsqueda
    if (isset($_GET['nombre_usuario'])) {
        $nombre_usuario = $_GET['nombre_usuario'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de usuarios</title>
    <?php include "includes/scripts.php"; ?>
    <?php include "includes/header.php"; ?>
</head>
<body>
    <section id="container">
        <h1>Lista de usuarios</h1>
        <a href="registro_usuario.php" class="btn_new">Crear un usuario</a>

        <!-- Formulario de búsqueda -->
        <form action="" method="GET" class="form_search">
            <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Buscar por nombre de usuario..." value="<?php echo $nombre_usuario; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>
    
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>

            <?php
            // Query para extraer usuarios
            if (!empty($nombre_usuario)) {
                $query = mysqli_query($connection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.usuario LIKE '%$nombre_usuario%'");
            } else {
                $query = mysqli_query($connection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol");
            }

            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>            
            <tr>
                <td><?php echo $data['idusuario']; ?></td>
                <td><?php echo $data['nombre']; ?></td>
                <td><?php echo $data['correo']; ?></td>
                <td><?php echo $data['usuario']; ?></td>
                <td><?php echo $data['rol']; ?></td>
                <td>
                    <a class="link_edit" href="editar_usuario.php?id=<?php echo $data['idusuario']; ?>">Editar</a>
                    <?php
                    if ($data['idusuario'] != 1) {
                    ?>
                        <a class="link_delete" href="eliminar_usuario.php?id=<?php echo $data['idusuario']; ?>">Eliminar</a>
                    <?php } ?>
                </td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6">No hay usuarios registrados o no se encontraron resultados para la búsqueda.</td></tr>';
            }
            ?>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>
</body>
</html>

<?php 
include "../bd.php";

$alert = '';

if (!empty($_POST)) { 
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])) { 
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>'; 
    } else { 
        $idusuario = $_POST['idusuario']; 
        $nombre = $_POST['nombre']; 
        $correo = $_POST['correo']; 
        $usuario = $_POST['usuario']; 
        $clave = md5($_POST['clave']); 
        $rol = $_POST['rol'];

        $query = mysqli_query($connection, "SELECT * FROM usuario WHERE (usuario = '$usuario' AND idusuario != $idusuario) OR (correo = '$correo' AND idusuario != $idusuario)"); // aseguramos  de que el mismo usuario no se esté seleccionando nuevamente en la consulta, esto asegura que la consulta no arroje un falso positivo al encontrar el mismo nombre de usuario o correo electrónico en el mismo registro que estás modificando
        $result = mysqli_fetch_array($query); 

        if ($result > 0) { 
            $alert='<p class="msg_error">El correo o el usuario ya están registrados</p>'; 
        } else { 
            if (empty($_POST['clave'])) { 
                $sql_update = mysqli_query($connection, "UPDATE usuario SET nombre = '$nombre', correo = '$correo', usuario = '$usuario', rol = '$rol' WHERE idusuario = $idusuario");
            } else { 
                $sql_update = mysqli_query($connection, "UPDATE usuario SET nombre = '$nombre', correo = '$correo', usuario = '$usuario', clave = '$clave', rol = '$rol' WHERE idusuario = '$idusuario'");
            } 

            if ($sql_update) { 
                $alert='<p class="msg_save">Usuario actualizado correctamente</p>'; 
            } else { 
                $alert='<p class="msg_error">Error al actualizar usuario</p>'; 
            } 
        } 
    } 
} 

if (empty($_GET['id'])) { 
    header('Location: lista_usuario.php'); 
    exit; 
} 

$iduser = $_GET['id']; 
$sql = mysqli_query($connection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, u.rol as idrol, r.rol FROM usuario u INNER JOIN rol r on u.rol = r.idrol WHERE idusuario = $iduser");

$result_sql = mysqli_num_rows($sql); 
if ($result_sql == 0) { 
    header('Location: lista_usuario.php'); 
    exit; 
} else { 
    $data = mysqli_fetch_array($sql); 
    $idusuario = $data['idusuario']; 
    $nombre = $data['nombre']; 
    $correo = $data['correo']; 
    $usuario = $data['usuario']; 
    $idrol = $data['idrol']; 
    $rol = $data['rol']; 
} 
?> 

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar usuario</title>
    <?php include "includes/scripts.php"; ?>

</head>
<body>
<?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1>Actualizar usuario</h1>
            <hr>

            <div class="alert"><?php echo $alert; ?></div>

            <form action="" method="post"> 
                <input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del usuario" value="<?php echo $nombre; ?>"> 

                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" placeholder="Correo del usuario" value="<?php echo $correo; ?>">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">

                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" placeholder="Clave del usuario">

                <label for="rol">Rol</label>
                <select name="rol" id="rol">
                    <?php 
                    $query_roles = mysqli_query($connection, "SELECT * FROM rol");
                    while ($row = mysqli_fetch_array($query_roles)) { 
                        $seleccionado = ($row['idrol'] == $idrol) ? 'seleccionado' : ''; 
                        echo '<option value="' . $row['idrol'] . '" ' . $seleccionado . '>' . $row['rol'] . '</option>'; 
                    } 
                    ?>
                </select>

                <input type="submit" value="Actualizar usuario" class="btn-save">
            </form>
        </div>
    </section>
</body>
</html>

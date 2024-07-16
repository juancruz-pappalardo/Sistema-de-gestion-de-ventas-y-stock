<?php
include "../bd.php";
include "verificar_rol.php";

if (!es_admin()) {
    header("Location: ../index.php");
    exit();
}

$alert = '';

if (!empty($_POST)) { 
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']); 
        $rol = $_POST['rol'];

        $query = mysqli_query($connection, "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email'");

        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<p class="msg_error">El correo o el usuario ya están registrados</p>';
        } else {
            $query_insert = mysqli_query($connection, "INSERT INTO usuario(nombre, correo, usuario, clave, rol) VALUES ('$nombre', '$email', '$user', '$clave', '$rol')");

            if ($query_insert) {
                $alert = '<p class="msg_save">Usuario registrado correctamente</p>';
            } else {
                $alert = '<p class="msg_error">Error al crear usuario</p>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Registro de usuario</title>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            <h1>Registro usuario</h1>
            <hr>
            <div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>
            <form action="" method="post">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del usuario">

                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" placeholder="Correo del usuario">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario"> 

                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" placeholder="Clave del usuario">

                <label for="rol">Rol</label>
                <select name="rol" id="rol">
                    <?php
                    $query_rol = mysqli_query($connection, "SELECT * FROM rol");
                    $result_rol = mysqli_num_rows($query_rol);

                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {
                    ?>
                            <option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"]; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <input type="submit" value="Crear usuario" class="btn-save">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>

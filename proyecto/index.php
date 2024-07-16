<?php
    $alert = ""; 
    
    session_start(); // Inicializamos sesion

    if(!empty($_SESSION['active'])){ // Validamos si existe la sesion
        header('location: sistema/'); 
    }
    if (!empty($_POST)) { // Comprobamos si el usuario le dio a ingresar
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = "Verifique que los datos ingresados sean correctos"; 
        } else {
            require_once "bd.php";
            $user = $_POST['usuario']; 
            $pass = md5(mysqli_real_escape_string($connection,$_POST['clave'])); // Evita caracteres maliciosos como por ejmplo comillas (para sql injection)

            $query = mysqli_query($connection, "SELECT * FROM usuario WHERE usuario= '$user' AND clave = '$pass'" );
            $result = mysqli_num_rows($query); // Deuvleve un numero

            if($result > 0){ // Si encuentra un registro es mayor a 0, entonces guardamos info
                $data = mysqli_fetch_array($query);
                
                $_SESSION['active'] = true; 
                $_SESSION['idUser'] = $data['idUsuario']; 
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['rol']    = $data['rol'];
                // No pongo la clave porque no es recomendable almacenar la clave de usuario en la sesion, primero porque no es necesario, porque el usurario ya inicio sesion
                // segundo es porque existe un mayor riesgo de exposición si hay alguna falla de seguridad en la app
                
                header('location: sistema/'); 
                
            }else{
                $alert = "El usuario o clave son incorrectos"; 
                session_destroy(); // Finalizamos sesion
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Login</title>
</head>
<body>
    <section id="container">
        <form action="" method = "post">
            <h3>Iniciar sesión</h3> 
            <img src="img/login.png">
            <input type="text" name="usuario" placeholder="Nombre del usuario">
            <input type="password" name="clave" placeholder="Clave del usuario">
            <div class="alert"><?php echo (isset($alert) ? $alert : ''); ?></div>
            <input type="submit" value="Iniciar sesión">

        </form>
    </section>
</body>
</html>
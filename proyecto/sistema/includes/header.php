<?php
session_start();
if(empty($_SESSION['active'])){ // Validamos si existe la sesion
    header('location: ../index.php'); // Si no existe redirigimos al login
}
?>

<header>
		<div class="header">
			<h1>Sistema de facturación</h1>
			<div class="optionsBar">
				<img class="photouser" src="img/user.png" alt="Usuario">
				<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
        <?php include "nav.php"; ?>
	</header>
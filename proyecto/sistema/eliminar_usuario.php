<?php
/* 
Si no queremos eliminar al usuario y lo queremos desactivar, podemos poner una nueva columna en la tabla usuario llamada estatus 
$query_delete = mysqli_query($connection, "UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuario"); // Query para cambiar de estado al usuario y no eliminarlo

cambiar en lista_usuario.php 
$query = mysqli_query($connection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE estatus = 1"); 


*/ 
	include "../bd.php"; 
	if(!empty($_POST)){
		$idusuario = $_POST['idusuario']; 

		$query_delete = mysqli_query($connection, "DELETE FROM usuario WHERE idusuario = $idusuario"); // Query para eliminar registro

		if($query_delete){
			header("location: lista_usuario.php"); 
		}else{
			echo "Error al eliminar"; 
		}
	}
	if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1){// request recibe por get y post
		header("location: lista_usuario.php"); 
	}else{
		

		$idusuario = $_REQUEST['id'];

		$query = mysqli_query($connection, "SELECT u.nombre,u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.idusuario = $idusuario");
		$result = mysqli_num_rows($query); 

		if($result > 0){
			while ($data = mysqli_fetch_array($query)){
				$nombre = $data['nombre']; 
				$usuario = $data['usuario']; 
				$rol = $data['rol']; 
			}	 
		} else{
			header("location: lista_usuario.php");
		}
	}
?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<?php include "includes/header.php"; ?>
	<title>Eliminar usuario</title>
</head>
<body>
	<section id="container">
		<div class="data_delete">
		<h2>¿Estás seguro de que deseas eliminar el siguiente usuario?</h2>
		<p>Nombre: <span><?php echo $nombre; ?></span></p>
		<p>Usuario: <span><?php echo $usuario; ?></span></p>
		<p>Rol del usuario: <span><?php echo $rol; ?></span></p>

		<form method="post">
			<input type="hidden" name="idusuario" value="<?php echo $idusuario?>"></input> <!--- Lo pongo hidden para no mostrarlo pero si para obtener el id -->
			<a href="lista_usuario.php" class="btn-cancel">Cancelar</a>
			<input type="submit" value="Aceptar" class="btn-ok"></input>
		</form>
		</div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>

<?php
    $host = "localhost"; 
    $user = "root"; 
    $password = ""; 
    $bbdd = "facturacion";


    $connection = mysqli_connect($host, $user, $password, $bbdd);


    if(!$connection){
        echo("Error en la conexion"); 
    }

    //misqly_close($connection); Cerrar conexion 


?>
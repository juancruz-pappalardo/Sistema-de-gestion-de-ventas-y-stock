<?php
session_start();
function es_admin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] == 1;
}
?>

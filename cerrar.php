<?php
session_start();
unset($_SESSION['IdMast']);
unset($_SESSION['apellidos']);
unset($_SESSION['nombre']);
unset($_SESSION['idD']);    
session_destroy();
header("Location:index.php");
?>
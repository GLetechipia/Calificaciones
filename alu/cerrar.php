<?php
session_start();
unset($_SESSION['numcont']);
unset($_SESSION['apellidos'] );
unset($_SESSION['nombre'] );
unset($_SESSION['idD'] );
unset($_SESSION['IdC']);
unset($_SESSION['idR'] );
unset($_SESSION['idE'] );
unset($_SESSION['periodo']);
unset($_SESSION['ayo']);

session_destroy();
header("Location:../index.php");

<?php
session_start();
unset($_SESSION['administradores']);
unset($_SESSION['nombre'] );
unset($_SESSION['puesto'] );
unset($_SESSION['accesos'] );
unset($_SESSION['UADM']);
unset($_SESSION['Tipo'] );

unset($_SESSION['periodo']);
unset($_SESSION['ayo']);

session_destroy();
header("Location:../index.php");








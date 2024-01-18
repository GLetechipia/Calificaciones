<?php
$ini = parse_ini_file('config.ini'); //se lee archivo de configuración...
$dsn = $ini['odbc'];
$usuario = $ini['username'];
$clave = $ini['password'];
$cid = odbc_connect($dsn, $usuario, $clave); //establece $cid para la conexión

if (!$cid) { //verifica si se realizo la conexión
    die("Error de conexión: " . odbc_errormsg());
}


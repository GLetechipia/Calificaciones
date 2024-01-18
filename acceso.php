<?php
session_start();

function num_row_counter($resultado)
{
    $items = 0;
    while ($row = odbc_fetch_array($resultado))
        $items++;
    return $items;
}

if (isset($_POST["Usuario"], $_POST["Contrasena"])) {
    require_once('conect.odbc.php'); //crea la conexión para la base de datos

    $consulta = "select IdMast, ape, nom, idD from docentes where Login='" . addslashes($_POST["Usuario"]) . "' and CveSice='" . addslashes($_POST["Contrasena"]) . "';";
    $result = odbc_exec($cid, $consulta);

    if (num_row_counter($result) > 0) {
        odbc_fetch_row($result);
        $_SESSION['IdMast'] = odbc_result($result, 1);
        $_SESSION['apellidos'] = odbc_result($result, 2);
        $_SESSION['nombre'] = odbc_result($result, 3);
        $_SESSION['idD'] = odbc_result($result, 4);
        $_SESSION['periodo'] = $ini['periodo'];
        $_SESSION['ayo'] = $ini['ayo'];

        odbc_free_result($result);
        odbc_close($cid);
        echo 'success';
    } else {
        odbc_free_result($result);
        odbc_close($cid);
        echo 'Usuario o contraseña incorrectos. Inténtelo nuevamente.';
    }
} else {
    echo 'Error: Datos de acceso no proporcionados.';
}

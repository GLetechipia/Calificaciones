<?php
session_start();
if (!isset($_SESSION['IdMast'])) {
    header("Location: index.php");
    exit();
}

require_once('conect.odbc.php'); // Asegúrate de tener esta conexión correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos del formulario
    $sfkey = $_POST['sFKey'];
    $nombreTema = $_POST['nombreTema'];

    // Eliminar el tema de la tabla 'temasporcalificar'
    $deleteTemaQuery = "DELETE FROM temasporcalificar WHERE sfkey='$sfkey' AND nombretema='$nombreTema'";
    odbc_exec($cid, $deleteTemaQuery);

    // Eliminar las calificaciones asociadas al tema
    $deleteCalificacionesQuery = "
        DELETE FROM calificaciontema
        WHERE idtemacalificar IN (SELECT idtemacalificar FROM temasporcalificar WHERE sfkey='$sfkey' AND nombretema='$nombreTema')
    ";
    odbc_exec($cid, $deleteCalificacionesQuery);

    echo "Tema y calificaciones asociadas quitados exitosamente."; // Puedes cambiar el mensaje según tus necesidades
} else {
    echo "Error: Acceso no permitido.";
}
odbc_close($cid);
?>

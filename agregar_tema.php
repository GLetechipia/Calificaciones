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

    // Insertar el nuevo tema en la tabla 'temasporcalificar'
    $insertTemaQuery = "INSERT INTO temasporcalificar (sfkey, nombretema) VALUES ('$sfkey', '$nombreTema')";
    odbc_exec($cid, $insertTemaQuery);

    // Insertar las calificaciones para el nuevo tema
    $insertCalificacionesQuery = "
        INSERT INTO calificaciontema (idtemacalificar, numcont)
        SELECT TemasPorCalificar.idTemaCalificar, Listas.NumCont
        FROM Listas, TemasPorCalificar 
        WHERE Listas.sfkey=TemasPorCalificar.sfkey AND 
              Listas.sfkey='$sfkey' AND 
              TemasPorCalificar.idtemacalificar IN (SELECT MAX(idtemacalificar) FROM temasporcalificar WHERE sfkey='$sfkey')
    ";
    odbc_exec($cid, $insertCalificacionesQuery);

    echo "Tema agregado exitosamente."; // Puedes cambiar el mensaje según tus necesidades
} else {
    echo "Error: Acceso no permitido.";
}
?>

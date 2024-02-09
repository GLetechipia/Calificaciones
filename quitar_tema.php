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
    // Eliminar las calificaciones asociadas al tema
    /*
    $deleteCalificacionesQuery = "
        DELETE FROM calificaciontema
        WHERE idtemacalificar IN (SELECT idtemacalificar FROM temasporcalificar WHERE sfkey='$sfkey' AND nombretema='$nombreTema')
    ";
    odbc_exec($cid, $deleteCalificacionesQuery);

    //obtener identificador del idTemacalificar para eliminar todo lo asociado..
    $Ridtemacalificar = odbc_exec($cid,"Select idTemacalificar from temasporcalificar WHERE sfkey='$sfkey' AND nombretema='$nombreTema");
    echo $Ridtemacalificar;
    $Aidtemacalificar = odbc_fetch_array($Ridtemacalificar);
    $idtemacalificar = $Aidtemacalificar['idTemacalificar'];

*/

    //select idrubrotema from rubrodetema where idtemacalificar= consulta anterior

    $eliminarcalidicacionestemas ="";
    
    //eliminar las califiaciones de rubros asociados al temas con los idrubrotema 
    $eliminarrubros="";
    
    // Eliminar el tema de la tabla 'temasporcalificar'
    $deleteTemaQuery = "DELETE FROM temasporcalificar WHERE sfkey='$sfkey' AND nombretema='$nombreTema'";
    odbc_exec($cid, $deleteTemaQuery);


    echo "Tema y calificaciones asociadas quitados exitosamente."; // Puedes cambiar el mensaje según tus necesidades
} else {
    echo "Error: Acceso no permitido.";
}
odbc_close($cid);

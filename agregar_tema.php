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

    // Añadir nuevo rubro por default con el 100%
    // Obtener el máximo idtemacalificar del sfkey correspondiente
    $maxidtemacalificar = "SELECT MAX(idtemacalificar) as maximo FROM temasporcalificar WHERE sfkey='$sfkey'";
    $Rmaxidtemacalificar = odbc_exec($cid, $maxidtemacalificar);

    $Result_maxidtemacalificar = odbc_fetch_array($Rmaxidtemacalificar);
    $Nmaxidtemacalificar = $Result_maxidtemacalificar['maximo'];

    // Insertar rubro del nuevo tema
    $insertarRubroTema = "INSERT INTO RubrodeTema(idTemaCalificar, nombreRubro, porcentaje)
                      VALUES ($Nmaxidtemacalificar, 'Default', 100)";
    odbc_exec($cid, $insertarRubroTema);

    // Insertar (crear) la lista de alumnos con las calificaciones del nuevo rubro
    $maxirubrotema_query = "SELECT MAX(idrubrotema) as maximo FROM rubrodeTema WHERE idtemacalificar=$Nmaxidtemacalificar";
    $maxirubrotema_result = odbc_exec($cid, $maxirubrotema_query);

    $Result_maxirubrotema = odbc_fetch_array($maxirubrotema_result);
    $maxirubrotema = $Result_maxirubrotema['maximo'];

    //$insertarCalifiacionesRubros = "insert into calificacionRubro(idRubroTema,NumCont)";

    $insertCalificacionesQuery = "
        INSERT INTO calificacionRubro (idrubroTema, numcont)
        SELECT RubrodeTema.idRubroTema, Listas.NumCont
        FROM (Listas INNER JOIN TemasPorCalificar ON Listas.sFKey = TemasPorCalificar.sFKey) 
        INNER JOIN RubrodeTema ON TemasPorCalificar.idTemaCalificar = RubrodeTema.idTemaCalificar
        WHERE (((RubrodeTema.idRubroTema)=$maxirubrotema));
    ";
    odbc_exec($cid, $insertCalificacionesQuery);

    echo $insertCalificacionesQuery."Tema agregado exitosamente.".$maxidtemacalificar."####".$insertarRubroTema."#####".$maxirubrotema_query."####".$insertCalificacionesQuery; // Puedes cambiar el mensaje según tus necesidades
} else {
    echo "Error: Acceso no permitido.";
}

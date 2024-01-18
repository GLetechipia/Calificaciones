<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $sfkeyArray = $_POST['sfkey'];
    $nctrlArray = $_POST['nctrl'];
    $tipocalArray = $_POST['tipocal'];
    $calificacionArray = $_POST['calificacion'];

    // Establecer la conexión ODBC
    require_once('conect.odbc.php'); //crea la conexión para la base de datos

    // Iterar sobre los datos y realizar la actualización
    foreach ($sfkeyArray as $key => $sfkey) {
        $numeroControl = $nctrlArray[$key];
        $tipoCalificacion = $tipocalArray[$key];
        $calificacion = $calificacionArray[$key];

        // Realizar la actualización directamente (ten en cuenta el riesgo de inyección de SQL)
        $consultaUpdate = "UPDATE listas SET TipoCal = '$tipoCalificacion', Calif = '$calificacion' WHERE sfkey = '$sfkey' AND numcont = '$numeroControl'";
        $resultadoUpdate = odbc_exec($cid, $consultaUpdate);

        if (!$resultadoUpdate) {
            die("Error al realizar la actualización: " . odbc_errormsg());
        }
    }

    // Cerrar la conexión
    odbc_close($cid);

    echo "Datos actualizados correctamente";
} else {
    echo "Error: Método de solicitud no válido";
}
?>

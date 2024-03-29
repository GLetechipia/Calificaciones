<?php
// guardar_calificaciones_rubros.php
// Aquí debes conectar con tu base de datos mediante ODBC y obtener $cid
require_once('conect.odbc.php'); // crea la conexión para la base de datos

// Obtener el ID del tema desde el POST
$idTema = $_POST['idTema'];

// Obtener las calificaciones del formulario
$calificaciones = $_POST['calificaciones'];

// Iterar sobre las calificaciones y guardar en la base de datos
foreach ($calificaciones as $numCont => $rubros) {
    foreach ($rubros as $idRubro => $calificacion) {
        // Consulta para guardar la calificación en la tabla calificacionRubro
        $consultaGuardarCalificacion = "UPDATE calificacionRubro
                                       SET calificacion = $calificacion
                                       WHERE NumCont = '$numCont'
                                       AND idRubroTema = $idRubro;";
        odbc_exec($cid, $consultaGuardarCalificacion);
    }
}

// Consulta para obtener las calificaciones
$sql = "SELECT TemasPorCalificar.idTemaCalificar, CalificacionRubro.NumCont, Sum(CalificacionRubro.Calificacion*RubrodeTema.porcentaje/100) AS Calificacion
FROM TemasPorCalificar
INNER JOIN (RubrodeTema INNER JOIN CalificacionRubro ON RubrodeTema.idRubroTema = CalificacionRubro.idRubroTema) ON TemasPorCalificar.idTemaCalificar = RubrodeTema.idTemaCalificar
GROUP BY CalificacionRubro.NumCont, TemasPorCalificar.idTemaCalificar
HAVING TemasPorCalificar.idTemaCalificar = $idTema";

$result = odbc_exec($cid, $sql);

if ($result) {
    // Recorre los resultados y actualiza la tabla calificaciontema
    while ($row = odbc_fetch_array($result)) {
        $idTemaCalificar = $row["idTemaCalificar"];
        $numCont = $row["NumCont"];
        $calificacion = round($row["Calificacion"]);

        // Actualiza la tabla calificaciontema con las calificaciones obtenidas
        $updateSql = "UPDATE calificaciontema 
              SET calificacion = $calificacion 
              WHERE idtemacalificar = $idTemaCalificar AND numcont = '$numCont'";
    //echo $updateSql;
        $updateResult = odbc_exec($cid, $updateSql);
    }
} else {
    echo "Error en la consulta: " . odbc_errormsg($cid);
}



odbc_close($cid);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Calificaciones Parciales</title>
    <!-- Agregar jQuery (asegúrate de que el archivo esté disponible en tu servidor) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <script>
        // Obtener el ID del tema desde PHP
        var idTema = <?php echo json_encode($idTema); ?>;

        // Realizar la redirección mediante AJAX
        $.ajax({
            type: 'POST',
            url: 'ver_rubros.php',
            data: {
                idTema: idTema
            }, // Pasar el idTema como parámetro
            success: function(response) {
                // Puedes manejar la respuesta si es necesario
                $('body').html(response);
                //console.log('Redirección exitosa:', response);
            },
            error: function(error) {
                // Puedes manejar errores si es necesario
                console.error('Error en la redirección:', error);
            },
            complete: function() {
                // Opcional: Puedes realizar acciones después de completar la redirección
            }
        });
    </script>
</body>

</html>
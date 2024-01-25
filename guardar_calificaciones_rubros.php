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
                                       SET calificacion = '$calificacion'
                                       WHERE NumCont = '$numCont'
                                       AND idRubroTema = '$idRubro';";

        odbc_exec($cid, $consultaGuardarCalificacion);
    }
}

odbc_close($cid);


exit();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guardando Calificaciones...</title>
    <!-- Agregar jQuery (asegúrate de que el archivo esté disponible en tu servidor) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <script>
        // Realizar la redirección mediante AJAX
        $.ajax({
            type: 'POST',
            url: 'ver_rubros.php',
            data: <?php echo json_encode($redirectData); ?>,
            success: function(response) {
                // Puedes manejar la respuesta si es necesario
                console.log('Redirección exitosa:', response);
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
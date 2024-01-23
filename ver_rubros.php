<?php
// Aquí debes conectar con tu base de datos mediante ODBC y obtener $cid
require_once('conect.odbc.php'); //crea la conexión para la base de datos
// Verificar si el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['usuario'])) {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    //header('Location: login.php');
    //exit();
}

// Obtener el ID del tema desde el POST
$idTema = $_POST['idTema'];

// Consulta para obtener los datos del tema
$consultaTema = "SELECT Materias.Materia, TemasPorCalificar.nombreTema
                FROM (Materias INNER JOIN Grupos ON (Materias.IdM = Grupos.IdM) AND (Materias.IdR = Grupos.IdR) AND (Materias.IdC = Grupos.IdC) AND (Materias.IdD = Grupos.IdD)) 
                INNER JOIN TemasPorCalificar ON Grupos.sFKey = TemasPorCalificar.sFKey
                WHERE (((TemasPorCalificar.idTemaCalificar)=$idTema));";

$resultTema = odbc_exec($cid, $consultaTema);
$datosTema = odbc_fetch_array($resultTema);

// Consulta para obtener las calificaciones de los alumnos para el tema específico
$consultaCalificaciones = "SELECT Alumnos.numcont, Alumnos.nom, Alumnos.ape, CalificacionTema.calificacion
                           FROM Alumnos
                           INNER JOIN CalificacionTema ON Alumnos.numcont = CalificacionTema.NumCont
                           WHERE CalificacionTema.idTemaCalificar = $idTema;";

$resultCalificaciones = odbc_exec($cid, $consultaCalificaciones);

// Consulta para obtener los rubros y porcentajes asociados al tema
$consultaRubros = "SELECT RubrodeTema.nombrerubro, RubrodeTema.porcentaje
                   FROM RubrodeTema
                   WHERE RubrodeTema.IdTemaCalificar = $idTema;";

$resultRubros = odbc_exec($cid, $consultaRubros);

// Crear un array asociativo con los rubros y sus porcentajes
$rubros = array();
while ($rubro = odbc_fetch_array($resultRubros)) {
    $rubros[$rubro['nombrerubro']] = $rubro['porcentaje'];
}

// Construir la tabla HTML
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones</title>
</head>
<body>

<h1>Calificaciones - <?php echo $datosTema['Materia'] ?> - <?php echo $datosTema['nombreTema'] ?></h1>

<table border="1">
    <tr>
        <th>numcont</th>
        <th>Nombre</th>
        <?php
        // Agregar encabezados de rubros dinámicamente
        foreach ($rubros as $rubro => $porcentaje) {
            echo "<th>$rubro $porcentaje%</th>";
        }
        ?>
    </tr>
    
    <?php
    // Mostrar datos de calificaciones
    while ($calificacion = odbc_fetch_array($resultCalificaciones)) {
        echo "<tr>";
        echo "<td>{$calificacion['numcont']}</td>";
        echo "<td>{$calificacion['ape']}, {$calificacion['nom']}</td>";

        // Obtener las calificaciones específicas de cada rubro
        foreach ($rubros as $rubro => $porcentaje) {
            // Aquí deberías hacer la consulta adecuada para obtener la calificación de cada rubro
            // Puedes utilizar JOIN con la tabla calificacionRubro y filtrar por idTema y numcont
            // y mostrar la calificación en el lugar correcto de la tabla
            echo "<td>Calificación</td>";
        }

        echo "</tr>";
    }
    ?>
</table>

</body>
</html>

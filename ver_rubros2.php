<?php
//ver_rubros.php
// Aquí debes conectar con tu base de datos mediante ODBC y obtener $cid
require_once('conect.odbc.php'); //crea la conexión para la base de datos

// Verificar si el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['usuario'])) {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    // header('Location: login.php');
    // exit();
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
                           WHERE CalificacionTema.idTemaCalificar = $idTema order by alumnos.ape;";

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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SICENETXv2</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <script src="vendor/jquery/jquery.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>

    <h1>Calificaciones - <?php echo $datosTema['Materia'] ?> - <?php echo $datosTema['nombreTema'] ?></h1>

    <form action="guardar_calificaciones_rubros.php" method="post">
        <!-- ... Tu formulario y tabla existente ... -->
    </form>

    <table border="1">
        <tr>
            <th>Numero de Control</th>
            <th>Nombre</th>
            <?php
            // Agregar encabezados de rubros dinámicamente
            foreach ($rubros as $rubro => $porcentaje) {
                echo "<th>{$rubro} {$porcentaje}%</th>";
            }
            ?>
            <th>Nuevo Rubro</th>
        </tr>

        <?php
        // Mostrar datos de calificaciones
        while ($calificacion = odbc_fetch_array($resultCalificaciones)) {
            echo "<tr>";
            echo "<td>{$calificacion['numcont']}</td>";
            echo "<td>{$calificacion['ape']} {$calificacion['nom']}</td>";

            // Obtener las calificaciones específicas de cada rubro
            foreach ($rubros as $rubro => $porcentaje) {
                // Consulta para obtener la calificación de cada rubro
                $consultaCalificacionRubro = "SELECT calificacionRubro.calificacion, calificacionRubro.idrubrotema
                                               FROM calificacionRubro
                                               WHERE calificacionRubro.NumCont = '{$calificacion['numcont']}' 
                                               AND calificacionRubro.idRubroTema IN (
                                                   SELECT RubrodeTema.idRubroTema
                                                   FROM RubrodeTema
                                                   WHERE RubrodeTema.nombrerubro = '$rubro' 
                                                   AND RubrodeTema.IdTemaCalificar = $idTema
                                               );";

                $resultCalificacionRubro = odbc_exec($cid, $consultaCalificacionRubro);
                $calificacionRubro = odbc_fetch_array($resultCalificacionRubro);

                // Agregar campos de entrada editables
                echo "<td><input type='text' name='calificaciones[{$calificacion['numcont']}][{$calificacionRubro['idrubrotema']}]' value='{$calificacionRubro['calificacion']}'></td>";
            }

            // Agregar columna para el nuevo rubro
            echo "<td><input type='text' name='calificaciones[{$calificacion['numcont']}][nuevoRubro]' value=''></td>";

            echo "</tr>";
        }
        odbc_close($cid);
        ?>
    </table>

    <!-- Resto del código... -->
        <script>
// Ajusta la función agregarFilaRubro para agregar la columna Nuevo Rubro
function agregarFilaRubro(rubro, porcentaje) {
    filaIndex++;

    // Construir la nueva fila
    var nuevaFila = `
        <div class="fila" id="fila-${filaIndex}">
            <label for="nombre${filaIndex}">Nombre:</label>
            <input type="text" id="nombre${filaIndex}" name="nombre${filaIndex}" required value="${rubro}">
            <label for="porcentaje${filaIndex}">Porcentaje:</label>
            <input type="number" id="porcentaje${filaIndex}" name="porcentaje${filaIndex}" min="0" max="100" required value="${porcentaje}">
            <button type="button" class="eliminar-fila">Eliminar</button>
        </div>`;

    // Agregar la nueva fila al contenedor
    $("#contenedor-nombres-porcentajes").append(nuevaFila);

    // Asignar el evento al botón de eliminar fila
    $("#fila-" + filaIndex + " .eliminar-fila").on("click", function() {
        if ($("#contenedor-nombres-porcentajes .fila").length > 1) {
            $(this).closest(".fila").remove();
        } else {
            alert("Debe haber al menos un rubro.");
        }
    });
}

// Ajusta la función cargarRubros para manejar los encabezados
function cargarRubros() {
    // ... Código anterior ...

    // Agregar columna Nuevo Rubro
    var nuevoRubroHeader = `<th>Nuevo Rubro</th>`;
    $("#contenedor-nombres-porcentajes").before(nuevoRubroHeader);

    // Agregar el evento al nuevo input de Nuevo Rubro
    $("#contenedor-nombres-porcentajes").prev().find("input").on("change", function() {
        agregarFilaRubro($(this).val(), 0);
        $(this).val("");  // Limpiar el input después de agregar la fila
    });
}

// ... Resto del código ...


        </script>
</body>
</html>

<?php
// ver_rubros.php
// Aquí debes conectar con tu base de datos mediante ODBC y obtener $cid
require_once('conect.odbc.php'); // crea la conexión para la base de datos

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
$consultaRubros = "SELECT RubrodeTema.idRubroTema, RubrodeTema.nombrerubro, RubrodeTema.porcentaje
                   FROM RubrodeTema
                   WHERE RubrodeTema.IdTemaCalificar = $idTema;";

$resultRubros = odbc_exec($cid, $consultaRubros);

// Crear un array asociativo con los rubros y sus porcentajes
$rubros = array();
while ($rubro = odbc_fetch_array($resultRubros)) {
    $rubros[$rubro['idRubroTema']] = array(
        'nombre' => $rubro['nombrerubro'],
        'porcentaje' => $rubro['porcentaje']
    );
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
</head>

<body>

    <h1>Calificaciones - <?php echo $datosTema['Materia'] ?> - <?php echo $datosTema['nombreTema'] ?></h1>

    <form action="guardar_calificaciones_rubros.php" method="post">
        <input type="hidden" name="idTema" value="<?php echo $idTema; ?>">

        <table border="1">
            <tr>
                <th>Numero de Control</th>
                <th>Nombre</th>
                <?php
                // Agregar encabezados de rubros dinámicamente
                foreach ($rubros as $idRubro => $rubro) {
                    echo "<th>{$rubro['nombre']} {$rubro['porcentaje']}%</th>";
                }
                ?>
            </tr>

            <?php
            // Mostrar datos de calificaciones
            while ($calificacion = odbc_fetch_array($resultCalificaciones)) {
                echo "<tr>";
                echo "<td>{$calificacion['numcont']}</td>";
                echo "<td>{$calificacion['ape']} {$calificacion['nom']}</td>";

                // Obtener las calificaciones específicas de cada rubro
                foreach ($rubros as $idRubro => $rubro) {
                    // Consulta para obtener la calificación de cada rubro
                    $consultaCalificacionRubro = "SELECT calificacionRubro.calificacion
                                               FROM calificacionRubro
                                               WHERE calificacionRubro.NumCont = '{$calificacion['numcont']}' 
                                               AND calificacionRubro.idRubroTema = $idRubro;";

                    $resultCalificacionRubro = odbc_exec($cid, $consultaCalificacionRubro);
                    $calificacionRubro = odbc_fetch_array($resultCalificacionRubro);

                    // Agregar campos de entrada editables
                    echo "<td><input class='form-control' type='text' name='calificaciones[{$calificacion['numcont']}][$idRubro]' value='{$calificacionRubro['calificacion']}'></td>";
                }

                echo "</tr>";
            }
            odbc_close($cid);
            ?>
        </table>

        <button type="submit" class="btn btn-primary">Guardar Calificaciones</button>
    </form>

    <!-- Botón para abrir el modal de rubros -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rubrosModal" onclick="cargarRubros()">
        Agregar/Editar Rubros
    </button>

    <!-- Resto del código (tabla, formulario, etc.) -->

    <!-- Modal de rubros -->
    <div class="modal fade" id="rubrosModal" tabindex="-1" role="dialog" aria-labelledby="rubrosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rubrosModalLabel">Agregar/Editar Rubros</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenido del modal -->
                    <form method="post" action="guardar_rubros_porcentaje.php" id="formularioRubros">
                        <div id="contenedor-nombres-porcentajes">
                            <div class="fila" id="fila-1">
                                <label for="nombre1">Nombre 1:</label>
                                <input type="text" id="nombre1" name="nombre1"class="form-control" required>
                                <label for="porcentaje1">Porcentaje 1:</label>
                                <input type="number" id="porcentaje1" name="porcentaje1" class="form-control" min="0" max="100" required>
                                <input type="hidden" name="idRubro1" value="<?php echo uniqid(); ?>">
                                <button type="button" class="eliminar-fila btn btn-danger" style="display:none">Eliminar</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" id="agregar-fila" class="btn btn-success mr-2" title="Agrega un nuevo rubro al tema actual">
                                <i class="bi bi-plus"></i> Rubro
                            </button>
                            <input type="submit" value="Guardar" class="btn btn-success" title="Guarda los rubros del presente tema">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p class="mensaje-eliminar-rubro text-danger">
                        Recuerde que si elimina un rubro, las calificaciones relacionadas a este se eliminarán.
                    </p>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->


    <script>
        var filaIndex;

        $(document).ready(function() {
            $("#agregar-fila").on("click", function() {
                filaIndex++;
                var nuevaFila = $("#fila-1").clone();
                nuevaFila.attr("id", "fila-" + filaIndex);

                nuevaFila.find("label").each(function() {
                    var nuevoFor = $(this).attr("for").replace("nombre1", "nombre" + filaIndex).replace("porcentaje1", "porcentaje" + filaIndex);
                    $(this).attr("for", nuevoFor);
                });

                nuevaFila.find("input").each(function() {
                    var nuevoId = $(this).attr("id").replace("nombre1", "nombre" + filaIndex).replace("porcentaje1", "porcentaje" + filaIndex);
                    var nuevoName = $(this).attr("name").replace("nombre1", "nombre" + filaIndex).replace("porcentaje1", "porcentaje" + filaIndex);
                    $(this).attr("id", nuevoId).attr("name", nuevoName).val("");
                });

                nuevaFila.find(".eliminar-fila").show();
                nuevaFila.find(".eliminar-fila").on("click", function() {
                    if ($("#contenedor-nombres-porcentajes .fila").length > 1) {
                        $(this).closest(".fila").remove();
                    } else {
                        alert("Debe haber al menos un rubro.");
                    }
                });

                $("#contenedor-nombres-porcentajes").append(nuevaFila);
            });
            $("form").on("submit", function() {
                // Validar suma de porcentajes
                var totalPorcentajes = 0;
                $("input[name^='porcentaje']").each(function() {
                    totalPorcentajes += parseFloat($(this).val());
                });

                if (totalPorcentajes !== 100) {
                    alert("La suma de los porcentajes debe ser 100%.");
                    return false; // Detener el envío del formulario
                }

                // Continuar con el envío del formulario si la validación es exitosa
                return true;
            });

        });

        function cargarRubros() {
            $.ajax({
                type: 'POST',
                url: 'obtener_rubros.php', // Reemplaza esto con la URL correcta de tu servidor
                data: {
                    idTema: <?php echo $idTema; ?>
                },
                dataType: 'json',
                success: function(data) {
                    // Verificar si la respuesta del servidor contiene datos válidos
                    if (data && data.idRubros && data.rubros && data.porcentajes &&
                        data.idRubros.length === data.rubros.length &&
                        data.rubros.length === data.porcentajes.length) {

                        // Eliminar filas existentes en el modal
                        $("#contenedor-nombres-porcentajes").empty();
                        filaIndex = 0;

                        // Crear filas para cada rubro y porcentaje
                        for (var i = 0; i < data.rubros.length; i++) {
                            agregarFilaRubro(data.idRubros[i], data.rubros[i], data.porcentajes[i]);
                        }
                    } else {
                        alert('No hay rubros disponibles para este tema.');
                    }
                },
                error: function() {
                    alert('Error al cargar los rubros');
                }
            });
        }

        function agregarFilaRubro(idRubro, rubro, porcentaje) {
            // Construir la nueva fila
            var nuevaFila = `
            <div class="fila" id="fila-${filaIndex}">
                <label for="nombre${filaIndex}">Nombre:</label>
                <input type="text" id="nombre${filaIndex}" name="nombre${filaIndex}" required value="${rubro}">
                <label for="porcentaje${filaIndex}">Porcentaje :</label>
                <input type="number" id="porcentaje${filaIndex}" name="porcentaje${filaIndex}" min="0" max="100" required value="${porcentaje}">
                <input type="hidden" name="idRubro${filaIndex}" value="${idRubro}">
                <button type="button" class="eliminar-fila btn btn-danger">Eliminar</button>
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

        
    </script>
</body>

</html>
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
$_SESSION['idTema'] = $idTema;

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
                    echo "<td><input class='form-control' type='text' name='calificaciones[{$calificacion['numcont']}][$idRubro]' value='{$calificacionRubro['calificacion']}' maxlength='3' style='width: 60px; text-align:right'></td>";
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
                    <form method="post" action="guardar_rubros.php" id="formularioRubros">
                        <div id="contenedor-nombres-porcentajes">
                            <!-- La primera fila se mantiene, solo para clonarla después -->
                            <div class="fila" id="fila-0">
                                <label for="nombre0">Nombre:</label>
                                <input type="text" class="nombre" name="nombres[]" required value="Asistencia">
                                <label for="porcentaje0">Porcentaje :</label>
                                <input type="number" class="porcentaje" name="porcentajes[]" min="0" max="100" required value="50" style="width: 60px; text-align:right">
                                <input type="hidden" class="idRubro" name="idRubros[]" value="2">
                                <button type="button" class="eliminar-fila btn btn-danger" style="display:none">Eliminar</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" id="agregar-fila" class="btn btn-success mr-2">
                                <i class="bi bi-plus"></i> Agregar Nombre y Porcentaje
                            </button>
                            <input type="submit" value="Guardar Rubros" class="btn btn-success">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p class="mensaje-eliminar-rubro text-danger">
                        Recuerde que si elimina un rubro, las calificaciones relacionadas a este se eliminarán.
                    </p>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->


    <script>
        var filaIndex = 1;

        $(document).ready(function() {
            $("#agregar-fila").on("click", function() {
                agregarNuevaFila();
            });

            $("#formularioRubros").on("submit", function(event) {
                event.preventDefault(); // Prevenir el envío tradicional del formulario

                // Validar suma de porcentajes
                var totalPorcentajes = 0;
                var rubrosData = [];

                $("input[name^='porcentajes']").each(function(index) {
                    var porcentaje = parseFloat($(this).val());
                    totalPorcentajes += porcentaje;

                    var nombre = $("input[name^='nombres']")[index].value;
                    var idRubro = $("input[name^='idRubros']")[index].value;

                    rubrosData.push({
                        nombre: nombre,
                        porcentaje: porcentaje,
                        idRubro: idRubro
                    });
                });

                if (totalPorcentajes !== 100) {
                    alert("La suma de los porcentajes debe ser 100%.");
                    return false; // Detener el envío del formulario
                }

                // Enviar datos de rubros mediante AJAX
                $.ajax({
                    type: 'POST',
                    url: 'guardar_rubros.php', // Reemplaza con la URL correcta
                    data: {
                        rubrosData: JSON.stringify(rubrosData),
                        idTema: <?php echo $idTema; ?>
                    },
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message); // Muestra el mensaje de respuesta
                        //location.reload(); // Recarga la página después de guardar los rubros
                    },
                    error: function() {
                        console.error("Error en la solicitud AJAX:");
                        alert('Error al enviar los rubros'); // Maneja errores de AJAX
                    }

                });
            });
        });

        function agregarNuevaFila() {
            // Crear una nueva fila y ajustar los atributos
            var nuevaFila = $("<div class='fila'>");
            nuevaFila.attr("id", "fila-" + filaIndex);

            // Añadir elementos HTML a la nueva fila
            nuevaFila.append("<label for='nombre" + filaIndex + "'>Nombre:</label>");
            nuevaFila.append("<input type='text' id='nombre" + filaIndex + "' name='nombres[]' required>");
            nuevaFila.append("<label for='porcentaje" + filaIndex + "'>Porcentaje :</label>");
            nuevaFila.append("<input type='number' id='porcentaje" + filaIndex + "' name='porcentajes[]' min='0' max='100' required maxlength='3' style='width: 60px; text-align:right'>");
            nuevaFila.append("<input type='hidden' name='idRubros[]'>");
            nuevaFila.append("<button type='button' class='eliminar-fila btn btn-danger'>Eliminar</button>");

            nuevaFila.find(".eliminar-fila").show();

            // Agregar la nueva fila al contenedor
            $("#contenedor-nombres-porcentajes").append(nuevaFila);

            // Asignar el evento al botón de eliminar fila
            nuevaFila.find(".eliminar-fila").on("click", function() {
                if ($("#contenedor-nombres-porcentajes .fila").length > 1) {
                    $(this).closest(".fila").remove();
                } else {
                    alert("Debe haber al menos un rubro.");
                }
            });

            filaIndex++;
        }
    </script>
    <script>
        function cargarRubros() {
            console.log("Cargando rubros...");
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
                            filaIndex++;
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
            // Crear una nueva fila y ajustar los atributos
            var nuevaFila = $("<div class='fila'>");
            nuevaFila.attr("id", "fila-" + filaIndex);

            // Añadir elementos HTML a la nueva fila
            nuevaFila.append("<label for='nombre" + filaIndex + "'>Nombre:</label>");
            nuevaFila.append("<input type='text' id='nombre" + filaIndex + "' name='nombres[]' required value='" + rubro + "'>");
            nuevaFila.append("<label for='porcentaje" + filaIndex + "'>Porcentaje :</label>");
            nuevaFila.append("<input type='number' id='porcentaje" + filaIndex + "' name='porcentajes[]' min='0' max='100' required value='" + porcentaje + "' maxlength='3' style='width: 60px; text-align:right'>");
            nuevaFila.append("<input type='hidden' name='idRubros[]' value='" + idRubro + "'>");
            nuevaFila.append("<button type='button' class='eliminar-fila btn btn-danger'>Eliminar</button>");

            nuevaFila.find(".eliminar-fila").show();

            // Agregar la nueva fila al contenedor
            $("#contenedor-nombres-porcentajes").append(nuevaFila);

            // Asignar el evento al botón de eliminar fila
            nuevaFila.find(".eliminar-fila").on("click", function() {
                if ($("#contenedor-nombres-porcentajes .fila").length > 1) {
                    $(this).closest(".fila").remove();
                } else {
                    alert("Debe haber al menos un rubro.");
                }
            });

            filaIndex++;
        }
    </script>


</body>

</html>
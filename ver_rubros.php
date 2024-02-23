<?php
// ver_rubros.php
// Aquí debes conectar con tu base de datos mediante ODBC y obtener $cid
require_once('conect.odbc.php'); // crea la conexión para la base de datos

// Verificar si el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['IdMast'])) {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    header('Location: index.php');
    exit();
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

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <!---- AQUI VA LA IMAGEN DEL LOGO DEL TEC...---->

                <img class="mb-4" src="img/LogoTecLoretoOriginal.svg" style="margin-top: 20px; height: 40px; width: 40px;">


                <div class="sidebar-brand-text mx-3">ITS Loreto</div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">



            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                <?php echo "Periodo " . $_SESSION['periodo'] . " - " . $_SESSION['ayo'];
                //echo "<br> http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                ?>

            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Gestiones</span>
                </a>
                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Calificaciones</h6>
                        <a class="collapse-item active" href="parciales.php">Parciales</a>
                        <a class="collapse-item" href="finales.php">Finales</a>
                        <h6 class="collapse-header">Académica</h6>
                        <a class="collapse-item" href="instrumentaciones.php">Instrumentaciones</a>
                        <a class="collapse-item" href="tutorias.php">Tutorías</a>
                        <a class="collapse-item " href="asesorias.php">Asesorías</a>
                    </div>
                </div>

            </li>
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <p><?php echo "Periodo " . $_SESSION['periodo'] . " - " . $_SESSION['ayo']; ?></p>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                       

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->


                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Salir
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800"><a href="parciales.php" class="btn btn-info">PARCIALES</a> - <a href="#" class="btn btn-info"><?php echo $datosTema['Materia'] ?></a> - <a href="#" class="btn btn-info"> <?php echo $datosTema['nombreTema'] ?></a></h1>

                    <form action="guardar_calificaciones_rubros.php" method="post">
                        <input type="hidden" name="idTema" value="<?php echo $idTema; ?>">

                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <tr>
                                <th>Numero de Control</th>
                                <th>Nombre</th>
                                <?php
                                // Agregar encabezados de rubros dinámicamente
                                foreach ($rubros as $idRubro => $rubro) {
                                    echo "<th>{$rubro['nombre']} <br> {$rubro['porcentaje']}%</th>";
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
                        <!-- Botón para abrir el modal de rubros -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rubrosModal" onclick="cargarRubros()">
                            Agregar/Editar Rubros
                        </button>
                    </form>

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
                                                <input type="hidden" class="idRubro" name="idRubros[]" value="2">&nbsp;
                                                <button type="button" class="eliminar-fila btn btn-danger" style="display:none">Eliminar</button><br><br>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" id="agregar-fila" class="btn btn-success mr-2">
                                                <i class="bi bi-plus"></i> Agregar
                                            </button>
                                            <input type="submit" value="Guardar" class="btn btn-success bi bi-plus">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <p class="mensaje-eliminar-rubro text-danger">
                                        Recuerde que, si elimina un rubro, las calificaciones relacionadas a éste se eliminarán.
                                    </p>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bootstrap JS -->
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; TecNM Campus Loreto - Letechipía 2024 &reg;</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">List@ para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Seleccionaste "Salir", estas segur@ de terminar la sesión.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="cerrar.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

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
                        location.reload(); // Recarga la página después de guardar los rubros
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
            nuevaFila.append("<label for='nombre" + filaIndex + "'>Nombre: </label>");
            nuevaFila.append("<input type='text' id='nombre" + filaIndex + "' name='nombres[]' required>");
            nuevaFila.append("<label for='porcentaje" + filaIndex + "'>% : </label>");
            nuevaFila.append("<input type='number' id='porcentaje" + filaIndex + "' name='porcentajes[]' min='0' max='100' required maxlength='3' style='width: 60px; text-align:right'>");
            nuevaFila.append("<input type='hidden' name='idRubros[]'>");
            nuevaFila.append("&nbsp;<button type='button' class='eliminar-fila btn btn-danger'>Eliminar</button><br><br>");

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
            nuevaFila.append("<label for='nombre" + filaIndex + "'>Nombre: </label>");
            nuevaFila.append("<input type='text' id='nombre" + filaIndex + "' name='nombres[]' required value='" + rubro + "'>");
            nuevaFila.append("<label for='porcentaje" + filaIndex + "'> %: </label>");
            nuevaFila.append("<input type='number' id='porcentaje" + filaIndex + "' name='porcentajes[]' min='0' max='100' required value='" + porcentaje + "' maxlength='3' style='width: 60px; text-align:right'>");
            nuevaFila.append("<input type='hidden' name='idRubros[]' value='" + idRubro + "'>");
            nuevaFila.append("&nbsp;<button type='button' class='eliminar-fila btn btn-danger'>Eliminar</button><br><br>");

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
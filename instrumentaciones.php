<?php
session_start();
if (!isset($_SESSION['IdMast'])) header("Location:index.php");

define('CHARSET', 'UTF-8');
header('Content-Type: text/html; charset=UTF-8');
require_once('conect.odbc.php'); //crea la conexión para la base de datos

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="MTI Gregorio Ignacio Letechipía Chávez">
    <script src="js/jquery-3.6.3.min.js" type="text/javascript"></script>
    <title>SICENETXv2 Calificaciones Finales</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
                        <a class="collapse-item" href="parciales.php">Parciales</a>
                        <a class="collapse-item " href="finales.php">Finales</a>
                        <h6 class="collapse-header">Académica</h6>
                        <a class="collapse-item active" href="instrumentaciones.php">Instrumentaciones</a>
                        <a class="collapse-item" href="tutorias.php">Tutorías</a>
                        <a class="collapse-item" href="asesorias.php">Asesorías</a>
                    </div>
                </div>

            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
                <!--BOTON QUE MINIMIZA LA BARRA LATERAL--->
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
                    <button id="sidebarToggleTop" class="btn btn-link  d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                                    <?php
                                    echo $_SESSION['nombre'] . " " . $_SESSION['apellidos'];
                                    ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>

                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                                <a class="dropdown-item" href="cerrar.php" data-toggle="modal" data-target="#logoutModal">
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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">
                        INSTRUMENTACIONES DIDÁCTICAS
                    </h1>

                    <?php
                    require_once('conect.odbc.php'); //crea la conexión para la base de datos
                    ?>
                    <!-- Page Heading -->

                    <p class="mb-4"><a target="_blank" href="https://datatables.net"></a></p>
                    <!-- DataTales Example -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Materias</th>
                                        <th>Semestre</th>
                                        <th>Grupo</th>
                                        <th>Instrumentaciones</th>
                                    </tr>
                                    <tr>
                                        <?php

                                        $consulta = "SELECT DISTINCT Materias.Materia, Materias.Sem, Carreras.CveDGIT, Materias.CveMat, Carreras.Abrev, Planes.ClaveDGIT, Grupos.YrP, Grupos.Per, Grupos.IdD, Grupos.IdC, Grupos.IdR, Grupos.IdM, Grupos.G, Grupos.sFKey
                                FROM Carreras INNER JOIN (Planes INNER JOIN (Materias INNER JOIN (Docentes INNER JOIN Grupos ON Docentes.IdMast=Grupos.IdMast) ON (Materias.IdD=Grupos.IdD) AND (Materias.IdC=Grupos.IdC) AND (Materias.IdR=Grupos.IdR) AND (Materias.IdM=Grupos.IdM)) ON (Planes.IdD=Materias.IdD) AND (Planes.IdC=Materias.IdC) AND (Planes.IdR=Materias.IdR)) ON (Carreras.IdD=Grupos.IdD) AND (Carreras.IdC=Grupos.IdC) AND (Carreras.IdD=Planes.IdD) AND (Carreras.IdC=Planes.IdC)
                                WHERE (((Grupos.YrP)=" . $_SESSION['ayo'] . ") AND ((Grupos.Per)=" . $_SESSION['periodo'] . ") AND ((Grupos.IdMast)= " . $_SESSION['IdMast'] . "))
                                ORDER BY Grupos.YrP, Grupos.Per;";
                                        //".$_SESSION['IdMast']." concatenación para hacer dinámicas las búsquedas de materias

                                        $result = odbc_exec($cid, $consulta,);

                                        while (odbc_fetch_row($result,)) {
                                            echo "<tr>";
                                            echo "<td>" . utf8_encode(odbc_result($result, 1)) . "</td>";
                                            echo "<td>" . odbc_result($result, 2) . "</td>";
                                            echo "<td>" . odbc_result($result, 13) . "</td>";
                                            echo "<td>
                <button type='button' class='btn btn-success' onclick='subirArchivo(\"" . odbc_result($result, 14) . "\")'>
                    <i class='fas fa-upload'></i> Subir
                </button>
                <button type='button' class='btn btn-danger' onclick='eliminarArchivo(\"" . odbc_result($result, 14) . "\")'>
                    <i class='fas fa-trash'></i> Eliminar
                </button>
                <button type='button' class='btn btn-primary' onclick='verArchivo(\"" . odbc_result($result, 14) . "\")'>
                    <i class='fas fa-eye'></i> Ver
                </button>
            </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tr>
                            </table>

                        </div>
                        <!-- /.container-fluid -->

                        <div id="destino" class=" col-xl-12 col-sm-6">
                            <!--- DIV DONDE SE MOSTRARA LA TABLA DE LA CONSULTA DE LA LISTA DE LOS ALUMNOS-->
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
                <!-- VENTANA MODAL DE CERRAR SESIÓN--->
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
                <div class="modal fade" id="rubros_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar rubro</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!------------------------------------------------------------------------------------------->
                            <div class="modal-body">
                                <hr>
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <!-- Contenido -->
                                        <div class="container">
                                            <br />
                                            <div class="form-group">
                                                <form name="add_name" id="add_name">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="dynamic_field">
                                                            <tr>
                                                                <td><input type="text" name="rubro[]" placeholder="Descripción" class="form-control name_list" />
                                                                    <input type="text" name="Porcentaje[]" placeholder="%" class="form-control name_list" />
                                                                </td>
                                                                <td>
                                                                    <button type="button" name="add" id="add" class="btn btn-success">Agregar Más</button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <input type="button" name="submit" id="submit" class="btn btn-info" value="Guardar" />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Fin Contenido -->
                                    </div>
                                </div>
                                <!-- Fin row -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='modal fade' id='verModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title'>Ver Archivo</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body' id='modalContenido'>
                                <!-- Contenido del modal -->
                            </div>
                        </div>
                    </div>
                </div>







                <!-- Bootstrap core JavaScript-->
                <script src="vendor/jquery/jquery.min.js"></script>
                <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="js/sb-admin-2.min.js"></script>
                <script src="scripts.js" type="text/javascript"></script>
                <script type="text/javascript">
                    $.ajaxSetup({
                        cache: false
                    });

                    function verArchivo(sfkey) {
                        // Comprobamos si el archivo existe antes de intentar cargarlo
                        $.ajax({
                            type: 'HEAD',
                            url: 'uploads/' + <?php echo $_SESSION['ayo'] ?> + '/' + <?php echo $_SESSION['periodo'] ?> + '/' + sfkey + '.pdf',
                            success: function() {
                                var uniqueString = new Date().getTime();
                                // El archivo existe, actualizamos dinámicamente el contenido del modal
                                $('#modalContenido').html("<iframe src='verArchivo.php?sfkey=" + sfkey + "&timestamp=" + uniqueString + "' width='100%' height='600'></iframe>");
                                // Mostramos el modal
                                $('#verModal').modal('show');
                            },
                            error: function() {
                                // El archivo no existe, mostramos un mensaje de error
                                alert('El archivo no existe.');
                            }
                        });
                    }




                    function eliminarArchivo(sfkey) {
                        if (confirm("¿Estás seguro de que deseas eliminar este archivo?")) {
                            $.ajax({
                                type: 'POST',
                                url: 'eliminarArchivo.php',
                                data: {
                                    sfkey: sfkey,
                                    ayo: <?php echo $_SESSION['ayo'] ?>,
                                    periodo: <?php echo $_SESSION['periodo'] ?>
                                },
                                success: function(response) {
                                    console.log(response);
                                    // Puedes hacer algo con la respuesta si es necesario
                                    alert('Archivo eliminado correctamente');
                                    // Aquí puedes recargar la tabla o realizar otras actualizaciones en la interfaz
                                    location.reload(true);
                                },
                                error: function(error) {
                                    console.error('Error al eliminar el archivo:', error);
                                }
                            });
                        }
                    }


                    function subirArchivo(sfkey) {
                        var inputFile = $('<input type="file" accept=".pdf">');
                        inputFile.on('change', function() {
                            var file = this.files[0];

                            // Verificar que se haya seleccionado un archivo
                            if (file) {
                                // Verificar la extensión del archivo
                                var fileName = file.name;
                                var fileExtension = fileName.split('.').pop().toLowerCase();
                                if (fileExtension !== 'pdf') {
                                    alert('Por favor, selecciona un archivo PDF.');
                                    return;
                                }

                                // Verificar el tamaño del archivo
                                var fileSize = file.size; // en bytes
                                var maxSize = 5 * 1024 * 1024; // 10 MB en bytes
                                if (fileSize > maxSize) {
                                    alert('El tamaño del archivo no debe superar los 5 MB.');
                                    return;
                                }

                                var formData = new FormData();
                                formData.append('sfkey', sfkey);
                                formData.append('archivo', file);

                                $.ajax({
                                    type: 'POST',
                                    url: 'subirArchivo.php',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        console.log(response);
                                        // Puedes hacer algo con la respuesta si es necesario
                                        alert('Archivo subido correctamente');
                                        // Actualiza solo la parte necesaria de la interfaz sin recargar la página
                                        // (opcional dependiendo de tus necesidades)
                                        // location.reload(true);
                                    },
                                    error: function(error) {
                                        console.error('Error al subir el archivo:', error);
                                    }
                                });
                            }
                        });

                        inputFile.click(); // Simula el clic en el botón de carga de archivo
                    }
                </script>
</body>

</html>
<?php
odbc_close($cid);
?>
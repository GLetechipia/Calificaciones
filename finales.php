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
                <?php echo "Periodo " . $_SESSION['periodo'] . " - " . $_SESSION['ayo']; ?>
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">

                    <span>Calificaciones</span>
                </a>
                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!--CREAR PAGINA PARA LA CAPTURA DE CALIFICACIONES--->
                        <h6 class="collapse-header">Calificaciones</h6>
                        <a class="collapse-item " href="parciales.php">Parciales</a>
                        <a class="collapse-item active" href="finales.php">Finales</a>
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
                        CALIFICACIONES FINALES
                    </h1>

                    <?php
                    $dsn = "sicenetxx"; //debe ser de sistema no de usuario
                    $usuario = "administrador";
                    $clave = "";
                    $cid = odbc_connect($dsn, $usuario, $clave,);
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
                                        <th>Ver</th>
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
                                            //echo "<td>".odbc_result($result,4)."</td>";
                                            echo "<td><button id=\"btnG\" type=\"button\" class=\"btn btn-primary\" onclick=\"verlistaf('" . odbc_result($result, 14) . "');\">
                                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-card-list\" viewBox=\"0 0 16 16\">
  <path d=\"M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z\"/>
  <path d=\"M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0\"/>
</svg><font style=\"vertical-align: center;\"><font style=\"vertical-align: center;\">
                                    </font></font></button>  </td>";
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

                <!-- Bootstrap core JavaScript-->
                <script src="vendor/jquery/jquery.min.js"></script>
                <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="js/sb-admin-2.min.js"></script>
                <script src="scripts.js" type="text/javascript"></script>
</body>

</html>
<?php
odbc_close($cid);
?>
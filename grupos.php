<?php
session_start();
//para la base de datos
$dsn = "sicenetxx"; //debe ser de sistema no de usuario
$usuario = "administrador";
$clave = "";


$cid = odbc_connect($dsn, $usuario, $clave,);
$sfkey = $_POST["sfkey"];


if (!$cid) {
    exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}
?>
<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="utf-8">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!----------------------------- JQUERY ------------------------------------>
    <script src="js/jquery-3.6.3.min.js" type="text/javascript"></script>

    <title>Grupos</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
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
                Acciones
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">

                    <span>Calificaciones</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Calificaciones</h6>
                        <a class="collapse-item" href="buttons.html">Parciales</a>
                        <a class="collapse-item" href="cards.html">Finales</a>
                    </div>
                </div>
            </li>




            <!-- Divider -->
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


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                                    <?php

                                    echo $_SESSION['nombre'];
                                    echo $_SESSION['apellidos'];

                                    ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>


                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400">

                                    </i>

                                    Perfil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configuraci&oacute;n
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Actividades Log
                                </a>
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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">
                        <?php
                        echo $_SESSION['nombre'];
                        ?>
                    </h1>

                    <?php
                    $dsn = "sicenetxx"; //debe ser de sistema no de usuario
                    $usuario = "administrador";
                    $clave = "";

                    $cid = odbc_connect($dsn, $usuario, $clave,);


                    ?>
                    <!-- Page Heading -->

                    <p class="mb-4"><a target="_blank" href="https://datatables.net"></a>.</p>
                    <!-- DataTales Example -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Numero de Control</th>
                                        <th>Nombre</th>
                                        <th>Grupo</th>

                                    </tr>
                                </thead>
                                <tr>
                                    <?php
                                    /* la consulta es AlumnosMateria*/
                                    $consulta = "SELECT Alumnos.NumCont, Alumnos.Ape+' '+Alumnos.Nom AS Alumno, Listas.G
                                FROM Alumnos INNER JOIN Listas ON Alumnos.NumCont=Listas.NumCont
                                WHERE (((Listas.sFKey) Like '" . [$sfkey] . "')) ORDER BY Alumnos.Ape+' '+Alumnos.Nom;";


                                    $result = odbc_exec($cid, $consulta,) or die(exit("Error Exect ODBC"));

                                    while (odbc_fetch_row($result,)) {
                                        //echo "<td><a href=\"grupos.php?sfkey=".odbc_result($result,14)."\">".odbc_result($result,1)."</td>";
                                        echo "<td>" . odbc_result($result, 1) . "</td>";
                                        echo "<td>" . odbc_result($result, 2) . "</td>";
                                        echo "<td>" . odbc_result($result, 3) . "</td>";

                                        echo "</tr>";
                                    }
                                    ?>
                                </tr>
                            </table>

                        </div>


                    </div>
                    <!-- End of Main Content -->
                    <div>
                        <!--------------------------------------------------------------------------------------->
                        <!--------------------------------------------------------------------------------------->
                        <!--------------------------------------------------------------------------------------->
                        <div class="container" id="destino">

                        </div>
                    </div>
                    <!--------------------------------------------------------------------------------------->
                    <!--------------------------------------------------------------------------------------->
                    <!--------------------------------------------------------------------------------------->
                    <!-- Footer -->
                    <footer class="sticky-footer bg-white">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span>Copyright &copy; Tecnm Campus Loreto</span>
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
                        <div class="modal-body">Seleccionaste "Logout", estas segur@ de terminar la sesión.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="cerrar.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <!---fin Logout Modal---->
            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
<?php
odbc_close($cid);
?>
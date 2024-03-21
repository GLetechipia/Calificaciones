<?php
session_start();
if (!isset($_SESSION['numero_control'])) header("Location:./index.php");

require_once('../conect.odbc.php'); //crea la conexión para la base de datos

$sql = "SELECT Listas.YrP, Listas.Per FROM Listas
WHERE (((Listas.NumCont)='" . $_SESSION['numero_control'] . "'))
GROUP BY Listas.YrP, Listas.Per;";

$resperiodos = odbc_exec($cid, $sql) or die(exit("Error en odbc_exec"));

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
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/nunito.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <script>
   function cargarResultados(url, periodo = '') {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var blob = new Blob([xhr.response], { type: 'application/pdf' });
            var urlPDF = URL.createObjectURL(blob);
            document.getElementById('resultado_frame').src = urlPDF;
        }
    };
    xhr.open('POST', url, true);
    xhr.responseType = 'arraybuffer'; // Esto indica que esperamos un array de bytes (un archivo PDF)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('ayoperiodo=' + periodo);
}
</script>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <!---- AQUI VA LA IMAGEN DEL LOGO DEL TEC...---->

                <img class="mb-4" src="../img/LogoTecLoretoOriginal.svg" style="margin-top: 20px; height: 40px; width: 40px;">

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
                    <span>Calificaciones</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Boletas </h6>
                        <?php
                        while (odbc_fetch_row($resperiodos)) {
                            $valor = odbc_result($resperiodos, 2) . '-' . odbc_result($resperiodos, 1);
                            echo '<a class="collapse-item" href="" onclick="cargarResultados(\'boletacalificaciones.php\', \'' . $valor . '\'); event.preventDefault();">' . $valor . '</a>';
                        }
                        ?>
                        <h6 class="collapse-header">Kardex</h6>
                        <a class="collapse-item" href="" onclick="cargarResultados('kardex.php'); event.preventDefault();">Ver</a>
                    </div>
                </div>

            </li>

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

                    <!-- Topbar Periodo-->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <p><?php echo "Periodo " . $_SESSION['periodo'] . " - " . $_SESSION['ayo']; ?></p>
                        </div>
                    </form>

                    <!-- Topbar Periodo -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Bienvenid@</h1>
                    <iframe id="resultado_frame" name="resultado_frame" frameborder="0" scrolling="auto" width="100%" height="700"></iframe>

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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>
<?php
session_start();
//para la base de datos
$dsn = "sicenetxx"; //debe ser de sistema no de usuario
$usuario = "administrador";
$clave="";


$cid=odbc_connect($dsn, $usuario, $clave,);


if (!$cid){
	exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}	
?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="utf-8">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="js/jquery-3.6.3.min.js" type="text/javascript"></script>
    
    <title>Materias</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- <script>
            $(document).ready(function(){
            $("#btnG").click(function () {
            alert("has hecho en el boton");
            });
        });

    </script>   -->
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <!---- AQUI VA LA IMAGEN DEL LOGO DEL TEC...---->

                <img class="mb-4" src="img/LogoTecLoretoOriginal.svg"
                    style="margin-top: 20px; height: 40px; width: 40px;">


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
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">

                    <span>Calificaciones</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!--CREAR PAGINA PARA LA CAPTURA DE CALIFICACIONES--->
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
                <button class="rounded-circle border-0" id="sidebarToggle"></button> <!--BOTON QUE MINIMIZA LA BARRA LATERAL--->
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
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>

                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                                    <?php  
                                
                                echo $_SESSION['nombre'];
                                echo $_SESSION['apellidos'];
                                
                            ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>


                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400">

                                    </i>

                                    Perfil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configuraci&oacute;n
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="cerrar.php" data-toggle="modal"
                                    data-target="#logoutModal">
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
                        $clave="";
                        
                        $cid=odbc_connect($dsn, $usuario, $clave,);
                        
                                                
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
                                
                                $consulta="SELECT DISTINCT Materias.Materia, Materias.Sem, Carreras.CveDGIT, Materias.CveMat, Carreras.Abrev, Planes.ClaveDGIT, Grupos.YrP, Grupos.Per, Grupos.IdD, Grupos.IdC, Grupos.IdR, Grupos.IdM, Grupos.G, Grupos.sFKey
                                FROM Carreras INNER JOIN (Planes INNER JOIN (Materias INNER JOIN (Docentes INNER JOIN Grupos ON Docentes.IdMast=Grupos.IdMast) ON (Materias.IdD=Grupos.IdD) AND (Materias.IdC=Grupos.IdC) AND (Materias.IdR=Grupos.IdR) AND (Materias.IdM=Grupos.IdM)) ON (Planes.IdD=Materias.IdD) AND (Planes.IdC=Materias.IdC) AND (Planes.IdR=Materias.IdR)) ON (Carreras.IdD=Grupos.IdD) AND (Carreras.IdC=Grupos.IdC) AND (Carreras.IdD=Planes.IdD) AND (Carreras.IdC=Planes.IdC)
                                WHERE (((Grupos.YrP)=2022) AND ((Grupos.Per)=1) AND ((Grupos.IdMast)= ".$_SESSION['IdMast']."))
                                ORDER BY Grupos.YrP, Grupos.Per;";
                                //".$_SESSION['IdMast']." concatenacion para hacer dinamicas las busquedas de materias

                                $result = odbc_exec($cid,$consulta,);
                                
                                while(odbc_fetch_row($result,)){
                                    echo "<tr>";
                                    echo "<td>".odbc_result($result,1)."</td>";
                                    echo "<td>".odbc_result($result,2)."</td>";
                                    echo "<td>".odbc_result($result,13)."</td>";
                                    //echo "<td>".odbc_result($result,4)."</td>";
                                    echo "<td><button id=\"btnG\" type=\"button\" class=\"btn btn-primary\" onclick=\"verlista('".odbc_result($result,14)."');\">
                                    <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"12\" height=\"12\" fill=\"currentColor\" class=\"bi bi-people\" viewBox=\"0 0 16 16\">
                                    <path d=\"M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z\"></path>
                                    </svg><font style=\"vertical-align: center;\"><font style=\"vertical-align: center;\">
                                    
                                    </font></font></button> </td>";
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
                        <!-- VENTANA MODAL DE CERRAR SESION--->
                <!-- Logout Modal-->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Salir</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <!---- ventana de salida---->
                            <div class="modal-body">¿Desea Cerrar Sesion?</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                <a class="btn btn-primary" href="cerrar.php">Salir</a>
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
                                                        <td><input type="text" name="rubro[]" placeholder="Descripcion"
                                                                class="form-control name_list" />
                                                            <input type="text" name="Porcentaje[]" placeholder="%"
                                                                class="form-control name_list" /></td>
                                                        <td><button type="button" name="add" id="add"
                                                                class="btn btn-success">Agregar Más</button></td>
                                                    </tr>
                                                </table>
                                                <input type="button" name="submit" id="submit" class="btn btn-info"
                                                    value="Guardar" />
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
                            <!--------------------------------------------------------------------------------------->
                            <!--------------------------------------------------------------------------------------->
                            <!--------------------------------------------------------------------------------------->

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
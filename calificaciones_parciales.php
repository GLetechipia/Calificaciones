<?php
session_start();

if (!isset($_SESSION['IdMast'])) {
    header("Location:index.php");
}

define('CHARSET', 'UTF-8');
header('Content-Type: text/html; charset=UTF-8');

$dsn = "sicenetxx";
$usuario = "administrador";
$clave = "";

$cid = odbc_connect($dsn, $usuario, $clave);

if (!$cid) {
    exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="js/jquery-3.6.3.min.js" type="text/javascript"></script>
    
    <title>Materias</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar y contenido aquí -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- Encabezado y otras secciones -->

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">CALIFICACIONES PARCIALES</h1>

                    <?php
                        $dsn = "sicenetxx";
                        $usuario = "administrador";
                        $clave = "";
                        
                        $cid = odbc_connect($dsn, $usuario, $clave);
                    ?>

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
                                </thead>
                                <tbody>
                                    <?php
                                        $consulta = "SELECT DISTINCT Materias.Materia, Materias.Sem, Carreras.CveDGIT, Materias.CveMat, Carreras.Abrev, Planes.ClaveDGIT, Grupos.YrP, Grupos.Per, Grupos.IdD, Grupos.IdC, Grupos.IdR, Grupos.IdM, Grupos.G, Grupos.sFKey
                                        FROM Carreras INNER JOIN (Planes INNER JOIN (Materias INNER JOIN (Docentes INNER JOIN Grupos ON Docentes.IdMast=Grupos.IdMast) ON (Materias.IdD=Grupos.IdD) AND (Materias.IdC=Grupos.IdC) AND (Materias.IdR=Grupos.IdR) AND (Materias.IdM=Grupos.IdM)) ON (Planes.IdD=Materias.IdD) AND (Planes.IdC=Materias.IdC) AND (Planes.IdR=Materias.IdR)) ON (Carreras.IdD=Grupos.IdD) AND (Carreras.IdC=Grupos.IdC) AND (Carreras.IdD=Planes.IdD) AND (Carreras.IdC=Planes.IdC)
                                        WHERE (((Grupos.YrP)=".$_SESSION['ayo'].") AND ((Grupos.Per)=".$_SESSION['periodo'].") AND ((Grupos.IdMast)= ".$_SESSION['IdMast']."))
                                        ORDER BY Grupos.YrP, Grupos.Per;";
                                        
                                        $result = odbc_exec($cid, $consulta);
                                        
                                        while (odbc_fetch_row($result)) {
                                            echo "<tr>";
                                            echo "<td>".utf8_encode(odbc_result($result, 1))."</td>";
                                            echo "<td>".odbc_result($result, 2)."</td>";
                                            echo "<td>".odbc_result($result, 13)."</td>";
                                            echo "<td><button id=\"btnG\" type=\"button\" class=\"btn btn-primary\" onclick=\"verlista('".odbc_result($result, 14)."');\">
                                                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-card-list\" viewBox=\"0 0 16 16\">
                                                <path d=\"M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z\"/>
                                                <path d=\"M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0\"/>
                                            </svg><font style=\"vertical-align: center;\"><font style=\"vertical-align: center;\">
                                                </font></font></button>  </td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="destino" class="col-xl-12 col-sm-6"></div>
                    </div>
                </div>

                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Tecnm Campus Loreto</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <!-- Contenido del modal de cierre de sesión -->
    </div>

    <!-- Rubros Modal -->
    <div class="modal fade" id="rubros_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <!-- Contenido del modal de rubros -->
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="scripts.js" type="text/javascript"></script>
</body>
</html>

<?php
odbc_close($cid);
?>

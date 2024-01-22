<?php
session_start();
//para la base de datos
require_once('conect.odbc.php'); //crea la conexión para la base de datos

$sfkey = $_POST["sfkey"];


?>

<div class="row">
    <div class="col-xl-12 col-sm-6">
        <!-- Content Section -->
        <!-- crud jquery-->
        <div class="da">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">

                        <p>Captura de calificaciones finales</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div id="records_content"></div>
                </div>
            </div>
        </div>
        <!-- /Content Section -->
        <!-- // Modal -->
        <div class="row">
            <div class="col-12 col-md-12">
                <!-- Contenido -->
                <div class="container">
                    <br />
                </div>
                <div class="col-xl-12 col-sm-6">
                    <!---inicia la tabla que muestra el listado para la captura de calificaciones--->
                    <form name="calif" id="calif" method="post">
                        <table class="table table-bordered" id="dataTable" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>N. Control</th>
                                    <th>Nombre del Alumno</th>
                                    <th>Repetición</th>
                                    <th>Tipo Calificación</th>
                                    <th>Calificación</th>
                                </tr>
                                <tr>
                                    <?php
                                    $consulta = "SELECT Alumnos.NumCont, Alumnos.Ape+' '+Alumnos.Nom AS Alumno, Listas.TipoCal, Listas.Calif, Listas.G, Listas.Repet
                                    FROM Alumnos INNER JOIN Listas ON Alumnos.NumCont=Listas.NumCont
                                    WHERE (((Listas.sFKey) Like '" . $sfkey . "'))
                                    ORDER BY Alumnos.Ape;";
                                    
                                    $result = odbc_exec($cid, $consulta) or die(exit("Error Exect ODBC"));

                                    while (odbc_fetch_row($result)) {
                                        echo "<tr>";
                                        echo "<td>" . odbc_result($result, 1) . "<input type=\"hidden\" name=\"sfkey[]\" value=\"" . $sfkey . "\"><input type=\"hidden\" name=\"nctrl[]\" value=\"" . odbc_result($result, 1) . "\" ></td>";
                                        echo "<td>" . utf8_encode(odbc_result($result, 2)) . "</td>";
                                        if (odbc_result($result, 6))
                                             $icono = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                                                        <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                                                        <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/>
                                                        </svg>';
                                        else $icono = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-square" viewBox="0 0 16 16">
                                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                                        </svg>';
                                        echo "<td align=\"center\">" . $icono . "</td>";
                                        echo "<td><select name=\"tipocal[]\"><option value=\"1aOp\" ";
                                        if (odbc_result($result, 3) == "1aOp")
                                            echo "selected ";
                                        echo ">1aOp</option> <option value=\"2aOp\" ";
                                        if (odbc_result($result, 3) == "2aOp")
                                            echo "selected ";
                                        echo ">2aOp</option></td></select>";
                                        echo "<td><input type=\"number\" name=\"calificacion[]\" 
                                            min=\"0\" max=\"100\" value=\"" . odbc_result($result, 4) . "\" style=\"text-align:right\"/></td>";
                                    }
                                    ?>
                                </tr>
                        </table><!-- boton -->
                        <div id="mensaje" class="alert alert-dismissible" role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span id="mensaje-texto"></span>
                        </div>
                        <br>
                        <p style="text-align: right;"><button type="submit" class="btn btn-primary float-right">Guardar</button></p>
                    </form>

                </div>
                <div class="container">

                </div>

            </div>
        </div>

        <?php odbc_close($cid); ?>
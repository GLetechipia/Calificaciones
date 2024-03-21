<?php
session_start();
//para la base de datos
require_once('conect.odbc.php'); //crea la conexión para la base de datos

$sfkey = $_POST["sfkey"];
echo $sfkey;

?>

<div class="row">
    <div class="col-xl-12 col-sm-6">
        <!------->
        <!--------------------------------------------------------------------------------------->
        <!-- Content Section -->
        <!-- crud jquery-->
        <div class="da">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <!--primer boton para mostrar la ventana d elos rubros--->
                        <button class="btn btn-primary" data-toggle="modal" data-target="#rubros_modal">Añadir
                            Tema</button>
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

        <!--------------------------------------------------------------------------------------->
        <div class="row">
            <div class="col-12 col-md-12">
                <!-- Contenido -->
                <div class="container">
                    <br />

                </div>

                <div class="col-xl-12 col-sm-6">
                    <!------>
                    <table class="table table-responsive table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Numero de control</th>
                                <th>Alumno</th>


                            </tr>
                            <tr>
                                <?php

                                $consulta = "SELECT Alumnos.NumCont, Alumnos.Ape+' '+Alumnos.Nom AS Alumno, Listas.G
                                    FROM Alumnos INNER JOIN Listas ON Alumnos.NumCont=Listas.NumCont
                                    WHERE (((Listas.sFKey) Like '" . $sfkey . "'))
                                    ORDER BY Alumnos.Ape;";

                                //echo $consulta;                               

                                $result = odbc_exec($cid, $consulta) or die(exit("Error Exect ODBC"));

                                while (odbc_fetch_row($result)) {
                                    echo "<tr>";
                                    echo "<td>" . odbc_result($result, 1) . "</td>";
                                    echo "<td>" . odbc_result($result, 2) . "</td>";
                                    //echo "<td>".odbc_result($result,3)."</td>";
                                    //echo "<td>".odbc_result($result,14)."</td>";
                                }
                                ?>
                            </tr>
                    </table>
                </div>
                <div class="container">
                    <!-- boton -->
                    <br><button class="btn btn-primary float-right">-</button>

                </div>

            </div>
        </div>
        <?php odbc_close($cid); ?>
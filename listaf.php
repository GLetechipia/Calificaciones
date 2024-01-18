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
                                <th>Tipo Calificación</th>
                                <th>Calificación</th>
                            </tr>
                            <tr>
                                <?php           
                                    $consulta="SELECT Alumnos.NumCont, Alumnos.Ape+' '+Alumnos.Nom AS Alumno, Listas.TipoCal, Listas.Calif, Listas.G
                                    FROM Alumnos INNER JOIN Listas ON Alumnos.NumCont=Listas.NumCont
                                    WHERE (((Listas.sFKey) Like '".$sfkey."'))
                                    ORDER BY Alumnos.Ape;";
                                        //echo $consulta;
                                        //input oculto para saber exactamente la materia            
                                        //echo "<form name=\"finales\" method=\"post\" action=\"nada.php\">";
                                        //echo "<input type=\"hidden\" name=\"sfkey\" value=\"".$sfkey."\">";
                                        $result = odbc_exec($cid,$consulta) or die (exit("Error Exect ODBC"));
                                    
                                        while(odbc_fetch_row($result)){
                                            echo "<tr>";
                                            echo "<td>".odbc_result($result,1)."<input type=\"hidden\" name=\"sfkey[]\" value=\"".$sfkey."\"><input type=\"hidden\" name=\"nctrl[]\" value=\"".odbc_result($result,1)."\" ></td>";
                                            echo "<td>".odbc_result($result,2)."</td>";
                                            echo "<td><select name=\"tipocal[]\"><option value=\"1aOp\" ";
                                            if (odbc_result($result,3)=="1aOp") 
                                                echo "selected ";
                                            echo ">1aOp</option> <option value=\"2aOp\" ";
                                            if (odbc_result($result,3)=="2aOp") 
                                                echo "selected ";
                                            echo ">2aOp</option></td></select>";
                                            echo "<td><input type=\"number\" name=\"calificacion[]\" 
                                            min=\"0\" max=\"100\" value=\"".odbc_result($result,4)."\" style=\"text-align:right\"/></td>";
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
                    <br><p style="text-align: right;"><button type="submit" class="btn btn-primary float-right">Guardar</button></p>
                    </form>
                    
                </div>
                <div class="container">
                
                </div>

            </div>
        </div>
        
<?php  odbc_close($cid); ?>
<?php
session_start();
//para la base de datos
require_once('conect.odbc.php'); //crea la conexión para la base de datos

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-------------------------------------------------------------------------->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
     $(document).ready(function(){
        $("button").click(function(){
            $("#dataTable").hide("slow", function(){
            //alert("The paragraph is now hidden");
            });
        });
    });
    </script>
</head>
<body>
    
    <button>Hide</button>
    
             <p class="mb-4" ><a target="_blank" href="https://datatables.net"></a>
                    <!-- DataTales Example -->
                   
                    <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" border="1" >
                   
                        <tr>
                            <th>Materias</th>
                            <th>Semestre</th>
                            <th>Grupo</th>
                            <th>Total</th>
                        </tr>
                        <tr>
                            <?php
                                
                                $consulta="SELECT DISTINCT Materias.Materia, Materias.Sem, Carreras.CveDGIT, Materias.CveMat, Carreras.Abrev, Planes.ClaveDGIT, Grupos.YrP, Grupos.Per, Grupos.IdD, Grupos.IdC, Grupos.IdR, Grupos.IdM, Grupos.G, Grupos.sFKey
                                FROM Carreras INNER JOIN (Planes INNER JOIN (Materias INNER JOIN (Docentes INNER JOIN Grupos ON Docentes.IdMast=Grupos.IdMast) ON (Materias.IdD=Grupos.IdD) AND (Materias.IdC=Grupos.IdC) AND (Materias.IdR=Grupos.IdR) AND (Materias.IdM=Grupos.IdM)) ON (Planes.IdD=Materias.IdD) AND (Planes.IdC=Materias.IdC) AND (Planes.IdR=Materias.IdR)) ON (Carreras.IdD=Grupos.IdD) AND (Carreras.IdC=Grupos.IdC) AND (Carreras.IdD=Planes.IdD) AND (Carreras.IdC=Planes.IdC)
                                WHERE (((Grupos.YrP)=2022) AND ((Grupos.Per)=1) AND ((Grupos.IdMast)= ".$_SESSION['IdMast']."))
                                ORDER BY Grupos.YrP, Grupos.Per;";
                                //".$_SESSION['IdMast']." concatenacion para hacer dinamicas las busquehas de materias

                                $result = odbc_exec($cid,$consulta,);
                                
                                while(odbc_fetch_row($result,)){
                                    echo "<tr>";
                                    echo "<td><a href=\"grupos.php?sfkey=".odbc_result($result,14)."\">".odbc_result($result,1)."</td>";
                                    echo "<td>".odbc_result($result,2)."</td>";
                                    echo "<td>".odbc_result($result,13)."</td>";
                                    echo "<td>".odbc_result($result,4)."</td>";
                                                                  
                                    echo "</tr>";
                                } 
                            ?>
                        </tr>
                    </table>

             </p>
            
              
</body>
</html>
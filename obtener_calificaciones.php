<?php
session_start();

require_once('conect.odbc.php'); //crea la conexión para la base de datos


$sFKey = $_POST['sFKey'];

$consulta = "SELECT CalificacionRubros.IdRubro, CalificacionRubros.NumTema, CalificacionRubros.Cal, RubrosPorTemas.NomRubro
FROM CalificacionRubros
INNER JOIN RubrosPorTemas ON CalificacionRubros.IdRubro = RubrosPorTemas.IdRubro
WHERE CalificacionRubros.sFKey = '$sFKey';";

$result = odbc_exec($cid, $consulta);

$html = '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rubro</th>
                    <th>Tema</th>
                    <th>Calificación</th>
                </tr>
            </thead>
            <tbody>';

while (odbc_fetch_row($result)) {
    $idRubro = odbc_result($result, 1);
    $numTema = odbc_result($result, 2);
    $calificacion = odbc_result($result, 3);
    $nomRubro = odbc_result($result, 4);

    $html .= "<tr>
                <td>$nomRubro</td>
                <td>Tema $numTema</td>
                <td>$calificacion</td>
            </tr>";
}

$html .= '</tbody></table>';

echo $html;

odbc_close($cid);

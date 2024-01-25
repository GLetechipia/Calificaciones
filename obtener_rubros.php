<?php
// obtener_rubros.php
// ... (Conectarte a la base de datos y demás)
require_once('conect.odbc.php'); //crea la conexión para la base de datos

$idTema = $_POST['idTema'];

// Consulta para obtener los rubros y porcentajes asociados al tema
$consultaRubros = "SELECT RubrodeTema.idRubroTema, RubrodeTema.nombrerubro, RubrodeTema.porcentaje
                   FROM RubrodeTema
                   WHERE RubrodeTema.IdTemaCalificar = $idTema;";

$resultRubros = odbc_exec($cid, $consultaRubros);

$rubros = array();
$porcentajes = array();
$idRubros = array();

while ($rubro = odbc_fetch_array($resultRubros)) {
    $idRubros[] = $rubro['idRubroTema'];
    $rubros[] = $rubro['nombrerubro'];
    $porcentajes[] = $rubro['porcentaje'];
}

odbc_close($cid);

// Devolver la respuesta como un array JSON
echo json_encode(array('idRubros' => $idRubros, 'rubros' => $rubros, 'porcentajes' => $porcentajes));
?>

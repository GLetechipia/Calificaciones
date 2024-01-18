<?php
// obtener_lista_alumnos.php
var_dump($_POST);

if (isset($_POST['sFKey'])) {
    // Conexión a la base de datos (debes incluir aquí tus credenciales y lógica de conexión)
    $dsn = "sicenetxx";
    $usuario = "administrador";
    $clave = "";
    $cid = odbc_connect($dsn, $usuario, $clave);

    // Obtener la clave del grupo
    $sfkey = $_POST['sFKey'];

    // Consulta PIVOT para obtener la lista de alumnos y calificaciones por temas
    $consulta = "TRANSFORM Sum(CalificacionTema.calificacion) AS calificacion
                 SELECT alumnos.numcont, alumnos.nom, alumnos.ape
                 FROM ((alumnos 
                        INNER JOIN Listas ON alumnos.NumCont = Listas.NumCont) 
                        INNER JOIN TemasPorCalificar ON Listas.sFKey = TemasPorCalificar.sFKey) 
                        INNER JOIN CalificacionTema ON (alumnos.NumCont = CalificacionTema.NumCont) 
                        AND (TemasPorCalificar.idTemaCalificar = CalificacionTema.idTemaCalificar) 
                 WHERE Listas.sFKey='$sfkey'
                 GROUP BY alumnos.numcont, alumnos.nom, alumnos.ape
                 PIVOT temasporcalificar.nombretema";

    $result = odbc_exec($cid, $consulta);

    // Obtener el número de campos y los nombres de los campos
    $numFields = odbc_num_fields($result);
    $fieldNames = array();
    for ($i = 1; $i <= $numFields; $i++) {
        $fieldNames[] = odbc_field_name($result, $i);
    }

    // Construir la tabla HTML con la lista de alumnos y calificaciones
    $tabla_html = '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Número de Control</th>
                                <th>Nombre</th>
                                <th>Apellido</th>';

    // Agregar los nombres de los campos en la cabecera
    foreach ($fieldNames as $fieldName) {
        $tabla_html .= '<th>' . $fieldName . '</th>';
    }

    $tabla_html .= '</tr>
                    </thead>
                    <tbody>';

    while (odbc_fetch_row($result)) {
        $numcont = odbc_result($result, 1);
        $nom = odbc_result($result, 2);
        $ape = odbc_result($result, 3);

        $tabla_html .= '<tr>
                            <td>' . $numcont . '</td>
                            <td>' . $nom . '</td>
                            <td>' . $ape . '</td>';

        // Agregar las calificaciones por tema a la fila
        for ($i = 4; $i <= $numFields; $i++) {
            $calificacion = odbc_result($result, $i);
            $tabla_html .= '<td>' . $calificacion . '</td>';
        }

        $tabla_html .= '</tr>';
    }

    $tabla_html .= '</tbody></table>';

    // Cerrar la conexión a la base de datos
    odbc_close($cid);

    // Devolver la tabla HTML como respuesta al cliente
    echo $tabla_html;
} else {
    // Si no se proporciona la clave del grupo, devolver un mensaje de error
    echo 'Error: Clave de grupo no proporcionada.';
}
?>

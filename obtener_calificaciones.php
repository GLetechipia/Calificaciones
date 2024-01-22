<?php
// obtener_lista_alumnos.php
//var_dump($_POST);
echo "Lista de Alumnos ";
if (isset($_POST['sFKey'])) {
    
    require_once('conect.odbc.php'); //crea la conexión para la base de datos
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
                 GROUP BY alumnos.numcont, alumnos.nom, alumnos.ape order by alumnos.ape
                 PIVOT temasporcalificar.nombretema";

    $result = odbc_exec($cid, $consulta);

    // Obtener el número de campos y los nombres de los campos
    $numFields = odbc_num_fields($result);
    $fieldNames = array();
    for ($i = 1; $i <= $numFields; $i++) {
        $fieldNames[] = odbc_field_name($result, $i);
    }
    if (count($fieldNames) > 3) {
        // Construir la tabla HTML con la lista de alumnos y calificaciones
        $tabla_html = '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Número de Control</th>
                                <th>Nombre</th>';

        // Agregar los nombres de los campos en la cabecera
        for ($i = 3; $i < $numFields; $i++) {
            $tabla_html .= '<th>' . utf8_encode($fieldNames[$i]) . '</th>';
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
                            <td>' . utf8_encode($ape) . ' ' . utf8_encode($nom) . '</td>';

            // Agregar las calificaciones por tema a la fila
            for ($i = 4; $i <= $numFields; $i++) {
                $calificacion = odbc_result($result, $i);
                $tabla_html .= '<td>' . number_format($calificacion, 0) . '</td>';
            }

            $tabla_html .= '</tr>';
        }

        $tabla_html .= '</tbody></table>';

        // Cerrar la conexión a la base de datos
        //odbc_close($cid);

        // Devolver la tabla HTML como respuesta al cliente
        echo $tabla_html;
    }
} else {
    // Si no se proporciona la clave del grupo, devolver un mensaje de error
    echo 'Error: Clave de grupo no proporcionada.';
}

echo '<button class="btn btn-primary" data-toggle="modal" data-target="#agregarQuitarModal">Agregar/Quitar Temas</button>';

// Consulta para obtener los temas actuales
$consultaTemas = "SELECT DISTINCT nombretema FROM TemasPorCalificar WHERE sfkey='$sfkey'";
$resultTemas = odbc_exec($cid, $consultaTemas);

$temasActuales = array();
while ($row = odbc_fetch_array($resultTemas)) {
    $temasActuales[] = $row['nombretema'];
}
?>
<!-- Modal para Agregar/Quitar Temas -->
<div class="modal fade" id="agregarQuitarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar/Quitar Temas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para agregar o quitar temas -->
                <form id="agregarQuitarForm">
                    <div class="form-group">
                        <label for="nombreTema">Nombre del Tema:</label>
                        <input type="text" class="form-control" id="nombreTema" name="nombreTema" placeholder="Escribe un nuevo tema o selecciona uno existente">
                    </div>
                    <div class="form-group">
                        <label for="temasActuales">Temas Actuales:</label>
                        <select class="form-control" id="temasActuales" name="temasActuales">
                            <?php
                            // Mostrar las opciones de temas actuales
                            foreach ($temasActuales as $tema) {
                                echo '<option value="' . $tema . '">' . $tema . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success" onclick="agregarTema()">Agregar Tema</button>
                    <button type="button" class="btn btn-danger" onclick="quitarTema()">Quitar Tema</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Definir el modal -->
<div class="modal" id="mensajeModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Cabecera del modal -->
            <div class="modal-header">
                <h4 class="modal-title">Mensaje</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Contenido del modal -->
            <div class="modal-body">
                <p id="mensajeTexto"></p>
            </div>

            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
<button type="button" class="btn btn-primary" id="btnMostrarModal" style="display: none;">
    Abrir Modal
</button>
<?php odbc_close($cid); ?>

<!-- Script para manejar la lógica de agregar/quitar temas -->
<script>
    function agregarTema() {
        var nuevoTema = $('#nombreTema').val();

        // Si el campo de texto no está vacío, agregar el nuevo tema
        if (nuevoTema.trim() !== '') {
            $.ajax({
                type: 'POST',
                url: 'agregar_tema.php', // Reemplaza con la ruta correcta a tu script PHP
                data: {
                    sFKey: '<?php echo $sfkey; ?>',
                    nombreTema: nuevoTema
                },
                success: function(response) {
                    // Lógica adicional después de agregar el tema (si es necesario)
                    console.log(response);
                    // Mostrar mensaje modal después de agregar el tema
                    mostrarMensaje('Tema agregado exitosamente.');
                    activarModal();
                    setTimeout(() =>verlista('<?php echo $sfkey; ?>'),3500);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        // Limpiar el campo de entrada después de agregar el tema
        $('#nombreTema').val('');
        // Cerrar el modal después de agregar el tema (o manejarlo según tus necesidades)
        $('#agregarQuitarModal').modal('hide');
    }

    function quitarTema() {
        var temaSeleccionado = $('#temasActuales').val();
        // Si hay un tema seleccionado, quitar el tema
        if (temaSeleccionado.trim() !== '') {
            $.ajax({
                type: 'POST',
                url: 'quitar_tema.php', // Reemplaza con la ruta correcta a tu script PHP
                data: {
                    sFKey: '<?php echo $sfkey; ?>',
                    nombreTema: temaSeleccionado
                },
                success: function(response) {
                    // Lógica adicional después de quitar el tema (si es necesario)
                    console.log(response);
                    // Ver la lista después de quitar el tema

                    // Mostrar mensaje modal después de quitar el tema
                    mostrarMensaje('Tema y calificaciones asociadas quitados exitosamente.');
                    activarModal();
                    setTimeout(() =>verlista('<?php echo $sfkey; ?>'),3500);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        // Limpiar el campo de entrada después de quitar el tema
        $('#nombreTema').val('');

        // Cerrar el modal después de quitar el tema (o manejarlo según tus necesidades)
        $('#agregarQuitarModal').modal('hide');
    }

    // Función para mostrar mensaje y cerrar automáticamente el modal después de 3 segundos
    function mostrarMensaje(mensaje) {
        // Asignar el mensaje al contenido del modal
        $('#mensajeTexto').text(mensaje);
        // Mostrar el modal
        $('#mensajeModal').modal('show');
        // Cerrar el modal automáticamente después de 3 segundos
        setTimeout(function() {
            $('#mensajeModal').modal('hide');
        }, 3000);
    }
    
    // Función para activar el modal mediante el botón oculto
    function activarModal() {
        $('#btnMostrarModal').trigger('click');
    }
</script>
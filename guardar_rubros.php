<?php
// guardar_rubros.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir datos del formulario
    //$postData = file_get_contents("php://input");

    $rubrosData = json_decode($_POST['rubrosData'], true);
    $idTema = isset($_POST['idTema']) ? $_POST['idTema'] : null;

    require_once('conect.odbc.php'); // Asegúrate de tener esta conexión correcta

    echo "Contenido de \$rubrosData:\n";
    var_dump($rubrosData);

    // Obtener rubros actuales del tema
    $rubrosActuales = obtenerRubrosActuales($idTema);

    // Comparar rubros actuales con los recibidos del formulario
    foreach ($rubrosData as $rubroForm) {
        $idRubroForm = $rubroForm['idRubro'];
        $nombreRubroForm = $rubroForm['nombre'];
        $porcentajeRubroForm = $rubroForm['porcentaje'];

        // Verificar si el rubro existe actualmente
        $rubroActual = encontrarRubroPorId($rubrosActuales, $idRubroForm);

        if ($rubroActual) {
            // El rubro existe, actualizar si es necesario
            if ($rubroActual['nombrerubro'] !== $nombreRubroForm || $rubroActual['porcentaje'] !== $porcentajeRubroForm) {
                actualizarRubroExistente($idRubroForm, $nombreRubroForm, $porcentajeRubroForm);
            }
        } else {
            // El rubro no existe, agregar a la base de datos
            agregarNuevoRubro($idTema, $nombreRubroForm, $porcentajeRubroForm);
        }
    }

    // Eliminar rubros que no están en el formulario
    eliminarRubrosNoEnviados($rubrosActuales, $rubrosData);

    // Recalcular la calificación del tema
    recalcularCalificacionTema($idTema);

    // Enviar respuesta al cliente
    $respuesta = ['message' => 'Rubros actualizados exitosamente'];
    echo json_encode($respuesta);
    exit;
}

function obtenerRubrosActuales($idTema)
{
    global $cid; // Asegúrate de tener $cid definido en tu contexto

    $rubrosActuales = [];

    // Consulta SQL para obtener los idrubrotema relacionados con el tema
    $sql = 'SELECT idrubrotema, nombrerubro, porcentaje FROM rubrodetema WHERE idtemacalificar =' . $idTema;

    // Ejecutar la consulta
    $result = odbc_exec($cid, $sql);

    // Recorrer los resultados y almacenar en el array $rubrosActuales
    while ($row = odbc_fetch_array($result)) {
        $rubrosActuales[] = [
            'idRubroTema' => $row['idrubrotema'],
            'nombrerubro' => $row['nombrerubro'],
            'porcentaje' => $row['porcentaje']
        ];
    }
    return $rubrosActuales;
}

function encontrarRubroPorId($rubrosActuales, $idRubroForm)
{
    foreach ($rubrosActuales as $rubroActual) {
        if ($rubroActual['idRubroTema'] == $idRubroForm) {
            return $rubroActual;
        }
    }
    return null;
}

function actualizarRubroExistente($idRubroForm, $nombreRubroForm, $porcentajeRubroForm)
{
    global $cid; // Asegúrate de tener $cid definido en tu contexto

    // Consulta SQL para actualizar el rubro existente
    $sql = "UPDATE rubrodetema SET nombrerubro = '$nombreRubroForm', porcentaje = $porcentajeRubroForm WHERE idrubrotema = $idRubroForm";

    // Ejecutar la consulta
    $result = odbc_exec($cid, $sql);

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Éxito
        // Puedes agregar más lógica aquí si es necesario
    } else {
        // Error en la consulta
        // Puedes manejar el error según tus necesidades
        echo "Error al actualizar el rubro: " . odbc_errormsg($cid);
    }
}


function agregarNuevoRubro($idTema, $nombreRubroForm, $porcentajeRubroForm)
{
    global $cid; // Asegúrate de tener $cid definido en tu contexto

    // Consulta SQL para insertar un nuevo rubro
    $sql = "INSERT INTO rubrodetema (idtemacalificar, nombrerubro, porcentaje) VALUES ($idTema, '$nombreRubroForm', $porcentajeRubroForm)";

    // Ejecutar la consulta
    $result = odbc_exec($cid, $sql);

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Éxito
        // Puedes agregar más lógica aquí si es necesario
    } else {
        // Error en la consulta
        // Puedes manejar el error según tus necesidades
        echo "Error al agregar nuevo rubro: " . odbc_errormsg($cid);
    }
}


function eliminarRubrosNoEnviados($rubrosActuales, $rubrosData)
{
    foreach ($rubrosActuales as $rubroActual) {
        $idRubroActual = $rubroActual['idRubroTema'];
        $rubroEnviado = false;

        foreach ($rubrosData as $rubroForm) {
            $idRubroForm = $rubroForm['idRubro'];

            if ($idRubroActual == $idRubroForm) {
                $rubroEnviado = true;
                break;
            }
        }

        if (!$rubroEnviado) {
            // El rubro actual no fue enviado en el formulario, eliminarlo
            eliminarRubro($idRubroActual);
        }
    }
}

function eliminarRubro($idRubro)
{
    global $cid; // Asegúrate de tener $cid definido en tu contexto

    // Consulta SQL para eliminar el rubro
    $sql = "DELETE FROM rubrodetema WHERE idrubrotema = $idRubro";

    // Ejecutar la consulta
    $result = odbc_exec($cid, $sql);

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Éxito
        // Puedes agregar más lógica aquí si es necesario
    } else {
        // Error en la consulta
        // Puedes manejar el error según tus necesidades
        echo "Error al eliminar el rubro: " . odbc_errormsg($cid);
    }
}


function recalcularCalificacionTema($idTema)
{
    // Lógica para recalcular la calificación del tema con los nuevos datos de rubros
}

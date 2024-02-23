<?php
// guardar_rubros_porcentaje.php
require_once('conect.odbc.php'); //crea la conexión para la base de datos
//print_r($_POST);
$idTema = $_POST['idTema'];

// Recorrer los datos del formulario
for ($i = 1; isset($_POST['nombre' . $i]); $i++) {
    $nombreRubro = $_POST['nombre' . $i];
    $porcentajeRubro = $_POST['porcentaje' . $i];
    $idRubro = $_POST['idRubro' . $i];

    // Aquí deberías realizar las operaciones necesarias para guardar o actualizar
    // los datos en tu base de datos, utilizando $idTema, $nombreRubro, $porcentajeRubro y $idRubro.
    // Asegúrate de validar y sanitizar los datos antes de interactuar con la base de datos.
}

// Cierra la conexión a la base de datos
odbc_close($cid);
?>

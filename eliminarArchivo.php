<?php
session_start();

// Agrega la lógica para validar sesiones, como lo hiciste al inicio de tu script

if (isset($_POST['sfkey'])) {
    $sfkey = $_POST['sfkey'];
    $rutaArchivo = 'uploads/' . $sfkey . '.pdf';

    if (file_exists($rutaArchivo)) {
        unlink($rutaArchivo);
        // Puedes realizar operaciones adicionales aquí, como eliminar información en la base de datos
        echo 'Archivo eliminado correctamente.';
    } else {
        echo 'El archivo no existe.';
    }
} else {
    echo 'Error: No se proporcionó el identificador del archivo.';
}
?>

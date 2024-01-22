<?php
session_start();

// Agrega la lógica para validar sesiones, como lo hiciste al inicio de tu script

if (isset($_POST['sfkey']) && isset($_FILES['archivo'])) {
    $sfkey = $_POST['sfkey'];
    $archivo = $_FILES['archivo'];

    $rutaDestino = 'uploads/' . $sfkey . '.pdf';

    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        // Puedes realizar operaciones adicionales aquí, como actualizar información en la base de datos
        echo 'Archivo subido correctamente.';
    } else {
        echo 'Error al subir el archivo.';
    }
} else {
    echo 'Error: No se proporcionó el identificador del archivo o el archivo.';
}
?>
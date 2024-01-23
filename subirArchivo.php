<?php
// subirArchivo.php

session_start();

// Verificar si se recibió un archivo
if (isset($_FILES['archivo'])) {
    $sfkey = isset($_POST['sfkey']) ? $_POST['sfkey'] : '';

    // Verificar la extensión del archivo
    $fileName = $_FILES['archivo']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    if ($fileExtension !== 'pdf') {
        echo 'Error: El archivo debe ser un PDF.';
        exit;
    }

    // Verificar el tamaño del archivo
    $fileSize = $_FILES['archivo']['size']; // en bytes
    $maxSize = 10 * 1024 * 1024; // 10 MB en bytes
    if ($fileSize > $maxSize) {
        echo 'Error: El tamaño del archivo no debe superar los 10 MB.';
        exit;
    }

    // Obtener año y periodo desde las variables de sesión
    $ayo = isset($_SESSION['ayo']) ? $_SESSION['ayo'] : '';
    $periodo = isset($_SESSION['periodo']) ? $_SESSION['periodo'] : '';

    // Directorio base de destino
    $directorioBase = 'uploads/';

    // Directorio de destino específico para el año y periodo
    $directorioDestino = $directorioBase . $ayo . '/' . $periodo . '/';

    // Si el directorio no existe, intentar crearlo
    if (!file_exists($directorioDestino)) {
        if (!mkdir($directorioDestino, 0777, true)) {
            echo 'Error: No se pudo crear el directorio de destino.';
            exit;
        }
    }

    // Nombre de archivo único basado en sfkey
    $archivoDestino = $directorioDestino . $sfkey . '.pdf';

    // Mover el archivo al directorio de destino
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $archivoDestino)) {
        echo 'Éxito: Archivo subido correctamente.';
    } else {
        echo 'Error: No se pudo mover el archivo.';
    }
} else {
    echo 'Error: No se recibió ningún archivo.';
}
?>

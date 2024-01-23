<?php
session_start();

if (!isset($_SESSION['IdMast'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sfkey'])) {
        $sfkey = $_POST['sfkey'];

        // Obtener ayo y periodo desde las variables de sesión
        $ayo = $_SESSION['ayo'];
        $periodo = $_SESSION['periodo'];

        // Verificar que el archivo exista antes de intentar eliminarlo
        $archivo = 'uploads/' . $ayo . '/' . $periodo . '/' . $sfkey . '.pdf';

        if (file_exists($archivo)) {
            // Eliminar el archivo
            unlink($archivo);
            echo 'Archivo eliminado correctamente.';
        } else {
            echo 'El archivo no existe.';
        }
    } else {
        echo 'Parámetros incompletos.';
    }
} else {
    echo 'Acceso no permitido.';
}

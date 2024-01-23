<?php
// verArchivo.php
session_start();
// Obtén el valor de sfkey de la consulta
$sfkey = isset($_GET['sfkey']) ? $_GET['sfkey'] : '';

// Agrega el parámetro timestamp a la URL del archivo
$timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : '';

// Obtén los valores de ayo y periodo desde las variables de sesión
$ayo = isset($_SESSION['ayo']) ? $_SESSION['ayo'] : '';
$periodo = isset($_SESSION['periodo']) ? $_SESSION['periodo'] : '';

// Genera la URL completa del archivo PDF incluyendo ayo y periodo
$pdfUrl = 'uploads/' . $ayo . '/' . $periodo . '/' . $sfkey . '.pdf';
echo $pdfUrl;

// Imprime el iframe con la URL del archivo
echo "<iframe src='{$pdfUrl}?timestamp={$timestamp}' width='100%' height='600'></iframe>";
?>

<?php
// verArchivo.php

// Obtén el valor de sfkey de la consulta
$sfkey = isset($_GET['sfkey']) ? $_GET['sfkey'] : '';

// Agrega el parámetro timestamp a la URL del archivo
$timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : '';

// Genera la URL completa del archivo PDF
$pdfUrl = 'uploads/' . $sfkey . '.pdf';

// Imprime el iframe con la URL del archivo
echo "<iframe src='{$pdfUrl}?timestamp={$timestamp}' width='100%' height='600'></iframe>";
?>
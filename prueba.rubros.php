<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Página</title>
    <!-- Asegúrate de incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<form id="verRubrosForm" method="post" action="ver_rubros.php">
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th><a href="#" id="verRubros" data-idTema="6">Tema1</a></th>
        </tr>
        <!-- Aquí deberías tener tus datos dinámicos en lugar de este ejemplo estático -->
        <tr>
            <td>Nombre de ejemplo</td>
            <td>Calificación de ejemplo</td>
        </tr>
    </table>

    <!-- Campo oculto para enviar el ID del tema -->
    <input type="hidden" id="idTemaInput" name="idTema" value="">

</form>

<script>
$(document).ready(function() {
    // Evento de clic para la clase "verRubros"
    $("#verRubros").on("click", function(e) {
        e.preventDefault();

        // Obtener el identificador único del tema
        var idTema = $(this).data("idTema");

        // Asignar el valor al campo oculto
        $("#idTemaInput").val(idTema);

        // Enviar el formulario
        $("#verRubrosForm").submit();
    });
});
</script>

</body>
</html>

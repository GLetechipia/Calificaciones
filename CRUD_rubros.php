<!DOCTYPE html>
<html>
<head>
    <title>Formulario porcentaje</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="formulario">
    <div id="contenedor-nombres-porcentajes">
        <div class="fila" id="fila-1">
            <label for="nombre1">Nombre 1:</label>
            <input type="text" id="nombre1" name="nombre1" required>
            <label for="porcentaje1">Porcentaje 1:</label>
            <input type="number" id="porcentaje1" name="porcentaje1" min="0" max="100" required>
            <button type="button" class="eliminar-fila" style="display:none">Eliminar</button>
        </div>
    </div>
    <button type="button" id="agregar-fila">Agregar Nombre y Porcentaje</button>
    <input type="submit" value="Enviar">
</form>

<script>
    $(document).ready(function() {
        var filaIndex = 1;

        $("#agregar-fila").on("click", function() {
            filaIndex++;
            var nuevaFila = $("#fila-1").clone();
            nuevaFila.attr("id", "fila-" + filaIndex);
            nuevaFila.find("label").each(function() {
                var nuevoFor = $(this).attr("for").replace("1", filaIndex);
                $(this).attr("for", nuevoFor);
            });
            nuevaFila.find("input").each(function() {
                var nuevoId = $(this).attr("id").replace("1", filaIndex);
                var nuevoName = $(this).attr("name").replace("1", filaIndex);
                $(this).attr("id", nuevoId).attr("name", nuevoName).val("");
            });
            nuevaFila.find(".eliminar-fila").show();
            nuevaFila.find(".eliminar-fila").on("click", function() {
                $(this).closest(".fila").remove();
            });
            $("#contenedor-nombres-porcentajes").append(nuevaFila);
        });
    });
</script>

</body>
</html>

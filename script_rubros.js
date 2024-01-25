var filaIndex = 1;
$(document).ready(function () {

    $("#agregar-fila").on("click", function () {
        filaIndex++;
        var nuevaFila = $("#fila-1").clone();
        nuevaFila.attr("id", "fila-" + filaIndex);
        nuevaFila.find("label").each(function () {
            var nuevoFor = $(this).attr("for").replace("1", filaIndex);
            $(this).attr("for", nuevoFor);
        });
        nuevaFila.find("input").each(function () {
            var nuevoId = $(this).attr("id").replace("1", filaIndex);
            var nuevoName = $(this).attr("name").replace("1", filaIndex);
            $(this).attr("id", nuevoId).attr("name", nuevoName).val("");
        });
        nuevaFila.find(".eliminar-fila").show();
        nuevaFila.find(".eliminar-fila").on("click", function () {
            $(this).closest(".fila").remove();
        });
        $("#contenedor-nombres-porcentajes").append(nuevaFila);
    });
});

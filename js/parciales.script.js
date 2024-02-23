function verlista(sFKey) {
    // Muestra un indicador de carga mientras se espera la respuesta del servidor
    console.log("Clave del grupo:", sFKey);
    $("#destino").html("<p>Cargando...</p>");

    // Realiza la solicitud AJAX
    $.ajax({
        type: "POST",
        url: "obtener_calificaciones.php", // Asegúrate de que la ruta del archivo sea correcta
        data: { sFKey: sFKey },
    })
        .done(function (data) {
            // Actualiza el contenido de #destino con la respuesta recibida
            $("#destino").html(data);
        })
        .fail(function () {
            // Muestra un mensaje de error en caso de problemas con la solicitud
            $("#destino").html("<p>Error al cargar la información.</p>");
        });
}

$(document).ready(function () {
    var i = 1;
    
    $(document).on("click", "#verRubros", function (e) {
        e.preventDefault();

        // Obtener el identificador único del tema
        var idTema = $(this).data("idtema");

        console.log("ID del tema:", idTema); // Verifica que se está obteniendo el ID del tema correctamente

        // Asignar el valor al campo oculto
        $("#idTemaInput").val(idTema);

        // Enviar el formulario
        $("#verRubrosForm").submit();
    });

    $(document).on("click", ".btn_remove", function () {
        var button_id = $(this).attr("id");
        $("#row" + button_id + "").remove();
    });

    $("#submit").click(function () {
        $.ajax({
            url: "nombre.php",
            method: "POST",
            data: $("#add_name").serialize(),
            success: function (data) {
                alert(data);
                $("#add_name")[0].reset();
            },
        });
    });
});
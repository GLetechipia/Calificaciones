/***script.js */

//ver lista de alumnos de la materia en finales
function verlistaf(sfkeyy) {
  //obtenemos el sfkey
  sK = sfkeyy;

  $.ajax({
    type: "POST",
    url: "listaf.php",
    data: { sfkey: sK },
  })
    .done(function (msg) {
      $("#destino").html(msg);
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      $("#destino").html(
        "Error al mostrar los grupos" + textStatus + " " + errorThrown
      );
    });
}

$(document).ready(function () {
  var i = 1;
  $(document).on("submit", "#calif", function (e) {
    e.preventDefault();
    var dataSend = $("#calif").serializeArray();
    console.log("Datos del formulario", dataSend);
    $.ajax({
      type: "POST",
      url: "update.calif.php",
      contentType: "application/x-www-form-urlencoded",
      data: dataSend,
      success: function (response) {
        console.log(response);

        var mensajeDiv = $("#mensaje");
        var mensajeTexto = $("#mensaje-texto");

        if (response === "Datos actualizados correctamente") {
          mensajeDiv
            .removeClass("alert-danger")
            .addClass("alert-success")
            .fadeIn()
            .delay(3000)
            .fadeOut();
          mensajeTexto.text("Se han realizado los cambios exitosamente.");

        } else {
          mensajeDiv
            .removeClass("alert-success")
            .addClass("alert-danger")
            .fadeIn()
            .delay(3000)
            .fadeOut();
          mensajeTexto.text("No se realizaron cambios.");
        }
      },
      error: function (error) {
        console.log("Error en la solicitud AJAX: ", error);
      },
    });
  });
});

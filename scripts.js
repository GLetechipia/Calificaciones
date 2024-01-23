/***script.js */
//ver lista de alumnos de la materia en parciales
/***
function verlista(sfkeyy) {
  //obtenemos el sfkey
  sK = sfkeyy;

  $.ajax({
    type: "POST",
    url: "lista.php",
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
***/
//ver lista de alumnos de la materia en fianles
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
  /***
  $("#add").click(function () {
    i++;
    $("#dynamic_field").append(
      '<tr id="row' +
      i +
      '"><td><input type="text" name="rubro[]" placeholder="Descripcion" class="form-control name_list" /><input type="text" name="Porcentaje[]" placeholder="%" class="form-control name_list" /></td><td><button type="button" name="remove" id="' +
      i +
      '" class="btn btn-danger btn_remove">X</button></td></tr>'
    );
    verlista($_SESSION["sfkeyy"]);
  });
*///
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

  $(document).on("submit", "#calif", function (e) {
    e.preventDefault();
    var dataSend = $("#calif").serializeArray(); // Utiliza serialize en lugar de serializeArray
    console.log("Datos del formulario", dataSend);
    $.ajax({
      type: "POST",
      url: "update.calif.php",
      contentType: "application/x-www-form-urlencoded", // Asegúrate de que coincida con el tipo de datos que estás enviando
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
          // Puedes hacer más cosas aquí, como recargar la página o actualizar partes específicas de la interfaz de usuario.
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

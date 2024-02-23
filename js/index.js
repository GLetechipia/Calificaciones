function submitForm() {
    $.ajax({
        type: "POST",
        url: "acceso.php",
        data: $("#loginForm").serialize(),
        success: function(response) {
            if (response === 'success') {
                // Redireccionar a la página de menú si el acceso es exitoso
                window.location.href = 'menu.php';
            } else {
                // Mostrar el mensaje de error
                showErrorModal(response);
            }
        },
        error: function(error) {
            console.log('Error en la solicitud AJAX: ', error);
        }
    });
}

function showErrorModal(message) {
    $("#errorMessage").text(message);
    $(".error-modal").fadeIn();
    // Desaparece el modal después de 3 segundos
    setTimeout(function() {
        $(".error-modal").fadeOut();
    }, 3000);
}
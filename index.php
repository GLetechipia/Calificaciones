<?php
session_start();
if (isset($_SESSION['IdMast'])) header("Location: menu.php");

$error_message = '';

if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error_message = 'Usuario o contraseña incorrectos. Inténtelo nuevamente.';
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>SICENETXv2 ITSL</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img { font-size: 1.125rem; text-anchor: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }

            html, body {
                height: 100%;
            }

            body {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-align: center;
                align-items: center;
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #f5f5f5;
            }

            .form-signin {
                width: 100%;
                max-width: 330px;
                padding: 15px;
                margin: auto;
            }

            .form-signin .checkbox {
                font-weight: 400;
            }

            .form-signin .form-control {
                position: relative;
                box-sizing: border-box;
                height: auto;
                padding: 10px;
                font-size: 16px;
            }

            .form-signin .form-control:focus {
                z-index: 2;
            }

            .form-signin input[type="email"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }

            .form-signin input[type="password"] {
                margin-bottom: 10px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        }

        .error-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .error-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            text-align: center;
        }
    </style>
</head>
<body class="text-center">
<form class="form-signin" method="post" action="acceso.php" id="loginForm">
    <img class="mb-4" src="img/LogoTecLoretoV2_4.svg" alt="" width="150" height="150">
    <h1 class="h3 mb-3 font-weight-normal">Por favor escriba</h1>

    <label for="Usuario" class="sr-only">Usuario</label>
    <input type="text" name="Usuario" id="Usuario" class="form-control" placeholder="Usuario" required autofocus maxlength="20">

    <label for="Contrasena" class="sr-only">Contraseña</label>
    <input type="password" name="Contrasena" id="Contrasena" class="form-control" placeholder="Contraseña" required maxlength="20">

    <button class="btn btn-lg btn-primary btn-block" type="button" onclick="submitForm()">Entrar</button>
</form>

<div id="errorModal" class="error-modal">
    <div class="error-modal-content">
        <p id="errorMessage"></p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
<script>
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
</script>
</body>
</html>

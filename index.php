<?php
session_start();
if (isset($_SESSION['IdMast'])) header("Location: menu.php");
if (isset($_SESSION['numero_control'])) header("Location: alu/menu.php");
if (isset($_SESSION['administradores'])) header("Location: admin/index.php");


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
    <link href="css/style.index.css" rel="stylesheet">
</head>

<body class="text-center">
    <form class="form-signin" method="post" id="loginForm" autocomplete="off">
        <img class="mb-4" src="img/LogoTecLoretoV2_4.svg" alt="" width="150" height="150">
        <h1 class="h3 mb-3 font-weight-normal">Por favor escriba</h1>

        <label for="Usuario" class="sr-only">Usuario</label>
        <input type="text" name="Usuario" id="Usuario" class="form-control" placeholder="Nombre de Usuario" required autofocus maxlength="30">
    <br>
        <label for="Contrasena" class="sr-only">Contraseña</label>
        <input type="password" name="Contrasena" id="Contrasena" class="form-control" placeholder="Contraseña" required maxlength="30">
    <br>
        <label for="Contrasena" class="sr-only">Tipo Usuario</label>
        <select name="tipoUsuario" id="tipoUsuario" class="form-control" title="Tipo de usuario" require>
            <option value="alumno">Alumn@</option>
            <option value="docente">Profesor@</option>
            <option value="admin">Administrativ@</option>
        </select>
    <br>
        <button class="btn btn-lg btn-primary btn-block" type="button" onclick="submitForm()">Entrar</button>
        <br>
        <span> &copy; TecNM Campus Loreto - Letechipía 2024 &reg;</span>
    </form>
    <div id="errorModal" class="error-modal">
        <div class="error-modal-content">
            <p id="errorMessage"></p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script src="js/index.js"></script>
</body>

</html>
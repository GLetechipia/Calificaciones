<?php
session_start();

function num_row_counter($resultado)
{
    $items = 0;
    while ($row = odbc_fetch_array($resultado))
        $items++;
    return $items;
}

function obtener_departamentos_carreras($cadena)
{
    $parejas = array();

    // Eliminar el primer y último carácter "|"
    $cadena = substr($cadena, 1, -1);

    // Dividir la cadena en pares de números
    $pares = explode("||", $cadena);

    // Iterar sobre cada par de números
    foreach ($pares as $par) {
        if (strlen($par) == 4) { // Asegurarse de que el par tiene 4 caracteres
            $departamento = substr($par, 0, 2); // Obtener el código del departamento
            $carrera = substr($par, 2); // Obtener el código de la carrera

            $parejas[] = array($departamento, $carrera);
        }
    }

    return $parejas;
}
/**
$cadena = "|0000||0101||0301||0201||0400|";
$parejas = obtener_departamentos_carreras($cadena);

// Guardar en la variable de sesión
$_SESSION['departamentos_carreras'] = $parejas;

// Recuperar de la variable de sesión
$parejas_recuperadas = $_SESSION['departamentos_carreras'];

foreach ($parejas_recuperadas as $index => $pareja) {
    echo "<br>Par $index: Departamento: " . $pareja[0] . ", Carrera: " . $pareja[1] . "\n";
}

 */

 function encryptKey($key) {
    // Recorrer 7 espacios hacia adelante
    $encryptedKey = "";
    $key = strtoupper($key);
    $length = strlen($key);
    for ($i = 0; $i < $length; $i++) {
        $encryptedChar = chr(ord($key[$i]) -17);
        $encryptedKey .= $encryptedChar;
    }
    
    // Invertir la cadena
    $invertedKey = strrev($encryptedKey);
    
    return $invertedKey;
}


if (isset($_POST["tipoUsuario"], $_POST["Usuario"], $_POST["Contrasena"])) {

    require_once('conect.odbc.php'); //crea la conexión para la base de datos

    switch ($_POST["tipoUsuario"]) {
        case "docente":
            $consulta = "select IdMast, ape, nom, idD from docentes where Login='" . addslashes($_POST["Usuario"]) . "' and CveSice='" . addslashes($_POST["Contrasena"]) . "';";
           
            $result = odbc_exec($cid, $consulta);

            if (num_row_counter($result) > 0) {
                odbc_fetch_row($result);
                $_SESSION['IdMast'] = odbc_result($result, 1);
                $_SESSION['apellidos'] = odbc_result($result, 2);
                $_SESSION['nombre'] = odbc_result($result, 3);
                $_SESSION['idD'] = odbc_result($result, 4);
                $_SESSION['periodo'] = $ini['periodo'];
                $_SESSION['ayo'] = $ini['ayo'];

                odbc_free_result($result);
                odbc_close($cid);
                echo 'success';
            } else {
                odbc_free_result($result);
                odbc_close($cid);
                echo 'Usuario o contraseña incorrectos. Inténtelo nuevamente.';
            }
            break;
        case "alumno":
            $consulta = "select numcont, ape, nom, idD, iDc, idR, idE from alumnos where numcont='" . 
                        addslashes($_POST["Usuario"]) . "' and NIP='" . addslashes($_POST["Contrasena"]) . "';";
            $result = odbc_exec($cid, $consulta);

            if (num_row_counter($result) > 0) {
                odbc_fetch_row($result);
                $_SESSION['numero_control'] = odbc_result($result, 1);
                $_SESSION['apellidos'] = odbc_result($result, 2);
                $_SESSION['nombre'] = odbc_result($result, 3);
                $_SESSION['idD'] = odbc_result($result, 4);
                $_SESSION['IdC'] = odbc_result($result, 5);
                $_SESSION['idR'] = odbc_result($result, 6);
                $_SESSION['idE'] = odbc_result($result, 7);

                $_SESSION['periodo'] = $ini['periodo'];
                $_SESSION['ayo'] = $ini['ayo'];

                odbc_free_result($result);
                odbc_close($cid);
                echo 'success';
            } else {
                odbc_free_result($result);
                odbc_close($cid);
                echo 'Usuario o contraseña incorrectos. Inténtelo nuevamente.';
            }
            break;

        case "admin":
            $consulta = "select nombre, puesto, accesos, UADM, Tipo from usuarios where Login='" . addslashes($_POST["Usuario"]) . "' and ClaveU='" . encryptKey(addslashes($_POST["Contrasena"])) . "';";
             $result = odbc_exec($cid, $consulta);
            /**accesos son los departamentos y carreras a las que el usuario tiene acceso */
           
            if (num_row_counter($result) > 0) {
                odbc_fetch_row($result);
                $_SESSION['administradores'] = "1";
                $_SESSION['nombre'] = odbc_result($result, 1);
                $_SESSION['puesto'] = odbc_result($result, 2);
                $_SESSION['accesos'] = obtener_departamentos_carreras(odbc_result($result, 3));
                $_SESSION['UADM'] = odbc_result($result, 4);
                $_SESSION['Tipo'] = odbc_result($result, 5);

                $_SESSION['periodo'] = $ini['periodo'];
                $_SESSION['ayo'] = $ini['ayo'];

                odbc_free_result($result);
                odbc_close($cid);
                echo 'success';
            } else {
                odbc_free_result($result);
                odbc_close($cid);
                echo 'Usuario o contraseña incorrectos. Inténtelo nuevamente.';
            }
            break;
        default:
            echo 'Error: Datos de acceso no proporcionados.';
    }
}

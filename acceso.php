<?php
session_start();
//acceso a el sistema de boletas de calificaciones
$dsn = "sicenetxx"; //debe ser de sistema no de usuario
$usuario = "administrador";
$clave="";

$cid=odbc_connect($dsn, $usuario, $clave);


function num_row_counter($resultado){
   $items = 0;
while ($row = odbc_fetch_array($resultado))
   {
       $items++;                          
   } 
  //odbc_free_result($resultado);  
    
    return $items;
}


if(isset($_POST["Usuario"],$_POST["Contrasena"],$_POST["loginacces"])){
    $consulta="select IdMast, ape, nom, idD from docentes where Login='".addslashes($_POST["Usuario"])."' and CveSice='".addslashes($_POST["Contrasena"])."';";
    echo $consulta;
    $result = odbc_exec($cid,$consulta) or die(header("Location:index.html"));
     if (num_row_counter($result) > 0){
         odbc_fetch_row($result);
         $_SESSION['IdMast'] = odbc_result($result,1);
         $_SESSION['apellidos']=odbc_result($result,2);
         $_SESSION['nombre'] = odbc_result($result,3);
         $_SESSION['idD'] = odbc_result($result,4);
     
        echo $_SESSION['nombre'];
        echo $_SESSION['apellidos'];
        
        
        odbc_free_result($result); 
        header("Location:menu.php");
     }
     else{
        odbc_free_result($result);  
        header("Location:index.html");
    }
         
    }
else{
    odbc_free_result($result);  
    header("Location:index.html");
}

?>
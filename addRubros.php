<?php
	if(isset($_POST['idRubro_1']) && isset($_POST['Porcentaje_1']))
	{
	
        session_start();
        function conectarse(){
            $servername = "localhost";
            $username = "root";
            $password = "tooR";
            $dbname = "sicenetv2";
            $cid = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: ".$cid->connect_error);
            } 
            return $cid;
        }


        $cid=odbc_connect($dsn, $usuario, $clave,);


        if (!$cid){
            exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
        }	

        $rubro[] = $_POST['idRubro[]'];
        $Porcentaje[] = $_POST['Porcentaje[]'];
		
        if(isset($_POST['idRubro[]']) && isset($_POST['Porcetaje[]']))
        {

        }
        //$sql = "INSERT INTO tbl_nombre(nombre, fecha) VALUES('".mysqli_real_escape_string($connect, $_POST["name"][$i])."',now())";
		/*$query = "INSERT INTO rubros(sFKey=".$_SESSION['sfkeyy']." , numTema, nomTema, Porcentaje) VALUES('$sfkeyy', '$codmatri', '$Rubro[]', '$Porcentaje[]')";
		if (!$result = mysqli_query($con, $query)) {
	        exit(mysqli_error($con));
	    }
	    echo "Rubro agregado!";*/
	}
    
?>
<?php
function conectarse(){
	$servername = "localhost";
	$username = "root";
	$password = "tooR";
	$dbname = "sicenetv2";
	$cid = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
    	die("Connection failed: ".$conn->connect_error);
	} 
	return $cid;
}

	// Design initial table header 
	$data = '<table class="table table-bordered table-striped">
						<tr>
							<th>N°</th>
							<th>N° Tema</th>
							<th>N° Control</th>
							<th>Calificacion</th>
						</tr>';

	$query = "SELECT * FROM calrubros";

	if (!$result = mysqli_query($con, $query)) {
        exit(mysqli_error($con));
    }

    // if query results contains rows then featch those rows 
    if(mysqli_num_rows($result) > 0)
    {
    	$number = 1;
    	while($row = mysqli_fetch_assoc($result))
    	{
    		$data .= '<tr>
				<td>'.$number.['idRubro']'</td>
				<td>'.$row['NumTema'].'</td>
				<td>'.$row['NunCont'].'</td>
				<td>'.$row['calificacion'].'</td>
				<td>
					<button onclick="GetUserDetails('.$row['idobs'].')" class="btn btn-warning"><i class="fas fa-edit"></i></button>
				</td>
				<td>
					<button onclick="DeleteUser('.$row['idobs'].')" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
				</td>
    		</tr>';
    		$number++;
    	}
    }
    else
    {
    	// records now found 
    	$data .= '<tr><td colspan="6">No hay registros!</td></tr>';
    }

    $data .= '</table>';

    echo $data;
?>
<?php
session_start();
require('fpdf/fpdf.php');
//if(!isset($_SESSION['numero_control']))
//    header("Location:index.html");

//para la base de datos
require_once('../conect.odbc.php'); //crea la conexión para la base de datos
$NCTRL=$_SESSION['numero_control'];

$PERIODO=substr($_POST['ayoperiodo'],0,1);
$AYIO=substr($_POST['ayoperiodo'],2);



if (!$cid){
	exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}	
// consulta SQL a nuestra tabla "alumnos,materias,grupos,listas" que se encuentra en la base de datos.
$sql="SELECT Materias.Materia, Materias.CveMat, Materias.Sem, Grupos.G, Listas.Calif, Listas.TipoCal, (Docentes.Tit+' '+Docentes.Nom+' '+Docentes.Ape) AS Docente
FROM Alumnos INNER JOIN (Materias INNER JOIN (Docentes INNER JOIN (Grupos INNER JOIN Listas ON Grupos.sFKey = Listas.sFKey) ON Docentes.IdMast = Grupos.IdMast) ON (Materias.IdM = Grupos.IdM) AND (Materias.IdR = Grupos.IdR) AND (Materias.IdC = Grupos.IdC) AND (Materias.IdD = Grupos.IdD)) ON Alumnos.NumCont = Listas.NumCont
WHERE (((Alumnos.NumCont)='$NCTRL') AND ((Listas.Per)=$PERIODO) AND ((Grupos.YrP)=$AYIO));";
$rescalif=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

//consulta SQL a la tabla alumnos, departamentos, carrera para la infromacion del alumno y utilizado en el ecabezado del documento
$sqlalumno="SELECT Alumnos.NumCont, Alumnos.Nom, Alumnos.Ape, Carreras.Nombre, Departamentos.Nombre, Alumnos.CURP
FROM Departamentos INNER JOIN (Carreras INNER JOIN Alumnos ON (Carreras.IdC = Alumnos.IdC) AND (Carreras.IdD = Alumnos.IdD)) ON (Alumnos.IdD = Departamentos.IdD) AND (Departamentos.IdD = Carreras.IdD)
WHERE (((Alumnos.NumCont)='$NCTRL'));";
$infoalumno=odbc_exec($cid,$sqlalumno)or die(exit("Error en odbc_exec"));

class PDF extends FPDF
{
   //Cabecera de página
   function Header()
   {
       $this->Image('img/head12020.png',10,8,200);
       //$this->SetFont('Arial','B',12);
       //$this->Cell(30,10,'Title',1,0,'C');
   }

    function Footer()
    {        
        $this->Image('img/footer1-2022.png',10,240,200);
        //$this->SetY(-10);
        //$this->SetFont('Arial','I',8);
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
   } 
}

$pdf=new PDF();
$pdf->AddPage('P','letter');  //se crea una pagina Vertical, en tamaño Carta.............
//titulo del documento para la vizualicacion........
$pdf->SetTitle('Boleta de Calificaciones Finales');

$pdf->SetY(50);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(200,5,'Boleta de Calificaciones Finales',0,0,'C');

$pdf->SetFont('Arial','',12);

//se imprime la infromacion del alumno
$pdf->SetY(60);
$pdf->write(5,utf8_decode('Alumno: '));
$pdf->write(5,odbc_result($infoalumno,3).' '.odbc_result($infoalumno,2)); 
$pdf->Ln();
$pdf->write(5,utf8_decode('Número de Control: '.odbc_result($infoalumno,1))); $pdf->Ln();
$pdf->write(5,utf8_decode('Carrera: '));
$pdf->write(5,odbc_result($infoalumno,4)); $pdf->Ln();
$pdf->write(5,utf8_decode('Periodo: '.$PERIODO.' - '.$AYIO)); $pdf->Ln();
$pdf->SetY(80);

$pdf->SetFont('Arial','B',11);//Se imprime el encabezado de cada una de las columnas
        $pdf->Cell(105,5,'Materia',0,0,'C');
        $pdf->Cell(25,5,'CveMat',0,0,'C');
        $pdf->Cell(20,5,'Calif',0,0,'C');
        $pdf->Cell(20,5,'TipoCal',0,0,'C');
        $pdf->Cell(20,5,'Grupo',0,0,'C');
        $pdf->SetFont('Arial','',10);
    $pdf->Ln();
$cuenta=0;
$suma=0;
while(odbc_fetch_row($rescalif)){    //INFORMACION DE CADA UNA DE LAS MATERIAS DEL PERIODO....
        $pdf->Cell(105,5,odbc_result($rescalif,1),0);
        $pdf->Cell(25,5,odbc_result($rescalif,2),0,0,'C');
        $pdf->Cell(20,5,odbc_result($rescalif,5),1,0,'R');
        $pdf->Cell(20,5,odbc_result($rescalif,6),0,0,'C');
        $pdf->Cell(20,5,odbc_result($rescalif,3).' '.odbc_result($rescalif,4),0,0,'C');
    $pdf->Ln();
        $pdf->SetFont('Arial','',8);  //AQUI EL NOMBRE DEL DOCENTE........
        $pdf->Cell(190,4,'          '.odbc_result($rescalif,7),0);
        $pdf->SetFont('Arial','',10);
    $pdf->Ln();    
    
    $cuenta++;//PARA EL CALCULO DEL PROMEDIO FINAL
    $suma+=odbc_result($rescalif,5);//SUMA DE LAS CALIFICACIONES
}

// SE IMPRIME EL CALCULO DEL PROMEDIO CON DOS DECIMALES...
$pdf->Cell(130,5,utf8_decode('Promedio del período:'),0,0,'R');  
$pdf->Cell(20,5,number_format($suma/$cuenta,2),1,0,'R');

$pdf->SetY(230);
$pdf->Image('img/yolanda_isela.png',20,200,30);$pdf->Image('img/sellocontrolescolar.png',150,200,50);
$pdf->write(5,utf8_decode('Departamento de Control Escolar')); $pdf->Ln();
$pdf->write(5,md5($NCTRL.'-'.$PERIODO.'-'.$AYIO.'-'.$suma)); $pdf->Ln();

$pdf->Output('Boleta Calificaciones -'.$NCTRL.'-'.$PERIODO.'-'.$AYIO.'.pdf','I');  //2do parametro I Muestra en navegador para guardar, D muestra para guardar, F guarda en archivo local
?>
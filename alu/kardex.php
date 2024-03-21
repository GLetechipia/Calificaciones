<?php
session_start();
require('fpdf/fpdf.php');
if(!isset($_SESSION['numero_control']))
    header("Location:index.html");

//para la base de datos
$dsn = "sicenetxx"; //debe ser de sistema no de usuario
$usuario = "administrador";
$clave="";
$NCTRL=$_SESSION['numero_control'];

$PERIODO=2;
$AYIO=2019;

$cid=odbc_connect($dsn, $usuario, $clave);

if (!$cid){
	exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}	
// consulta SQL a nuestra tabla "alumnos,materias,grupos,listas" que se encuentra en la base de datos.
$sql="SELECT Materias.Materia, Materias.CveMat, Materias.Cred, Kardex.Calif, Kardex.TipoCal, Kardex.Repet, Materias.Sem, Kardex.Per, Kardex.YrP, Materias.IdM
FROM Materias INNER JOIN Kardex ON (Materias.IdD=Kardex.IdD) AND (Materias.IdC=Kardex.IdC) AND (Materias.IdR=Kardex.IdR) AND (Materias.IdM=Kardex.IdM)
WHERE (((Kardex.NumCont)='$NCTRL')) ORDER BY Materias.Sem;";

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
       $pdf->Image('img/head12020.png',10,8,200);
       //$this->SetFont('Arial','B',12);
       //$this->Cell(30,10,'Title',1,0,'C');
   }

    function Footer()
    {        
        $this->Image('img/footer1-2022.png',10,320,200);
        //$this->SetY(-10);
        //$this->SetFont('Arial','I',8);
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
   } 
}

$pdf=new FPDF();
$pdf->AddPage('P','legal');  //se crea una pagina Vertical, en tamaño Carta.............
//titulo del documento para la vizualicacion........
$pdf->SetTitle('Kardex de calificaciones');
$pdf->Image('img/head12020.png',10,8,200);
$pdf->Image('img/footer1-2022.png',10,320,200);

$pdf->SetY(45);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(200,5,'Kardex de Calificaciones',0,0,'C');

$pdf->SetFont('Arial','',11);

//se imprime la infromacion del alumno
$pdf->SetY(55);
$pdf->write(5,utf8_decode(odbc_result($infoalumno,1))); 
$pdf->Ln();
//$pdf->write(5,utf8_decode('Alumno: '));
$pdf->write(5,odbc_result($infoalumno,3).' '.odbc_result($infoalumno,2)); 
$pdf->Ln();
//$pdf->write(5,utf8_decode('Carrera: '));
$pdf->write(5,odbc_result($infoalumno,4)); $pdf->Ln();
//$pdf->write(5,utf8_decode('Periodo: '.$PERIODO.' - '.$AYIO)); $pdf->Ln();
$pdf->SetY(70);

$pdf->SetFont('Courier','B',8);//Se imprime el encabezado de cada una de las columnas
        $pdf->Cell(100,5,'Materia',0,0,'C');
        $pdf->Cell(15,5,'CveMat',0,0,'C');
        $pdf->Cell(10,5,'Cred',0,0,'C');
        $pdf->Cell(10,5,'Calif',0,0,'C');
        $pdf->Cell(15,5,'TipoCal',0,0,'C');
        $pdf->Cell(15,5,'Rep',0,0,'C');
        $pdf->Cell(10,5,'Sem',0,0,'C');
        $pdf->Cell(15,5,'Periodo',0,0,'C');
        
        $pdf->SetFont('Courier','',8);
    $pdf->Ln();
$cuenta=0;
$suma=0;
$creditos=0;

/**
1 Materias.Materia, 
2 Materias.CveMat, 
3 Materias.Cred, 
4 Kardex.Calif, 
5 Kardex.TipoCal, 
6 Kardex.Repet, 
7 Materias.Sem, 
8 Kardex.Per, 
9 Kardex.YrP, 
10 Materias.IdM
**/
while(odbc_fetch_row($rescalif)){    //INFORMACION DE CADA UNA DE LAS MATERIAS DEL PERIODO....
        $pdf->Cell(100,4,odbc_result($rescalif,1),0);
        $pdf->Cell(15,4,odbc_result($rescalif,2),0,0,'C');
        $pdf->Cell(10,4,odbc_result($rescalif,3),0,0,'R');
        $pdf->Cell(10,4,odbc_result($rescalif,4),0,0,'R');
        $pdf->Cell(15,4,odbc_result($rescalif,5),0,0,'C');
    if (odbc_result($rescalif,6)=='1') $pdf->Cell(15,4,'si',0,0,'C');
    else $pdf->Cell(15,4,'',0,0,'C');
        $pdf->Cell(10,4,odbc_result($rescalif,7),0,0,'R');
        //$pdf->Cell(15,4,odbc_result($rescalif,8),1);
        $pdf->Cell(15,4,odbc_result($rescalif,8).'-'.odbc_result($rescalif,9),0,0,'R');
        
    $pdf->Ln();    
    
    $cuenta++;//PARA EL CALCULO DEL PROMEDIO FINAL
    $suma+=odbc_result($rescalif,4);//SUMA DE LAS CALIFICACIONES
    $creditos+=odbc_result($rescalif,3);//SUMA DE CREDITOS APROBADOS
}

// SE IMPRIME EL CALCULO DEL PROMEDIO CON DOS DECIMALES...
$pdf->Cell(100,5,utf8_decode('Créditos del Plan: 260      Creditos Aprobados: '.$creditos.'   '.number_format(($creditos/260)*100,0).'%'),0,0);
$pdf->Cell(25,5,utf8_decode('Promedio'),0,0,'R');  
$pdf->Cell(10,5,number_format($suma/$cuenta,2),1,0,'R');
$pdf->Cell(55,5,'NA = No aprobado',0,0,'R');

$pdf->SetY(310);
$pdf->Image('img/yolanda_isela.png',20,290,30);//$pdf->Image('img/sellocontrolescolar.png',150,290,50);
$pdf->write(5,utf8_decode('Departamento de Control Escolar')); $pdf->Ln();
$pdf->write(5,md5($NCTRL.'-'.$PERIODO.'-'.$AYIO.'-'.$suma)); $pdf->Ln();

$pdf->Output('Boleta Calificaciones -'.$NCTRL.'-'.$PERIODO.'-'.$AYIO.'.pdf','I');  //2do parametro I Muestra en navegador para guardar, D muestra para guardar, F guarda en archivo local
?>
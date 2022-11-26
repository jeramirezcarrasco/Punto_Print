<?php	
require_once(__DIR__.'\fpdf\fpdf.php');
require_once(__DIR__.'\fpdi\autoload.php');
	
$pdf = new Fpdi();
$pdf->AddPage();
$pdf->setSourceFile("punto_print.pdf");

// $pdf = new FPDF();
// $pdf->AddPage();


// $pdf->SetFont('Arial','B',16);
// $pdf->Cell(40,10,'Hello World!');
// $pdf->Output();
?>
<?php
require_once 'db.php';
require_once 'fpdf.php';

$pdf = new FPDF('L'); // Paysage
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,utf8_decode('Liste des Rapports'),0,1,'C');
$pdf->Ln(5);

// Titres des colonnes
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'ID',1);
$pdf->Cell(50,10,utf8_decode('Titre'),1);
$pdf->Cell(40,10,utf8_decode('Catégorie'),1);
$pdf->Cell(140,10,utf8_decode('Description'),1);
$pdf->Cell(35,10,'Date',1);
$pdf->Ln();

// Données
$pdf->SetFont('Arial','',10);
$reports = $pdo->query("SELECT * FROM reports ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
foreach ($reports as $report) {
    $pdf->Cell(10,10,$report['id'],1);
    $pdf->Cell(50,10,utf8_decode($report['titre']),1);
    $pdf->Cell(40,10,utf8_decode($report['categorie']),1);

    // Sauvegarder position x/y avant la MultiCell
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Description sur plusieurs lignes
    $pdf->MultiCell(140,10,utf8_decode($report['description']),1);

    // Remonter à côté pour continuer sur la même ligne
    $pdf->SetXY($x + 140, $y);

    // Cellule date
    $pdf->Cell(35,10,$report['date_creation'],1);
    $pdf->Ln();
}

$pdf->Output('D', 'rapports.pdf');
?>

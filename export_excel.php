<?php
require_once 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Titre');
$sheet->setCellValue('C1', 'Catégorie');
$sheet->setCellValue('D1', 'Description');
$sheet->setCellValue('E1', 'Date');

$reports = $pdo->query("SELECT * FROM reports ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
$row = 2;
foreach ($reports as $report) {
    $sheet->setCellValue("A$row", $report['id']);
    $sheet->setCellValue("B$row", $report['titre']);
    $sheet->setCellValue("C$row", $report['categorie']);
    $sheet->setCellValue("D$row", $report['description']);
    $sheet->setCellValue("E$row", $report['date_creation']);
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="rapports.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

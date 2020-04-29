<?php

require_once __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

$filecsv = fopen("List/Jopup.csv", "r");

while (!feof($filecsv)) {
    $data = fgetcsv($filecsv);

    $name = trim($data[1]);
    $pen = trim($data[2]);
    $dept = trim($data[3]);

    print("Name: ".$name." PEN: ".$pen." Dept: ".$dept."\n");
}

fclose($filecsv);

/*try {
    $pdf = new FPDI();

    $pdf->AddPage();
    $pdf->setSourceFile('Template/certificate_jopup.pdf');
    $pdf->useTemplate($pdf->importPage(1), null, null, 0, 0, true);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetFont('Courier', 'B', 21);
    $pdf->SetXY(116, 101);
    $pdf->Cell(0, 0, "Kuldip Patel", 0, 2, 'C');

    $pdf->SetXY(85, 114);
    $pdf->Cell(20, 0, "Computer", 0, 2, 'C');

    $path = "Temp.png";
    $qrCode = new QrCode("Kuldip Patel"."-"."160840107036"."-"."Computer");
    $qrCode->setWriterByName('png');
    $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
    $qrCode->writeFile($path);
    $pdf->Image($path, 130, 151, 31, 31);
    unlink($path);

    $pdf->Output("Test1.pdf", 'F');
} catch (Exception $e) {
    print($e);
}*/

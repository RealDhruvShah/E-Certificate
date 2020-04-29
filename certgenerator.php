<?php

require_once __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

$data_source = [
    "CheifFaculty" => [
        "csv" => "Chief_Faculties.csv",
        "name" => 2,
        "dept" => 4,
        "pdf" => "certificate_chief_faculty.pdf",
        "outFile" => "chief_faculty.pdf",
        "outFolder" => "Cheif"
    ],
    "Faculty" => [
        "csv" => "Faculties.csv",
        "name" => 2,
        "dept" => 4,
        "pdf" => "certificate_faculty.pdf",
        "outFile" => "faculty.pdf",
        "outFolder" => "Faculty"
    ],
    "Coordinator" => [
        "csv" => "Coordinator.csv",
        "name" => 1,
        "dept" => 3,
        "event" => 5,
        "pdf" => "certificate_coordinator.pdf",
        "outFile" => "coordinator.pdf",
        "outFolder" => "Coordinator"
    ],
    "DC" => [
        "csv" => "DC.csv",
        "name" => 1,
        "dept" => 3,
        "pdf" => "certificate_dc.pdf",
        "outFile" => "dc.pdf",
        "outFolder" => "DC"
    ],
    "Volunteer" => [
        "csv" => "Volunteer.csv",
        "name" => 1,
        "dept" => 3,
        "event" => 5,
        "pdf" => "certificate_volunteer.pdf",
        "outFile" => "volunteer.pdf",
        "outFolder" => "Volunteer"
    ],
    // "JOPUP" => [
    //     "csv" => "Jopup.csv",
    //     "event" => 0,
    //     "name" => 1,
    //     "pen" => 2,
    //     "dept" => 3,
    //     "link" => 5,
    //     "pdf" => "certificate_JoPuP.pdf",
    //     "outFile" => "JoPuP.pdf",
    //     "outFolder" => "Jopup"

    "JOPUP" => [
        "csv" => "Jopupfaculty.csv",
        "event" => 0,
        "name" => 1,
        "email" => 2,
        "dept" => 3,
        "link" => 5,
        "workas" => 6,
        "pdf" => "certificate_jopupfaculty.pdf",
        "outFile" => "JoPuP.pdf",
        "outFolder" => "Jopup"
    ],
];


$mode = "JOPUP";
$currentData = $data_source[$mode];

$filecsv = fopen("List/".$currentData["csv"], "r");

$firstSkiped = false;
$hasError = false;
while (!feof($filecsv)) {
    $data = fgetcsv($filecsv);

    if($firstSkiped == false){
        $firstSkiped = true;
        continue;
    }

    $name = trim($data[$currentData["name"]]);
    $dept = trim($data[$currentData["dept"]]);
    $event = "";
    $pen = "";
    $email = "";
    $link = "";
    $workas = "";

    print("Name: ".$name."\n");
    print("Department: ".$dept."\n");

    if(isset($currentData["event"])){
        $event = trim($data[$currentData["event"]]);
        print("Event: ".$event."\n");
    }

    if(isset($currentData["pen"])){
        $pen = trim($data[$currentData["pen"]]);
        print("PEN: ".$pen."\n");
    }

    if(isset($currentData["email"])){
        $email = trim($data[$currentData["email"]]);
        print("Email: ".$email."\n");
    }
    if(isset($currentData["event"])){
        $event = trim($data[$currentData["event"]]);
        print("Event: ".$event."\n");
    }
    if(isset($currentData["link"])){
        $link = trim($data[$currentData["link"]]);
        print("Verification Link: ".$link."\n");
    }

    if(isset($currentData["workas"])){
        $workas = trim($data[$currentData["workas"]]);
        print("Work As: ".$workas."\n");
    }

    try {
        $pdf = new FPDI();

        $pdf->AddPage();
        $pdf->setSourceFile('Template/'.$currentData["pdf"]);
        $pdf->useTemplate($pdf->importPage(1), null, null, 0, 0, true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('arial', '', 17);

        if($mode == "Faculty" || $mode == "CheifFaculty") {
            $pdf->SetXY(61, 118);
            $pdf->Cell(0, 0, $name, 0, 2, 'C');

            $pdf->SetXY(121, 130.5);
            $pdf->Cell(20, 0, $dept, 0, 2, 'C');
        } else if($mode == "DC"){
            $pdf->SetXY(61, 118);
            $pdf->Cell(0, 0, $name, 0, 2, 'C');

            $pdf->SetXY(142, 130.5);
            $pdf->Cell(20, 0, $dept, 0, 2, 'C');
        } else if($mode == "Coordinator" || $mode == "Volunteer"){
            $pdf->SetXY(61, 118.5);
            $pdf->Cell(0, 0, $name, 0, 2, 'C');

            $pdf->SetXY(123, 130);
            $pdf->Cell(20, 0, $dept, 0, 2, 'C');

            $pdf->SetXY(112, 142);
            $pdf->Cell(20, 0, $event, 0, 2, 'C');
        } else if($mode == "JOPUP"){
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Courier', 'B', 15);

            // $pdf->SetXY(116, 101);
            // $pdf->Cell(0, 0, $name, 0, 2, 'C');
            //
            // $pdf->SetXY(85, 114);
            // $pdf->Cell(20, 0, $dept, 0, 2, 'C');

            $pdf->SetXY(150, 100);         //for JoPup Faculties
            $pdf->Cell(0, 0, $name, 0, 2, 'C');

            $pdf->SetXY(114, 112);
            $pdf->Cell(22, 0, $workas, 0, 2, 'C');

            $path = "Temp.png";
          //  $qrCode = new QrCode("\n"."Event Name: " .$event."\n"."Name: ".$name."\n"."Enrollment No: ".$pen."\n"."Institute: ".$dept."\n"."Verification Link: ".$link);
            $qrCode = new QrCode("\n"."Event Name: " .$event."\n\n"."Name: ".$name."\n"."Email: ".$email."\n"."Institute: ".$dept."\n"."Verification Link: ".$link);
            $qrCode->setWriterByName('png');
            $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
            $qrCode->writeFile($path);
            $pdf->Image($path, 128.5, 149, 34, 34);
            unlink($path);
        }

      //  $outputName = $name." ".$dept." ".$currentData["outFile"];
        $outputName = $name." ".$workas." ".$currentData["outFile"];
        $pdf->Output("Certificate/".$currentData["outFolder"]."/".preg_replace('/\s+/', '_', $outputName), 'F');
    } catch (Exception $e) {
        print($e);
        $hasError = true;
    }
}

if($hasError == true){
    print("Certificate Generation Error!");
} else {
    print("Certificate Generation Done!");
}

fclose($filecsv);

<?php
require('../fpdf/fpdf.php');
include('../config/database.php');

$reportType = $_GET['report_type'] ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

if (empty($reportType) || empty($fromDate) || empty($toDate)) {
    die("Missing required parameters.");
}

class PDF extends FPDF {
    function Header() {
        // Logo (adjust width and path as needed)
        $this->Image('../assets/img/logo.png', 10, 6, 25); // x, y, width in mm

        // Title beside the logo
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'Clinic Management System',0,1,'C');
        $this->Ln(2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function addReportTitle($title, $from, $to) {
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,$title,0,1,'C');
        $this->SetFont('Arial','',11);
        $this->Cell(0,8,"From: $from   To: $to",0,1,'C');
        $this->Ln(5);
    }

    function tableHeader($headers, $widths) {
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(230,230,230);
        foreach ($headers as $i => $header) {
            $this->Cell($widths[$i],8,$header,1,0,'C',true);
        }
        $this->Ln();
    }

    function tableRow($data, $widths, $fill) {
        $this->SetFont('Arial','',10);
        foreach ($data as $i => $val) {
            $this->Cell($widths[$i],8,$val,1,0,'C',$fill);
        }
        $this->Ln();
    }
}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$title = ucwords(str_replace('_', ' ', $reportType)) . " Report";
$pdf->addReportTitle($title, $fromDate, $toDate);

switch ($reportType) {
    case 'admissions_report':
        $query = "SELECT * FROM admissions WHERE DATE(created_at) BETWEEN '$fromDate' AND '$toDate'";
        $result = mysqli_query($conn, $query);

        $headers = ['Person ID', 'Name', 'Course', 'Diagnosis', 'Status'];
        $widths = [30, 50, 30, 40, 30];
        $pdf->tableHeader($headers, $widths);

        $fill = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['firstname'] . ' ' . $row['lastname'];
            $pdf->tableRow([$row['person_id'], $name, $row['course'], $row['diagnosis'], $row['status']], $widths, $fill);
            $fill = !$fill;
        }
        break;

    case 'medical_report':
        $query = "SELECT * FROM medical_records WHERE DATE(created_at) BETWEEN '$fromDate' AND '$toDate'";
        $result = mysqli_query($conn, $query);

        $headers = ['Student ID', 'Hospitalized', 'Surgeries', 'Allergies', 'Conditions'];
        $widths = [25, 30, 30, 55, 50];
        $pdf->tableHeader($headers, $widths);

        $fill = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $pdf->tableRow([
                $row['student_id'],
                $row['hospitalized'],
                $row['surgeries'],
                $row['allergies'],
                $row['existing_conditions']
            ], $widths, $fill);
            $fill = !$fill;
        }
        break;

    case 'inventory_report':
        $query = "SELECT * FROM medicines WHERE DATE(created_at) BETWEEN '$fromDate' AND '$toDate'";
        $result = mysqli_query($conn, $query);

        $headers = ['Name', 'Brand', 'Description', 'Dosage'];
        $widths = [45, 40, 70, 30];
        $pdf->tableHeader($headers, $widths);

        $fill = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $pdf->tableRow([
                $row['name'],
                $row['brand'],
                $row['description'],
                $row['dosage']
            ], $widths, $fill);
            $fill = !$fill;
        }
        break;

    default:
        $pdf->Cell(0,10,'Invalid Report Type.',0,1,'C');
        break;
}

$pdf->Output();
exit;

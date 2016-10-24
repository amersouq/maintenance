<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;
//var_dump($_POST);die();
$ticketsStatusReportImage = $_POST['ticketsStatusReportImage'];
file_put_contents('reports/ticketsStatusReportImage.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $ticketsStatusReportImage)));
$ticketsStatusReportImagePreviousMonth = $_POST['ticketsStatusReportImagePreviousMonth'];
file_put_contents('reports/ticketsStatusReportImagePreviousMonth.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $ticketsStatusReportImagePreviousMonth)));
$ticketsTypesCurrentMonthImage = $_POST['ticketsTypesCurrentMonthImage'];
file_put_contents('reports/ticketsTypesCurrentMonthImage.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $ticketsTypesCurrentMonthImage)));
$ticketsTypesPreviousMonthImage = $_POST['ticketsTypesPreviousMonthImage'];
file_put_contents('reports/ticketsTypesPreviousMonthImage.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $ticketsTypesPreviousMonthImage)));
$monthsTicketsImage = $_POST['monthsTicketsImage'];
file_put_contents('reports/monthsTicketsImage.png', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $monthsTicketsImage)));


require('fpdf/fpdf.php');

class PDF extends FPDF {

// Page header
    function Header() {
        // Logo
        $this->Image('reports/logo.png', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'Jouf University', 0, 0, 'C');
        $this->Ln(10);
        $this->Cell(80);
        $this->Cell(30, 10, 'Maintenance Department Tickets Report', 0, 0, 'C');
        $this->Ln(10);
        $this->Cell(80);
        $previousMonthStartDate = date('Y-m-d', strtotime(date('Y-m') . " -1 month"));
        $this->Cell(30, 10, date('F Y', strtotime($previousMonthStartDate)), 0, 0, 'C');
        // Line break
//        $this->Ln(20);
    }

// Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdf->Image('reports/ticketsStatusReportImage.png', 5, 40, 120, 90, 'PNG');
$pdf->Image('reports/ticketsStatusReportImagePreviousMonth.png', 105, 40, 120, 90, 'PNG');
$pdf->Image('reports/ticketsTypesCurrentMonthImage.png', 5, 130, 120, 90, 'PNG');
$pdf->Image('reports/ticketsTypesPreviousMonthImage.png', 105, 130, 120, 90, 'PNG');
//$pdf->Image('reports/monthsTicketsImage.png', 0, 220, 90, 300, 'PNG');
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdf->Image('reports/monthsTicketsImage.png', 0, 50, 200, 0, 'PNG');
//$pdf->Output();
$path = "reports/summary.pdf";
$pdf->Output($path,'F');
//die();
$previousMonthStartDate = date('Y-m-d', strtotime(date('Y-m') . " -1 month"));
$files = "reports/summary.pdf";//$fname;
var_dump($files);
// email fields: to, from, subject, and so on
$to = "mahmoud.amer.m@gmail.com";
$from = "JuUniversityMaintenance.com";
$subject = "Maintenance Tickets Reports " . date('F Y', strtotime($previousMonthStartDate));
$message = "";
$message .= "Maintenance Tickets Reports ".date('F Y', strtotime($previousMonthStartDate)).". For detailed reports, please open the system " . get_site_url() . "/staff-reports";
$headers = "From: $from";
//$headers .= "CC: mahmoud.amer.m@gmail.com\r\n";
// boundary 
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

// headers for attachment 
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

// multipart boundary 
$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
$message .= "--{$mime_boundary}\n";

// preparing attachments
//for ($x = 0; $x < count($files); $x++) {
    $file = fopen($files, "rb");
    $data = fread($file, filesize($files));
    fclose($file);
    $data = chunk_split(base64_encode($data));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files\"\n" .
            "Content-Disposition: attachment;\n" . " filename=\"$files\"\n" .
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";
//}

// send

$ok = @mail($to, $subject, $message, $headers);
if ($ok) {
    echo "<p>mail sent to $to!</p>";
} else {
    echo "<p>mail could not be sent!</p>";
}
//wp_redirect(get_site_url() . '/staff-reports');
echo'<script> window.location="'.get_site_url().'/staff-reports"; </script> ';



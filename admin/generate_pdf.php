<?php
require_once('../controller/db_model.php');
require_once('tcpdf/tcpdf.php');

// pdo template
$stmt = $conn->prepare("SELECT 
                            p.pName, 
                            p.pType,
                            o.oQty,     
                            SUM(o.oQty) AS total_sold
                        FROM products p
                        INNER JOIN orders o ON p.pID = o.pID
                        INNER JOIN transactions t ON o.tID = t.tID
                        WHERE t.tStatus = 6
                        GROUP BY p.pID
                        ORDER BY total_sold DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

class CustomPDF extends TCPDF {
    public function Header() {
        // Set background color to light blue
        $this->SetFillColor(74,144,226); // RGB for light blue
        $this->Rect(0, 0, $this->getPageWidth(), 15, 'F'); // Draw rectangle with fill color

        // Set font and write header text
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(255, 255, 255); // Set text color to white
        $this->Cell(0, 15, 'Potchitos Buns and Cookies', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
}

$pdf = new CustomPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Potchitos');
$pdf->SetTitle('Potchitos Buns and Cookies');
$pdf->SetSubject('File Report');
$pdf->SetKeywords('TCPDF, PDF, report');

$currentDate = date('Y-m-d');
$pdf->SetHeaderData('', 0, 'Potchitos Buns and Cookies', 'Report - ' . $currentDate);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';

$currentMonth = '';

$pdf->AddPage();

$reportType = $_GET['report'];
if ($reportType == 'sales') {
  $pdf->SetFont('helvetica', 'B', 16);
  $pdf->Write(0, 'Sales Report', '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->Write(0, $currentDate, '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', '', 12);

} elseif ($reportType == 'products') {
  $pdf->SetFont('helvetica', 'B', 16);
  $pdf->Write(0, 'Products Report', '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->Write(0, $currentDate, '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', '', 12);

  $html .= '<table class="table table-bordered">';
  $html .= '<thead class="thead-dark">';
  $html .= '<tr>
              <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Total Sold</th>
                    </tr>
                </thead>
            </tr>';
  $html .= '</thead>';
  $html .= '<tbody>';

  foreach($products as $product){
    $html .= '<tr>';
    $html .= '<td>' . $product['pName'] . '</td>';
    $html .= '<td>' . $product['oQty'] . '</td>';
    // $html .= '<td>' . $product['Total'] . '</td>';
    $html .= '</tr>';
  }

  $html .= '</tbody>';
  $html .= '</table>';

} elseif ($reportType == 'orders') {
  $pdf->SetFont('helvetica', 'B', 16);
  $pdf->Write(0, 'Orders Report', '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->Write(0, $currentDate, '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', '', 12);
}
$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('report.pdf', 'I');
?>


<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');
require('../controller/db_model.php');

// Capture search query and date filters from URL parameters (if needed)
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the SQL query to retrieve transaction data with user information
$stmt = $conn->prepare('SELECT t.tID, CONCAT(u.uFName, " ", u.uLName) AS uName, t.tType, t.tDateOrder, t.tDateClaim, t.tPayStatus, t.tStatus, t.tPayRemain, t.tDateOrder
                        FROM transactions t
                        INNER JOIN users u ON t.uID = u.uID
                        ORDER BY tID DESC');
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define the custom PDF class for headers and footers
class MYPDF extends TCPDF {
    public function Header() {
        $pageWidth = $this->getPageWidth();
        $image_file = K_PATH_IMAGES . 'logo_example1.jpg';
        $logoWidth = 50;
        $this->Image($image_file, ($pageWidth - $logoWidth) / 2, 10, $logoWidth, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 15);
        $this->SetY(0);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Report generated on ' . date('Y-m-d H:i:s'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// Create a new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name or Organization');
$pdf->SetTitle('Product Sales Report');
$pdf->SetSubject('Product Information');
$pdf->SetKeywords('TCPDF, PDF, report, product, sales');

$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 9);

// Header
$pdf->writeHTML('<h2 style="text-align: center;">Orders</h2>', true, false, true, false, '');
$pdf->Ln(10);

// Table header
$html = '<table style="width: 100%" border="1" cellspacing="0" cellpadding="5">';
$html .= '<thead>';
$html .= '<tr>
            <th style="width: 50px; text-align: center; font-weight: bold;">Order ID</th>
            <th style="width: 248px; text-align: center; font-weight: bold;">Products</th>
            <th style="width: 100px; text-align: center; font-weight: bold;">Order Date</th>
            <th style="width: 70px; text-align: center; font-weight: bold;">Payment Method</th>
            <th style="width: 70px; text-align: center; font-weight: bold;">Payment Status</th>
            <th style="width: 100px; text-align: center; font-weight: bold;">Total Payment</th>
          </tr>';
$html .= '</thead>';
$html .= '<tbody>';

$currentMonth = '';

foreach ($transactions as $transaction) {
    $orderDate = date('F Y', strtotime($transaction['tDateOrder']));
    if ($currentMonth != $orderDate) {
        $currentMonth = $orderDate;
        $html .= '<tr><td colspan="6"><strong>' . $currentMonth . '</strong></td></tr>';
    }

    // Fetch order items for this transaction
    $stmt = $conn->prepare('SELECT c.cID, o.oID, p.pID, p.pType, p.pName, p.pPrice, o.oQty, cfName, csSize, cInstructions, cMessage, cc.ccName, cLayers,
                            CASE WHEN p.pType = 3 
                                THEN (p.pPrice + (COALESCE(cf.cfPrice, 0) * COALESCE(c.cLayers, 0) * COALESCE(cs.csSize, 0)))
                                ELSE p.pPrice
                                END AS total, p.pPrepTime
                            FROM orders o
                            INNER JOIN products p ON o.pID = p.pID
                            LEFT JOIN cakes c ON o.oID = c.oID
                            LEFT JOIN cakes_flavor cf ON c.cfID = cf.cfID
                            LEFT JOIN cakes_size cs ON c.csID = cs.csID
                            LEFT JOIN cakes_color cc ON c.ccID = cc.ccID
                            WHERE o.tID = ?;');
    $stmt->execute([$transaction['tID']]);
    $transactionItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html .= '<tr>';
    $html .= '<td style="width: 50px; text-align: center; font-weight: bold;">' . htmlspecialchars($transaction['tID']) . '</td>
    <td style="width: 248px; text-align: center; font-weight: bold;"><ul>';
    $totalPay = 0;

    foreach ($transactionItems as $item) {
        $html .= '<li><b>' . htmlspecialchars($item['pName']) . '</b> <small>x' . htmlspecialchars($item['oQty']) . '</small>';
        if ($item['pType'] == 3) {
            $html .= '<small><ul style="list-style-type: none;">
                        <li>Flavor: ' . htmlspecialchars($item['cfName']) . '</li>
                        <li>Size: ' . htmlspecialchars($item['csSize']) . '</li>
                        <li>Color: ' . htmlspecialchars($item['ccName']) . '</li>
                        <li>Layers: ' . htmlspecialchars($item['cLayers']) . '</li>
                        <li>Message: ' . htmlspecialchars($item['cMessage']) . '</li>
                        <li>Instructions: ' . htmlspecialchars($item['cInstructions']) . '</li>
                      </ul></small>';
        }
        $html .= '</li>';
        $totalPay += $item['total'] * $item['oQty'];
    }
    $html .= '</ul></td>';
    $html .= '<td style="width: 100px; text-align: center; font-weight: bold;">' . date('F j, Y, g:i A', strtotime($transaction['tDateOrder'])) . '</td>';
    $html .= '<td style="width: 70px; text-align: center; font-weight: bold;">GCash</td>';
    $html .= '<td style="width: 70px; text-align: center; font-weight: bold;">' . ($transaction['tType'] == 1 ? 'Fully Paid' : ($transaction['tType'] == 2 ? 'Deposit' : '')) . '</td>';
    $html .= '<td style="width: 100px; text-align: center; font-weight: bold;">Php ' . number_format($totalPay, 2) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('product_sales_' . date('Y-m-d') . '.pdf', 'D');

// Close the database connection
$pdo = null;
?>

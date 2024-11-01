<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');
require('../controller/db_model.php');

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
$pdf->writeHTML('<h2 style="text-align: center;">Annual Sales Report</h2>', true, false, true, false, '');
$pdf->Ln(10);

// Define the queries for daily, weekly, monthly, and yearly transactions
$queries = [
    'daily' => "SELECT 
                    CONCAT(DATE_FORMAT(d.date, '%b %d'), ' (', DAYNAME(d.date), ')') AS day_of_week,
                    IFNULL(t.transactions, 0) AS transactions
                FROM
                    (SELECT CURDATE() - INTERVAL n DAY AS date FROM (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6) numbers) d
                LEFT JOIN
                    (SELECT DATE(tDateOrder) AS date, COUNT(*) AS transactions FROM transactions WHERE tDateOrder >= CURDATE() - INTERVAL 6 DAY AND tStatus = 6 GROUP BY DATE(tDateOrder)) t
                ON d.date = t.date
                ORDER BY d.date;",
    'weekly' => "SELECT 
                    CONCAT(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (n.n - 1) WEEK) + INTERVAL 1 - WEEKDAY(CURDATE()) DAY, '%b %d'),
                    ' to ',
                    DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (n.n - 1) WEEK) + INTERVAL 7 - WEEKDAY(CURDATE()) DAY, '%b %d')) AS week_span,
                    COALESCE(COUNT(t.tDateOrder), 0) AS total_sold
                FROM 
                    (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) n
                LEFT JOIN transactions t ON WEEK(t.tDateOrder, 1) = WEEK(DATE_SUB(CURDATE(), INTERVAL (n.n - 1) WEEK) + INTERVAL 1 - WEEKDAY(CURDATE()) DAY, 1)
                WHERE t.tDateOrder IS NULL OR t.tDateOrder >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)
                GROUP BY n.n
                ORDER BY n.n DESC;",
    'monthly' => "SELECT 
                    CONCAT(MONTHNAME(d.month), ', ', YEAR(d.month)) AS month_name,
                    IFNULL(t.transactions, 0) AS transactions
                FROM
                    (SELECT DATE_FORMAT(CURDATE() - INTERVAL (12 - n) MONTH, '%Y-%m-01') AS month FROM (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) numbers) d
                LEFT JOIN
                    (SELECT DATE_FORMAT(tDateOrder, '%Y-%m-01') AS month, COUNT(*) AS transactions FROM transactions WHERE YEAR(tDateOrder) = YEAR(CURDATE()) AND tStatus = 6 GROUP BY DATE_FORMAT(tDateOrder, '%Y-%m-01')) t
                ON d.month = t.month
                ORDER BY d.month;"
];

// Execute queries and generate tables for each period
foreach ($queries as $period => $sql) {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML table
    $html = '<h3>' . ucfirst($period) . ' Transactions</h3>';
    $html .= '<table style="width: 100%;" border="1" cellspacing="0" cellpadding="5">';
    $html .= '<thead>';
    $html .= '<tr>';

    // Dynamically create headers based on keys
    foreach (array_keys($results[0] ?? ['No data' => '']) as $key) {
        $html .= '<th style="text-align: center; font-weight: bold;">' . htmlspecialchars($key) . '</th>';
    }
    $html .= '</tr></thead><tbody>';

    // Populate table rows
    foreach ($results as $row) {
        $html .= '<tr>';
        foreach ($row as $value) {
            $html .= '<td style="text-align: center;">' . htmlspecialchars($value) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table><br><br>';

    // Add the table to the PDF
    $pdf->writeHTML($html, true, false, true, false, '');
}

// Close and output PDF document
$pdf->Output('product_sales_' . date('Y-m-d') . '.pdf', 'D');

// Close the database connection
$conn = null;
?>

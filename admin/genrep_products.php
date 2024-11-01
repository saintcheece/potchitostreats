<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');
require('../controller/db_model.php');

// Capture search query and date filters from URL parameters (if needed)
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the SQL query to retrieve product data with total quantities sold
$sql = "
    SELECT 
    p.pName, 
    p.pType,
    SUM(o.oQty) AS total_quantity_sold,
    SUM(
        CASE 
            WHEN p.pType = 3 
                THEN (p.pPrice + (COALESCE(cf.cfPrice, 0) * COALESCE(c.cLayers, 0) * COALESCE(cs.csSize, 0))) * o.oQty
            ELSE p.pPrice * o.oQty
        END
    ) AS total_revenue
FROM 
    products p
INNER JOIN 
    orders o ON p.pID = o.pID
LEFT JOIN 
    cakes c ON o.oID = c.oID
LEFT JOIN 
    cakes_flavor cf ON c.cfID = cf.cfID
LEFT JOIN 
    cakes_size cs ON c.csID = cs.csID
LEFT JOIN 
    cakes_color cc ON c.ccID = cc.ccID
INNER JOIN 
    transactions t ON o.tID = t.tID
WHERE 
    t.tStatus = 6
GROUP BY 
    p.pID, p.pName, p.pType
ORDER BY 
    total_quantity_sold DESC;
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as an associative array

// Define the custom PDF class for headers and footers
class MYPDF extends TCPDF {
    public function Header() {
        // Get the width of the page
        $pageWidth = $this->getPageWidth();
                
        // Define the logo file and width
        $image_file = K_PATH_IMAGES . 'logo_example1.jpg';
        $logoWidth = 50; // Set your logo width here
        
        // Center the logo
        $this->Image($image_file, ($pageWidth - $logoWidth) / 2, 10, $logoWidth, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Set font for the title
        $this->SetFont('helvetica', 'B', 15);
        
        // Set Y position for the title below the logo
        $this->SetY(0); // Adjust Y position if needed
    }

    public function Footer() {
        // Position footer 15mm from the bottom
        $this->SetY(-15);
        
        // Set font for the footer
        $this->SetFont('helvetica', 'I', 8);
        
        // Add the report generated on date, aligned to the left
        $this->Cell(0, 10, 'Report generated on ' . date('Y-m-d H:i:s'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
    
        // Move to the right for the page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// Create a new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name or Organization');
$pdf->SetTitle('Product Sales Report');
$pdf->SetSubject('Product Information');
$pdf->SetKeywords('TCPDF, PDF, report, product, sales');

// Set header and footer fonts
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font for the document
$pdf->SetFont('helvetica', '', 9);

// Header
$pdf->writeHTML('<h2 style="text-align: center;">Product Sales Report</h2>', true, false, true, false, '');
$pdf->Ln(10);

// Table header
$html = '<table style="width: 100%" border="1" cellspacing="0" cellpadding="5">';
$html .= '<thead>';
$html .= '<tr>
            <th style="width: 350px; text-align: center; font-weight: bold;">Product Name</th>
            <th style="width: 150px; text-align: center; font-weight: bold;">Total Quantity Sold</th>
            <th style="width: 100px; text-align: center; font-weight: bold;">Total Revenue</th>
          </tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Loop through the product data and create table rows
foreach ($products as $product) {
    $html .= '<tr>';
    $html .= '<td style="width: 350px;">' . htmlspecialchars($product['pName']) . '</td>';
    $html .= '<td style="width: 150px; text-align: center;">' . htmlspecialchars($product['total_quantity_sold']) . ' sold</td>';
    $html .= '<td style="width: 100px; text-align: right;">Php' . htmlspecialchars($product['total_revenue']) . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('product_sales_' . date('Y-m-d') . '.pdf', 'D');

// Close the database connection
$pdo = null;
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_clean();

require_once('TCPDF/tcpdf.php');

$pdf = new TCPDF('l', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MAIKA ORDONEZ');
$pdf->SetTitle('Point of Sales');
$pdf->SetHeaderData('', '', 'Point of Sales', '');

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', 'BI', 9);
$pdf->AddPage();

// Get the selected date range from the URL parameters
$startDate = isset($_GET['start-date']) ? $_GET['start-date'] : date('Y-m-d');
$endDate = isset($_GET['end-date']) ? $_GET['end-date'] : date('Y-m-d');

// Database connection
$dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT 
            o.id AS order_id, 
            o.user_id, 
            u.first_name, 
            u.last_name, 
            u.contact_number,
            u.address,        
            o.product_name, 
            o.quantity, 
            o.price, 
            o.image, 
            o.product_variant, 
            o.product_id, 
            o.order_date, 
            o.total_amount
        FROM finalProject.orders o
        JOIN finalProject.users u ON o.user_id = u.id
        WHERE o.order_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";

$result = mysqli_query($dbConnection, $query);

$html = '
    <h1>Point of Sales</h1>
    <p>Date Range: ' . $startDate . ' to ' . $endDate . '</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Full Name</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Product</th>
                <th>Variant</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>';

// Calculate total sales and total quantity sold
$totalSales = 0;
$totalQuantitySold = 0;

while ($row = mysqli_fetch_array($result)) {
    $html .= '
        <tr>
            <td>' . $row['order_id'] . '</td>
            <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
            <td>' . $row['address'] . '</td>
            <td>' . $row['contact_number'] . '</td>
            <td>' . $row['product_name'] . '</td>
            <td>' . $row['product_variant'] . '</td>
            <td>' . $row['quantity'] . '</td>
            <td>PHP ' . $row['price'] . '</td>
            <td>PHP ' . $row['total_amount'] . '</td>
            <td>' . $row['order_date'] . '</td>
        </tr>';

    // Sum up the total amount and total quantity sold for each order
    $totalSales += $row['total_amount'];
    $totalQuantitySold += $row['quantity'];
}

// Add a summary section to the HTML
$html .= '
        </tbody>
    </table>
    <hr>
    <p>Total Sales: PHP ' . number_format($totalSales, 2) . '</p>
    <p>Total Quantity Sold: ' . $totalQuantitySold . '</p>
';

$pdf->writeHTML($html);

$pdf->Output('Point_of_Sales.pdf', 'I');
?>
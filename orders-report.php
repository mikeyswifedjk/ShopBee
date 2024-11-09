<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ob_clean();

    require_once('TCPDF/tcpdf.php');
    // create new PDF document
    //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //page orientation p for portrait and l for lanscape
    //pdf unit mm for milimeter
    //page format sizes like A4, legal
    //tcpdf class is default kung gagamit ng header and footer need gumawa ng bagong class, use the class tapos extends si tcpdf
    $pdf = new TCPDF('l', PDF_UNIT, 'A4', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('MAIKA ORDONEZ');
    $pdf->SetTitle('Sample Generated report');
    // $pdf->SetSubject('');
    // $pdf->SetKeywords('');
    //this is optional kung gagamit ng header or footer
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    //default font
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    //margin ng page(left, top, right)
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
    //optional if isasama sila header at footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    //matic na adding ng page if ma reach yung sinet na footer margin
    $pdf->SetAutoPageBreak(TRUE, 10);
    //font ng buong page (font theme, font style, font size)
    $pdf->SetFont('helvetica', 'BI', 9);
    //eto yung pag add ng page./no of page
    $pdf->AddPage();
    //dito yung mismong code

    // Get the selected date range from the URL parameters
    $startDate = isset($_GET['start-date']) ? $_GET['start-date'] : date('Y-m-d');
    $endDate = isset($_GET['end-date']) ? $_GET['end-date'] : date('Y-m-d');

    $html = '
    <h1>Payment History Report</h1>
    <p>Date Range: ' . $startDate . ' to ' . $endDate . '</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Variant</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>';

    // Database connection
    $dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");

    if (!$dbConnection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM orders WHERE order_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
    $result = mysqli_query($dbConnection, $query);

    while ($row = mysqli_fetch_array($result)) {
        $html .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['user_id'] . '</td>
                <td>' . $row['product_id'] . '</td>
                <td>' . $row['product_name'] . '</td>
                <td>' . $row['product_variant'] . '</td>
                <td>' . $row['quantity'] . '</td>
                <td>' . $row['price'] . '</td>
                <td>' . $row['total_amount'] . '</td>
                <td>' . $row['total_amount'] . '</td>
                <td>' . $row['order_date'] . '</td>
            </tr>';
    }

    $html .= '
            </tbody>
        </table>
    ';

    // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
    // $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->writeHTML($html);

    $pdf->Output('usersreport	.pdf', 'I');
?>
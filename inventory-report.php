<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    $html = '
    <h1>Product Inventory Report</h1>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Total Quantity</th>
                <th>Total Qty On Hand </th>
            </tr>
            <tr><th></th></tr>
        </thead>
        <tbody>';

        $dbConnection = mysqli_connect("localhost:3306", "root", "", "finalProject");
        $selectProductsQuery = "SELECT * FROM product";
        $productsResult = mysqli_query($dbConnection, $selectProductsQuery);

        if (!$productsResult) {
            die("Error in SQL query: " . mysqli_error($dbConnection));
        }

        while ($product = mysqli_fetch_assoc($productsResult)) {
            $html .= '
                <tr>
                    <td>' . $product['id'] . '</td>
                    <td>' . $product['name'] . '</td>
                    <td>' . $product['category'] . '</td>
                    <td>' . $product['qty'] . '</td>
                    <td>'; // Fetch total quantity sold and calculate total on hand quantity

            // Fetch total quantity sold from orders table
            $selectOrdersQuery = "SELECT SUM(quantity) as total_sold FROM orders WHERE product_id = " . $product['id'];
            $ordersResult = mysqli_query($dbConnection, $selectOrdersQuery);

            if (!$ordersResult) {
                die("Error in SQL query: " . mysqli_error($dbConnection));
            }

            $totalSold = mysqli_fetch_assoc($ordersResult)['total_sold'];

            // Calculate total on hand quantity
            $totalOnHand = $product['qty'] - $totalSold;

            $html .= $totalOnHand . '</td>
                </tr>';
        }

        $html .= '
        </tbody>
    </table>';

    // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
    // $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->writeHTML($html);

    $pdf->Output('usersreport	.pdf', 'I');
?>
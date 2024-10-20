<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You need to login first");
}

// Fetch cart items from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT decoration_items.item_name, decoration_items.price, cart.quantity 
        FROM cart 
        JOIN decoration_items ON cart.item_id = decoration_items.id 
        WHERE cart.user_id = '$user_id'";
$result = $conn->query($sql);

$cart_items = [];
$total_price = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shantai Lawns - Invoice</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- jsPDF-AutoTable Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        .invoice {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .invoice h1 {
            color: #2c3e50;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .invoice p {
            color: #7f8c8d;
        }
        .invoice h2 {
            color: #3498db;
            font-size: 24px;
            margin-top: 30px;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .table {
            margin-top: 30px;
        }
        .table th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(52, 152, 219, 0.1);
        }
        .total-price {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .btn-download {
            background-color: #2ecc71;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .btn-download:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice">
            <h1 class="text-center">Shantai Lawns</h1>
            <p class="text-center">Sangvi Dumala, Ahmadnagar SH-10, Ahmednagar - Daund Rd, Maharashtra 414701</p>
            <h2>Estimated Invoice</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price (Rs)</th>
                        <th>Quantity</th>
                        <th>Total (Rs)</th>
                    </tr>
                </thead>
                <tbody id="invoice-items">
                    <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?= $item['item_name']; ?></td>
                        <td><?= number_format($item['price'], 2); ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3 class="text-end total-price">Total: Rs <?= number_format($total_price, 2); ?></h3>
            <div class="text-center mt-4">
                <button class="btn btn-primary btn-download" onclick="generatePDF()">Download PDF</button>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jsPDF Script to Generate PDF -->
    <script>
        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Header
            doc.setFontSize(22);
            doc.text('Shantai Lawns', 105, 20, { align: 'center' });
            doc.setFontSize(12);
            doc.text('Sangvi Dumala, Ahmadnagar SH-10, Ahmednagar - Daund Rd, Maharashtra 414701', 105, 30, { align: 'center' });

            // Invoice Title
            doc.setFontSize(18);
            doc.text('Estimated Invoice', 20, 50);

            // Define the table for cart items
            const items = <?php echo json_encode($cart_items); ?>;
            const tableData = items.map(item => [item.item_name, `Rs. ${item.price.toFixed(2)}`, item.quantity, `Rs. ${(item.price * item.quantity).toFixed(2)}`]);

            doc.autoTable({
                head: [['Item', 'Price (Rs)', 'Quantity', 'Total (Rs)']],
                body: tableData,
                startY: 60,
                theme: 'striped',
                headStyles: {
                    fillColor: [52, 152, 219],
                    textColor: 255,
                    fontStyle: 'bold',
                    halign: 'center'
                },
                styles: {
                    halign: 'left',
                    fontSize: 12
                },
                columnStyles: {
                    1: { halign: 'right' },
                    2: { halign: 'right' },
                    3: { halign: 'right' }
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                }
            });

            // Total Price
            doc.setFontSize(14);
            doc.text(`Total: Rs. <?= number_format($total_price, 2); ?>`, 190, doc.autoTable.previous.finalY + 10, { align: 'right' });

            // Save PDF
            doc.save('Invoice_Shantai_Lawns.pdf');
        }
    </script>
</body>
</html>

<?php
session_start();

if (isset($_GET["clear"])) {
  $_SESSION['cart'] = array();
  header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhopping Cart Test</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">

    <div class="container p-4 border rounded shadow-sm bg-white">
        <h1 class="text-center mb-4">Items available</h1>
        <form action="Pair11_1D_addToCart.php" method="post"> <!--carttest.php-->
            <table class="table table-bordered">
                <thead>
                    <tr><th>Item</th><th>Quantity</th></tr>
                </thead>
                <tbody>
                    <tr><td>Apples</td><td><input type="text" name="apples" class="form-control" size="2"></td></tr>
                    <tr><td>Bananas</td><td><input type="text" name="bananas" class="form-control" size="2"></td></tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary w-100">Click to add to cart</button>
        </form>
        <br>
        <?php
        // The code lists two products: apples and bananas, and provides a
        // text box to indicate the quantity of each you want to place in the shopping cart.
        // This section uses PHP code to check whether the form has already been submitted.
        // If the site visitor has submitted the form, the PHP code checks to see which (if any)
        // of the products had been selected for purchase. If either one had been selected,
        // the PHP code stores the new quantity number in the cart session cookie for that product.

        // Code for apples
        if (isset($_POST['apples'])) {
            if (is_numeric($_POST['apples'])) {
                $_SESSION['cart']['apples'] = $_POST['apples'];
            }
        }

        if (isset($_POST['apples']) && $_POST['apples'] === "Remove") {
            unset($_SESSION['cart']['apples']);
        }

        // Code for bananas
        if (isset($_POST['bananas'])) {
            if (is_numeric($_POST['bananas'])) {
                $_SESSION['cart']['bananas'] = $_POST['bananas'];
            }
        }

        if (isset($_POST['bananas']) && $_POST['bananas'] === "Remove") {
            unset($_SESSION['cart']['bananas']);
        }
        ?>
        <fieldset class="border p-3">
            <legend>Your Shopping Cart</legend>
            <?php
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
                echo "Your shopping cart is empty<br>\n";
            }
            else {
                echo "<form action=\"Pair11_1D_addToCart.php\" method=\"post\">\n";
                echo "<table class=\"table table-bordered\">\n";
                echo "<thead><tr><th>Item</th><th>Quantity</th><th></th></tr></thead>\n";
                echo "<tbody>\n";
                foreach ($_SESSION['cart'] as $key => $value) {
                    echo "<tr><td>$key</td><td>$value</td>";
                    echo "<td><input type=\"submit\" name=\"$key\" value=\"Remove\" class=\"btn btn-danger btn-sm\"></td></tr>\n";
                }
                echo "</tbody>\n";
                echo "</table>\n";
                echo "</form>\n";
            }
            ?>

            <?php
            $prices = [
                'apples' => 25.75,
                'bananas' => 15.50
            ];

            $total = 0.00;
            $itemBreakdown = [];

            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item => $qty) {
                    if (isset($prices[$item]) && is_numeric($qty) && $qty > 0) {
                        $lineTotal = $prices[$item] * $qty;
                        $itemBreakdown[$item] = [
                            'qty' => $qty,
                            'unit_price' => $prices[$item],
                            'line_total' => $lineTotal
                        ];
                        $total += $lineTotal;
                    }
                }

                //calculations
                $vatable = $total / 1.12;
                $vat = $total - $vatable;

                echo "<h3 class='text-center'>Purchase Summary</h3>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>";
                echo "<tbody>";
                foreach ($itemBreakdown as $name => $data) {
                    echo "<tr>";
                    echo "<td>" . ucfirst($name) . "</td>";
                    echo "<td>{$data['qty']}</td>";
                    echo "<td>P" . number_format($data['unit_price'], 2) . "</td>";
                    echo "<td>P" . number_format($data['line_total'], 2) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table><br>";

                echo "<strong>VATable Sales:</strong> P" . number_format($vatable, 2) . "<br>";
                echo "<strong>VAT 12%:</strong> P" . number_format($vat, 2) . "<br>";
                echo "<strong>Total Sales:</strong> P" . number_format($total, 2) . "<br><br>";

                //change
                echo '<form method="post" action="Pair11_1D_addToCart.php">';
                echo '<div class="mb-3">';
                echo '<label for="amount_paid" class="form-label"><strong>Amount Paid:</strong></label> ';
                echo '<input type="text" name="amount_paid" id="amount_paid" class="form-control"> ';
                echo '</div>';
                echo '<button type="submit" class="btn btn-primary w-100">Compute Change</button>';
                echo '</form>';

                //change alculation
                if (isset($_POST['amount_paid'])) {
                    $paid = $_POST['amount_paid'];
                    if (is_numeric($paid)) {
                        $change = $paid - $total;
                        if ($change >= 0) {
                            echo "<strong>Change:</strong> P" . number_format($change, 2) . "<br>";
                        } else {
                            echo "<span style='color:red;'>Insufficient amount. Customer still owes P" . number_format(abs($change), 2) . "</span><br>";
                        }
                    } else {
                        echo "<span style='color:red;'>Please enter a valid numeric amount.</span><br>";
                    }
                }
            }
            ?>
        </fieldset>
    </div>

    <!-- Bootstrap 5 JS (Optional, for some components like modals, dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

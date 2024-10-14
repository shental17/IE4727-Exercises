<?php
include '../utils/db_connect.php';

// Query to fetch products and their categories
$sql = "
SELECT 
      p.id AS product_id,
      p.name AS product_name, 
      p.description AS product_description, 
      c.id AS category_id,
      c.name AS category_name, 
      c.price 
FROM products p
JOIN categories c ON p.id = c.product_id
";

$result = $conn->query($sql);

// Initialize an array to hold products
$products = [];

// Fetch results and structure them into an array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Use the product_id as a key to uniquely identify each product
        $products[$row['product_id']]['product_name'] = $row['product_name'];
        $products[$row['product_id']]['description'] = $row['product_description'];
        
        // Add the category information including category_id
        $products[$row['product_id']]['categories'][] = [
            'id' => $row['category_id'],
            'name' => $row['category_name'],
            'price' => $row['price'],
        ];
    }
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the checkout here (e.g., save to database, etc.)

    // Reset input values or just process as required
    // After processing, you can redirect to the same page to reset the values
    header("Location: " . $_SERVER['PHP_SELF'] . "?checkout=success");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu</title>
    <link rel="stylesheet" href="../javajam.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header></header>
    <div id="wrapper">
        <div id="leftcolumn">
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="menu.html" class="active">Menu</a></li>
                    <li><a href="music.html">Music</a></li>
                    <li><a href="jobs.html">Jobs</a></li>
                </ul>
            </nav>
        </div>
        <div id="rightcolumn">
            <div class="content">
                <h2>Coffee at JavaJam</h2>
                <form action="" method="POST" id="checkoutForm">
                    <table id="menu" class="table table-bordered">
                        <tbody>
                            <?php
                            $colorClass = 'odd-row'; // Start with odd row

                            foreach ($products as $product_id => $product) {
                                $product_name = htmlspecialchars($product['product_name']);
                                $description = htmlspecialchars($product['description']);
                                $categories = $product['categories'];
                                $rowCount = count($categories);

                                // Output the first row for the product with description
                                echo "<tr class='$colorClass'>"; // Apply the current color class
                                echo "<td rowspan='$rowCount'><strong>" . $product_name . "</strong></td>";
                                echo "<td rowspan='$rowCount'>$description</td>";

                                // Output the first category's details
                                $firstCategory = $categories[0];
                                $firstCategoryId = $firstCategory['id'];
                                $firstCategoryName = htmlspecialchars($firstCategory['name']);
                                $firstPrice = number_format($firstCategory['price'], 2);

                                echo "<td><strong>$firstCategoryName</strong></td>";
                                echo "<td><strong>$$firstPrice</strong></td>";
                                echo "<td><input type='number' name='$firstCategoryName' value='0' min='0' class='quantity' data-price='{$firstCategory['price']}' data-product-id='$product_id' data-category-id='$firstCategoryId' oninput='updateProductSubtotal($product_id)' /></td>";
                                
                                // Add a subtotal cell for the product in the first row
                                echo "<td rowspan='$rowCount' class='product-subtotal'><input type='text' class='subtotal' value='$0.00' data-product-id='$product_id' readonly /></td>";
                                echo "</tr>";

                                // Output the remaining categories
                                for ($i = 1; $i < $rowCount; $i++) {
                                    echo "<tr class='$colorClass'>"; // Start a new row for additional categories
                                    $category = $categories[$i];
                                    $categoryId = $category['id'];
                                    $categoryName = htmlspecialchars($category['name']);
                                    $price = number_format($category['price'], 2);

                                    echo "<td><strong>$categoryName</strong></td>";
                                    echo "<td><strong>$$price</strong></td>";
                                    echo "<td><input type='number' name='$categoryName' value='0' min='0' class='quantity' data-price='{$category['price']}' data-product-id='$product_id' data-category-id='$categoryId' oninput='updateProductSubtotal($product_id)' /></td>";
                                    echo "</tr>";
                                }

                                // Alternate the color class for the next product
                                $colorClass = ($colorClass === 'odd-row') ? 'even-row' : 'odd-row';
                            }
                            ?>

                            <tr>
                                <td colspan='4'></td>
                                <td><span>Total Price</span></td>
                                <td>
                                    <input type="text" id="totalPrice" value="$0.00" readonly />
                                </td>
                            </tr>
                            <tr>
                                <td colspan='5'></td>
                                <td>
                                    <button type="submit" class="btn btn-success" onclick="checkout()">Checkout</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <small>
            <i>
                Copyright &copy; 2014 JavaJam Coffee House
                <br />
                
            </i>
        </small>
    </footer>
    <script src="../js/menuUpdate.js"></script>
     <!-- Add Bootstrap JS and dependencies -->
     <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Display alert if checkout was successful
        <?php if (isset($_GET['checkout']) && $_GET['checkout'] === 'success') : ?>
            alert('Order has been submitted successfully!');
            // Reset input values to 0
            document.querySelectorAll('.quantity').forEach(input => input.value = 0);
            document.getElementById('totalPrice').value = '$0.00';
        <?php endif; ?>
    </script>
</body>
</html>

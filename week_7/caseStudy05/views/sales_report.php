<?php
include '../utils/db_connect.php';

// Set the default timezone to Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

// Get the current date in 'Y-m-d' format
$currentDate = date('Y-m-d');

// Initialize variables
$viewType = isset($_POST['viewType']) ? $_POST['viewType'] : 'allProductsAndCategories';
$selectedProductId = isset($_POST['product_id']) ? $_POST['product_id'] : null;

// Fetch all products for dropdown
$productQuery = "SELECT id, name FROM products";
$products = $conn->query($productQuery);

// Prepare the main query based on the selected view type
$query = "";
$mostSoldQuery = "";  // Initialize the query
$mostSalesQuery = ""; // Initialize the query

if ($viewType === 'allProductsAndCategories') {
    $query = "
        SELECT 
            products.id AS product_id,
            products.name AS product_name,
            categories.id AS category_id,
            categories.name AS category_name,
            IFNULL(SUM(sales.quantity), 0) AS quantity_sold,
            IFNULL(SUM(categories.price * sales.quantity), 0) AS subtotal
        FROM 
            products
        LEFT JOIN 
            categories ON categories.product_id = products.id
        LEFT JOIN 
            sales ON sales.category_id = categories.id AND sales.sale_date = '$currentDate'
        GROUP BY 
            products.id, categories.id
        ORDER BY 
            products.name, categories.name;
    ";

    // Most quantity sold category query
    $mostSoldQuery = "
        SELECT 
            categories.id AS most_sold_id,
            CONCAT(products.name, ' ', categories.name) AS most_sold_name
        FROM 
            products
        LEFT JOIN 
            categories ON categories.product_id = products.id
        LEFT JOIN 
            sales ON sales.category_id = categories.id AND sales.sale_date = '$currentDate'
        WHERE 
            sales.quantity = (
                SELECT MAX(subquery.quantity)
                FROM (
                    SELECT 
                        category_id, 
                        quantity 
                    FROM 
                        sales
                    WHERE
                        sales.sale_date = '$currentDate'
                    GROUP BY 
                        category_id
                ) AS subquery
            )
            AND sales.sale_date = '$currentDate' -- Ensure this filter condition refers to the correct table
        ORDER BY 
            categories.id;

    ";

    // Most sales query
    $mostSalesQuery = "
        SELECT 
            c.id AS most_sales_id,
            CONCAT(p.name, ' ', c.name) AS most_sales_name
        FROM 
        	products p
        LEFT JOIN 
            categories c on p.id=c.product_id
        LEFT JOIN 
            sales s ON c.id = s.category_id AND s.sale_date = '$currentDate' 
        GROUP BY 
            c.id
        HAVING 
            IFNULL(SUM(s.quantity * c.price), 0) = (
                SELECT MAX(most_sales) 
                FROM (
                    SELECT 
                        IFNULL(SUM(s.quantity * c.price), 0) AS most_sales
                    FROM 
                        categories c
                    LEFT JOIN 
                        sales s ON c.id = s.category_id AND s.sale_date = '$currentDate'
                    GROUP BY 
                        c.id
                ) AS sales_summary
            )
        ORDER BY 
            c.id;
    ";

} elseif ($viewType === 'allProductsOnly') {
    $query = "
        SELECT 
            products.id AS product_id,
            products.name AS product_name,
            IFNULL(SUM(sales.quantity), 0) AS quantity_sold,
            IFNULL(SUM(categories.price * sales.quantity), 0) AS subtotal
        FROM 
            products
        LEFT JOIN 
            categories ON categories.product_id = products.id
        LEFT JOIN 
            sales ON sales.category_id = categories.id AND sales.sale_date = '$currentDate'
        GROUP BY 
            products.id
        ORDER BY 
            products.name;
    ";

    // Most quantity sold product query
    $mostSoldQuery = "
        SELECT 
            p.id AS most_sold_id,
            p.name AS most_sold_name
        FROM 
            products p
        INNER JOIN 
            categories c ON p.id = c.product_id
        INNER JOIN 
            sales s ON c.id = s.category_id AND s.sale_date = '$currentDate'
        GROUP BY 
            p.id
        HAVING 
            SUM(s.quantity) = (
                SELECT MAX(total_quantity)
                FROM (
                    SELECT  
                        SUM(s.quantity) AS total_quantity
                    FROM 
                        products p
                    INNER JOIN 
                        categories c ON p.id = c.product_id
                    INNER JOIN 
                        sales s ON c.id = s.category_id AND s.sale_date = '$currentDate'
                    GROUP BY 
                        p.id
                ) AS subquery
            );

    ";

    // Most product dollar sales query
    $mostSalesQuery = "
        SELECT 
            p.id AS most_sales_id,
            p.name AS most_sales_name
        FROM 
            products p
        LEFT JOIN 
            categories c ON p.id = c.product_id
        LEFT JOIN 
            sales s ON c.id = s.category_id AND s.sale_date = '$currentDate'
        GROUP BY    
            p.id
        HAVING 
            IFNULL(SUM(c.price * s.quantity), 0) = (
                SELECT MAX(total_sales)
                FROM (
                    SELECT 
                        IFNULL(SUM(c.price * s.quantity), 0) AS total_sales
                    FROM 
                        products p
                    LEFT JOIN 
                        categories c ON p.id = c.product_id
                    LEFT JOIN 
                        sales s ON c.id = s.category_id AND s.sale_date = '$currentDate'
                    GROUP BY 
                        p.id
                ) AS subquery
            );
    ";

} elseif ($viewType === 'specificProductCategories' && $selectedProductId) {
    $query = "
        SELECT 
            categories.id AS category_id,
            categories.name AS category_name,
            IFNULL(SUM(sales.quantity), 0) AS quantity_sold,
            IFNULL(SUM(categories.price * sales.quantity), 0) AS subtotal
        FROM 
            categories
        LEFT JOIN 
            sales ON sales.category_id = categories.id AND sales.sale_date = '$currentDate'
        WHERE 
            categories.product_id = '$selectedProductId'
        GROUP BY 
            categories.id
        ORDER BY 
            categories.name;
    ";

    // Most quantity sold category for chosen product query 
    $mostSoldQuery = "
        SELECT 
            categories.id AS most_sold_id, 
            categories.name AS most_sold_name
        FROM 
            categories
            LEFT JOIN 
            sales ON sales.category_id = categories.id
        WHERE 
            sales.quantity = (
                SELECT MAX(quantity)
                FROM (
                    SELECT 
                        sales.quantity AS quantity
                    FROM 
                        categories
                        LEFT JOIN
                        sales ON sales.category_id = categories.id AND sales.sale_date = '$currentDate'
                    WHERE 
                        categories.product_id = '$selectedProductId'
                    GROUP BY 
                        category_id
                ) AS subquery
            )
            AND 
                sales.sale_date = '$currentDate'
            AND
                categories.product_id = '$selectedProductId'
        ORDER BY 
            category_id;
    ";

    $mostSalesQuery = "
        SELECT 
            c.id AS most_sales_id,
            c.name AS most_sales_name
        FROM 
            categories c
        LEFT JOIN 
            sales s ON c.id = s.category_id AND s.sale_date = '$currentDate'  -- Moved the date filter to the JOIN condition
        WHERE 
            c.product_id = '$selectedProductId'
        GROUP BY 
            c.id
        HAVING 
            IFNULL(SUM(s.quantity * c.price), 0) = (
                SELECT MAX(most_sales) 
                FROM (
                    SELECT 
                        IFNULL(SUM(s.quantity * c.price), 0) AS most_sales
                    FROM 
                        categories c
                    LEFT JOIN 
                        sales s ON c.id = s.category_id AND s.sale_date = '$currentDate'
                    WHERE 
                        c.product_id = '$selectedProductId'
                    GROUP BY 
                        c.id
                ) AS sales_summary
            )
        ORDER BY 
            c.id;

    ";
}

// Execute the main query
$result = $conn->query($query);
if (!$result) {
    die("Query Failed: " . $conn->error);
}

// Execute the query for most sold categories
$mostSoldResult = $conn->query($mostSoldQuery);
if (!$mostSoldResult) {
    die("Most Sold Query Failed: " . $conn->error);
}

$mostSold = []; // Initialize
if ($mostSoldResult->num_rows > 0) {
    while ($row = $mostSoldResult->fetch_assoc()) {
        $mostSold[] = [
            'id' => $row['most_sold_id'], // Store each id in an array
            'name' => $row['most_sold_name'] // Store each name in an array
        ];
        
    }
}
// Prepare a message variable to store the output
$mostSoldMessage = "";

// Now handle the message output for most sold
$mostSoldNames = array_column($mostSold, 'name');

if (count($mostSoldNames) === 1) {
    $mostSoldMessage = $mostSoldNames[0] . " has the most quantity sold."; // Store message in a string variable
} else {
    $allNames = implode(", ", $mostSoldNames);
    $mostSoldMessage = $allNames . " have the most quantities sold."; // Store message in a string variable
}


// Execute the query for most sales
$mostSalesResult = $conn->query($mostSalesQuery);
if (!$mostSalesResult) {
    die("Most Sales Query Failed: " . $conn->error);
}

$mostSales = []; // Initialize the array
if ($mostSalesResult->num_rows > 0) {
    while ($row = $mostSalesResult->fetch_assoc()) {
        // Store each entry as an associative array with both variables
        $mostSales[] = [
            'id' => $row['most_sales_id'], // Change to your actual variable name
            'name' => $row['most_sales_name'] // Change to your second variable name
        ];
    }
}

$mostSalesMessage="";

// Now handle the message output
$mostSalesNames = array_column($mostSales, 'name');

if (count($mostSalesNames) === 1) {
    $mostSalesMessage = $mostSalesNames[0] . " has the most sales."; // Store message in a string variable
} else {
    $allNames = implode(", ", $mostSalesNames);
    $mostSalesMessage = $allNames . " have the most sales."; // Store message in a string variable
}


$productName = "";
if ($viewType === 'specificProductCategories' && $selectedProductId) {
    // Query to get the product name
    $productQuery = "SELECT name FROM products WHERE id = '$selectedProductId'";
    $productResult = $conn->query($productQuery);
    if ($productResult && $productResult->num_rows > 0) {
        $productRow = $productResult->fetch_assoc();
        $productName = $productRow['name']; // Get the product name
    }
}
// Initialize variables for total sales
$totalSales = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daily Sales Report</title>
    <link rel="stylesheet" href="../javajam.css" />
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .list-group-item{
            background-color: transparent;
            border: transparent;
        }
        .btn-custom-primary {
            color: #3e2723 !important; /* Custom text color */
            border-color: #3e2723 !important; /* Custom border color */
        }

        .btn-custom-primary:hover {
            background-color: #d7ccc8; /* Light background on hover */
        }

        .btn-custom-primary.active {
            background-color: #3e2723 !important; /* Active background color */
            border-color: #3e2723 !important; /* Active border color */
            color:white !important;
        }

        .form-control{
            width:"20%";
        }
    </style>

    <script>
        function toggleProductDropdown() {
            const viewType = document.querySelector('input[name="viewType"]:checked').value;
            const dropdown = document.getElementById('productDropdown');
            dropdown.style.display = (viewType === 'specificProductCategories') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <header></header>
    <div id="wrapper">
        <div id="leftcolumn">
            <nav>
                <ul class="list-group">
                    <li class="list-group-item"><a href="admin.php">Admin Management</a></li>
                    <li class="list-group-item"><a href="sales_report.php"  class="active">Sales Report</a></li>
                </ul>
            </nav>
        </div>
        <div id="rightcolumn">
            <div class="content">
                <h1>Daily Sales Report for <?php echo $currentDate; ?></h1>
                <form method="POST" class="form-group">
                <div>
                    <label class="btn btn-custom-primary <?php echo $viewType === 'allProductsAndCategories' ? 'active' : ''; ?>">
                        <input type="radio" name="viewType" value="allProductsAndCategories" onchange="toggleProductDropdown()" <?php echo $viewType === 'allProductsAndCategories' ? 'checked' : ''; ?>> Products and Categories
                    </label>
                    <label class="btn btn-custom-primary <?php echo $viewType === 'allProductsOnly' ? 'active' : ''; ?>">
                        <input type="radio" name="viewType" value="allProductsOnly" onchange="toggleProductDropdown()" <?php echo $viewType === 'allProductsOnly' ? 'checked' : ''; ?>> Products
                    </label>
                    <label class="btn btn-custom-primary <?php echo $viewType === 'specificProductCategories' ? 'active' : ''; ?>">
                        <input type="radio" name="viewType" value="specificProductCategories" onchange="toggleProductDropdown()" <?php echo $viewType === 'specificProductCategories' ? 'checked' : ''; ?>> Categories
                    </label>
                </div>
                    <div id="productDropdown" style="display: <?php echo $viewType === 'specificProductCategories' ? 'block' : 'none'; ?>;">
                        <label for="product_id">Select Product Name:</label>
                        <select name="product_id" id="product_id" class="form-control">
                            <option value="">Select a product</option>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo $selectedProductId == $row['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <input type="submit" value="Generate Report" class="btn btn-custom-primary active mt-2">
                </form>

                <?php if ($viewType === 'allProductsAndCategories'): ?>
                    <h2>Overall Sales for All Products and Categories</h2>
                <?php elseif ($viewType === 'allProductsOnly'): ?>
                    <h2>Overall Sales for All Products</h2>
                <?php elseif ($viewType === 'specificProductCategories' && $selectedProductId): ?>
                    <h2>Overall Sales for All Categories of <?php echo htmlspecialchars($productName); ?></h2>
                <?php endif; ?>

                <table id="menu" class="table table-striped">
                    <thead>
                        <tr>
                            <?php if ($viewType === 'allProductsAndCategories'): ?>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Category Quantity Sold</th>
                                <th>Subtotal for Category</th>
                                <th>Most Popular</th>
                                <th>Most Sales</th>
                            <?php elseif ($viewType === 'allProductsOnly'): ?>
                                <th>Product</th>
                                <th>Product Quantity Sold</th>
                                <th>Subtotal</th>
                                <th>Most Popular</th>
                                <th>Most Sales</th>
                            <?php elseif ($viewType === 'specificProductCategories'): ?>
                                <th>Category</th>
                                <th>Category Quantity Sold</th>
                                <th>Subtotal</th>
                                <th>Most Popular</th>
                                <th>Most Sales</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                if ($viewType === 'allProductsAndCategories') {
                                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                    echo "<td>{$row['quantity_sold']}</td>";
                                    echo "<td>$" . number_format($row['subtotal'], 2) . "</td>";
                                    echo "<td>" . (in_array($row['category_id'], array_column($mostSold, 'id')) ? "Most Quantity Sold" : "") . "</td>";
                                    echo "<td>" . (in_array($row['category_id'], array_column($mostSales, 'id')) ? "Highest Sales Amount" : "") . "</td>";                           
                                } elseif ($viewType === 'allProductsOnly') {
                                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                    echo "<td>{$row['quantity_sold']}</td>";
                                    echo "<td>$" . number_format($row['subtotal'], 2) . "</td>";
                                    echo "<td>" . (in_array($row['product_id'], array_column($mostSold, 'id')) ? "Most Quantity Sold" : "") . "</td>";
                                    echo "<td>" . (in_array($row['product_id'], array_column($mostSales, 'id')) ? "Highest Sales Amount" : "") . "</td>";

                                } elseif ($viewType === 'specificProductCategories') {
                                    echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                    echo "<td>{$row['quantity_sold']}</td>";
                                    echo "<td>$" . number_format($row['subtotal'], 2) . "</td>";
                                    echo "<td>" . (in_array($row['category_id'], array_column($mostSold, 'id')) ? "Most Quantity Sold" : "") . "</td>";
                                    echo "<td>" . (in_array($row['category_id'], array_column($mostSales, 'id')) ? "Highest Sales Amount" : "") . "</td>";
                                }
                                echo "</tr>";

                                // Update total sales
                                $totalSales += $row['subtotal'];
                            }

                            echo "<tr>";
                            if ($viewType === 'allProductsAndCategories') {
                                echo "<td colspan='3'><strong>Total Sales</strong></td>"; // Fixed: use single quotes around colspan
                                echo "<td><strong>$" . number_format($totalSales, 2) . "</strong></td>";                           
                            } else {
                                echo "<td colspan='2'><strong>Total Sales</strong></td>"; // Fixed: use single quotes around colspan
                                echo "<td><strong>$" . number_format($totalSales, 2) . "</strong></td>"; 
                            }
                            echo "<td colspan='2'></td></tr>"; 
                            if ($viewType === 'allProductsAndCategories') {
                                echo "<tr><td colspan='6'>" . $mostSoldMessage . "</td></tr>";  
                                echo "<tr><td colspan='6'>" . $mostSalesMessage . "</td></tr>";                      
                            } else {
                                echo "<tr><td colspan='5'>" . $mostSoldMessage . "</td></tr>";  
                                echo "<tr><td colspan='5'>" . $mostSalesMessage . "</td></tr>"; 
                            }
                    
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

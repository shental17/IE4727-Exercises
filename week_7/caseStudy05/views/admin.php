<?php
include '../utils/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - JavaJam</title>
    <link rel="stylesheet" href="../javajam.css">
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
</head>
<body>
    <header></header>
    <div id="wrapper">
      <div id="leftcolumn">
            <nav>
                <ul class="list-group">
                    <li class="list-group-item"><a href="admin.php"   class="active">Admin Management</a></li>
                    <li class="list-group-item"><a href="sales_report.php">Sales Report</a></li>
                </ul>
            </nav>
      </div>
      <div id="rightcolumn">
        <div class="content">
            <main>
                <h1>Admin Management</h1>
                <!-- Products Table -->
                <h2>Products</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM products");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['description']) . "</td>
                                    <td>
                                        <button class='btn btn-warning btn-sm' onclick=\"openEditProductModal({$row['id']}, '{$row['name']}', '{$row['description']}')\">Edit</button>
                                        <button class='btn btn-danger btn-sm' onclick=\"deleteItem('deleteProduct', {$row['id']})\">Delete</button>
                                    </td>
                                </tr>";
                            }
                            ?>
                            <tr>
                                <td colspan="2"></td>
                                <td><button class="btn btn-success" onclick="openAddProductModal()">Add New Product</button></td>
                            </tr>
                        </tbody>
                    </table>
                    
                <!-- Categories Table -->
                <h2>Categories</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("
                            SELECT categories.id, categories.name, categories.price, categories.product_id, products.name AS product_name 
                            FROM categories 
                            JOIN products ON categories.product_id = products.id
                        ");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['product_name']) . "</td>
                                <td>" . number_format($row['price'], 2) . "</td>
                                <td>
                                    <button class='btn btn-warning btn-sm' onclick=\"openEditCategoryModal({$row['id']}, '{$row['name']}', {$row['price']}, {$row['product_id']})\">Edit</button>
                                    <button class='btn btn-danger btn-sm' onclick=\"deleteItem('deleteCategory', {$row['id']})\">Delete</button>
                                </td>
                            </tr>";
                        }
                        ?>
                        <tr>
                            <td colspan="3"></td>
                            <td><button class="btn btn-success" onclick="openAddCategoryModal()">Add New Category</button></td>
                        </tr>
                    </tbody>
                </table>
                
                
            </main>
        </div>
      </div>
    </div>
    <?php include '../commons/modals.php'; ?>
    <script src="../js/admin_management.js"></script>
</body>
</html>

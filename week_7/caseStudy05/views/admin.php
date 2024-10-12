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
            <main>
                <h1>Admin Management</h1>
                <!-- Products Table -->
                <h2>Products</h2>
                <table border="1">
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
                                <td>{$row['name']}</td>
                                <td>{$row['description']}</td>
                                <td>
                                    <button onclick=\"openEditProductModal({$row['id']}, '{$row['name']}', '{$row['description']}')\">Edit</button>
                                    <button onclick=\"deleteItem('deleteProduct', {$row['id']})\">Delete</button>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button onclick="openAddProductModal()">Add New Product</button>

                <!-- Categories Table -->
                <h2>Categories</h2>
                <table border="1">
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
                                <td>{$row['name']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['price']}</td>
                                <td>
                                    <button onclick=\"openEditCategoryModal({$row['id']}, '{$row['name']}', {$row['price']}, {$row['product_id']})\">Edit</button>
                                    <button onclick=\"deleteItem('deleteCategory', {$row['id']})\">Delete</button>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button onclick="openAddCategoryModal()">Add New Category</button>
            </main>
        </div>
      </div>
    </div>
    <?php include '../commons/modals.php'; ?>
    <script src="../js/script.js"></script>
</body>
</html>

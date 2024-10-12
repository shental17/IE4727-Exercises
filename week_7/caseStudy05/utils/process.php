<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'addProduct') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $stmt = $conn->prepare("INSERT INTO products (name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $description);
            $stmt->execute();
            $stmt->close();
        }

        if ($action == 'editProduct') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $description, $id);
            $stmt->execute();
            $stmt->close();
        }

        if ($action == 'addCategory') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $product_id = $_POST['product_id'];
            $stmt = $conn->prepare("INSERT INTO categories (name, price, product_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sdi", $name, $price, $product_id);
            $stmt->execute();
            $stmt->close();
        }

        if ($action == 'editCategory') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $product_id = $_POST['product_id'];
            $stmt = $conn->prepare("UPDATE categories SET name = ?, price = ?, product_id = ? WHERE id = ?");
            $stmt->bind_param("sdii", $name, $price, $product_id, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}
$conn->close();
?>

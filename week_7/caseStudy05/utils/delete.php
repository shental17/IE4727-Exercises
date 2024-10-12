<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = $_POST['id'];

        if ($action == 'deleteProduct') {
            //Delete the product
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            //Delete the categories that are associated with the deleted product
            $stmt2 = $conn->prepare("DELETE FROM categories WHERE product_id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $stmt2->close();
        }

        if ($action == 'deleteCategory') {
            //Delete the category
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}
$conn->close();
?>

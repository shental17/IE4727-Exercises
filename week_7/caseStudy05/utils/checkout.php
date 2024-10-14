<?php
include './db_connect.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "javajam_db";

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set the default timezone to Malaysia
    date_default_timezone_set('Asia/Kuala_Lumpur');

    // Get the current date in 'Y-m-d' format
    $currentDate = date('Y-m-d');

    // Check if the required fields are set
    if (isset($_POST['category_id']) && isset($_POST['quantity'])) {
        // Loop through each category_id and quantity
        foreach ($_POST['category_id'] as $index => $category_id) {
            $quantity = (int)$_POST['quantity'][$index]; // Get the corresponding quantity

            // Only proceed if the quantity is greater than 0
            if ($quantity > 0) {
                // Check if the record exists
                $stmt = $pdo->prepare("SELECT * FROM sales WHERE category_id = :category_id AND sale_date = :sale_date");
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':sale_date', $currentDate);
                $stmt->execute();

                // Check if the record exists
                if ($stmt->rowCount() > 0) {
                    // Update the quantity if the record exists
                    $existingSale = $stmt->fetch(PDO::FETCH_ASSOC);
                    $newQuantity = $existingSale['quantity'] + $quantity; // Add to the existing quantity

                    // Update the record
                    $updateStmt = $pdo->prepare("UPDATE sales SET quantity = :quantity WHERE category_id = :category_id AND sale_date = :sale_date");
                    $updateStmt->bindParam(':quantity', $newQuantity);
                    $updateStmt->bindParam(':category_id', $category_id);
                    $updateStmt->bindParam(':sale_date', $currentDate);
                    $updateStmt->execute();
                } else {
                    // Insert new record if it does not exist
                    $insertStmt = $pdo->prepare("INSERT INTO sales (category_id, quantity, sale_date) VALUES (:category_id, :quantity, :sale_date)");
                    $insertStmt->bindParam(':category_id', $category_id);
                    $insertStmt->bindParam(':quantity', $quantity);
                    $insertStmt->bindParam(':sale_date', $currentDate);
                    $insertStmt->execute();
                }
            }
        }

        // Send success response
        echo json_encode(['status' => 'success', 'message' => 'Quantities submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No quantities to submit.']);
    }
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

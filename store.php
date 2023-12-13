<?php
include("./connect.php");

$mysqli = new mysqli($hostname, $username, $password, $database);

// Get the selected items from the AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedItems = $_POST['items'];

    // Insert each selected item into the database
    foreach ($selectedItems as $item) {
        $itemName = $conn->real_escape_string($item['name']);
        $itemPrice = floatval($item['price']);

        $sql = "INSERT INTO selected_items (item_name, item_price) VALUES ('$itemName', $itemPrice)";
        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    echo "Selection saved successfully!";
}
$mysqli->close();
?>

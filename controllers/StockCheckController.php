<?php
include('../config/database.php');

// Fetch medicines with low or zero stock
$query = "SELECT m.id, m.name, m.brand, s.stock 
          FROM medicines m
          LEFT JOIN medicine_stocks s ON m.id = s.medicine_id
          WHERE s.stock <= 5"; // Change this threshold if needed

$result = mysqli_query($conn, $query);

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['stock'] == 0) {
        $notifications[] = [
            "message" => "Medicine <strong>" . $row['name'] . " (" . $row['brand'] . ")</strong> is <span class='text-danger'>OUT OF STOCK</span>!",
            "type" => "danger"
        ];
    } else {
        $notifications[] = [
            "message" => "Medicine <strong>" . $row['name'] . " (" . $row['brand'] . ")</strong> is running low (<span class='text-warning'>" . $row['stock'] . " left</span>)!",
            "type" => "warning"
        ];
    }
}

echo json_encode(["notifications" => $notifications]);
?>

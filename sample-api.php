<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include('config/database.php');

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Fetch all medicines
$sql = "SELECT * FROM medicines";
$result = $conn->query($sql);

$medicines = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row;
    }
}

echo json_encode(["data" => $medicines]);

// Close connection
$conn->close();

?>

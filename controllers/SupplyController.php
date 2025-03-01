<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'fetch_supplies') {
    fetchSupplies();
} elseif ($action == 'add_supply') {
    addSupply();
}

function fetchSupplies() {
    global $conn;

    $query = "SELECT * FROM medical_supplies ORDER BY id DESC";
    $result = $conn->query($query);
    
    $supplies = [];
    while ($row = $result->fetch_assoc()) {
        $supplies[] = $row;
    }

    echo json_encode(['success' => true, 'supplies' => $supplies]);
}

function addSupply() {
    global $conn;

    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $supplier = $_POST['supplier'];

    $stmt = $conn->prepare("INSERT INTO medical_supplies (name, stock, supplier) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $stock, $supplier);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Supply added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding supply']);
    }

    $stmt->close();
}
?>

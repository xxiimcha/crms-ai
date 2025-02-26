<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'fetch_medicines':
        fetchMedicines();
        break;
    case 'add_medicine':
        addMedicine();
        break;
    case 'update_stock':
        updateStock();
        break;
    case 'delete_medicine':
        deleteMedicine();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

// Fetch Medicines with Stock Information
function fetchMedicines() {
    global $conn;
    $response = ["success" => false, "medicines" => []];

    $query = "SELECT m.id, m.name, m.category, m.expiry_date, 
                     COALESCE(SUM(s.quantity), 0) AS stock 
              FROM medicines m
              LEFT JOIN medicine_stocks s ON m.id = s.medicine_id
              GROUP BY m.id
              ORDER BY m.name ASC";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response["medicines"][] = $row;
        }
        $response["success"] = true;
    } else {
        $response["message"] = "Failed to fetch medicines: " . mysqli_error($conn);
    }

    echo json_encode($response);
}

// Add New Medicine
function addMedicine() {
    global $conn;

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = (int)$_POST['stock'];
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);

    if (empty($name) || empty($category) || empty($expiry_date)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Insert into `medicines` table
    $query = "INSERT INTO medicines (name, category, expiry_date) 
              VALUES ('$name', '$category', '$expiry_date')";

    if (mysqli_query($conn, $query)) {
        $medicine_id = mysqli_insert_id($conn);

        // Add Initial Stock Entry
        if ($stock > 0) {
            $stockQuery = "INSERT INTO medicine_stocks (medicine_id, quantity, transaction_type) 
                           VALUES ('$medicine_id', '$stock', 'IN')";
            mysqli_query($conn, $stockQuery);
        }

        echo json_encode(["success" => true, "message" => "Medicine added successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add medicine: " . mysqli_error($conn)]);
    }
}

// Update Stock (Add or Reduce Stock)
function updateStock() {
    global $conn;

    $medicine_id = (int)$_POST['medicine_id'];
    $quantity = (int)$_POST['quantity'];
    $transaction_type = $_POST['transaction_type']; // 'IN' for adding stock, 'OUT' for dispensing

    if ($medicine_id <= 0 || $quantity <= 0 || !in_array($transaction_type, ['IN', 'OUT'])) {
        echo json_encode(["success" => false, "message" => "Invalid stock update parameters."]);
        exit;
    }

    // Insert Stock Transaction
    $query = "INSERT INTO medicine_stocks (medicine_id, quantity, transaction_type) 
              VALUES ('$medicine_id', '$quantity', '$transaction_type')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Stock updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update stock: " . mysqli_error($conn)]);
    }
}

// Delete Medicine (Also deletes stock history)
function deleteMedicine() {
    global $conn;

    $id = (int)$_POST['id'];

    if ($id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid medicine ID."]);
        exit;
    }

    // Delete Medicine & Stock History
    $query = "DELETE FROM medicines WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Medicine deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete medicine: " . mysqli_error($conn)]);
    }
}
?>

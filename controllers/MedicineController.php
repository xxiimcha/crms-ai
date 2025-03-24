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

// Fetch Medicines with Stock and Expiry Information
function fetchMedicines() {
    global $conn;
    $response = ["success" => false, "medicines" => []];

    $query = "SELECT * FROM medicines";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(["success" => false, "message" => "Database Error: " . mysqli_error($conn)]);
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $response["medicines"][] = $row;
    }

    if (empty($response["medicines"])) {
        $response["message"] = "No medicines found.";
    } else {
        $response["success"] = true;
    }

    echo json_encode($response);
}

function addMedicine() {
    global $conn;

    // Retrieve and sanitize inputs
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $brand = isset($_POST['brand']) ? mysqli_real_escape_string($conn, $_POST['brand']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $dosage = isset($_POST['dosage']) ? mysqli_real_escape_string($conn, $_POST['dosage']) : '';
    $stock = isset($_POST['stock']) ? (int) $_POST['stock'] : 0; // Ensure it's an integer
    $expiry_date = isset($_POST['expiry_date']) ? mysqli_real_escape_string($conn, $_POST['expiry_date']) : '';

    // Validate required fields
    if (empty($name) || empty($brand) || empty($description) || empty($dosage) || empty($expiry_date)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Insert into `medicines` table
    $query = "INSERT INTO medicines (name, brand, description, dosage) 
              VALUES ('$name', '$brand', '$description', '$dosage')";

    if (mysqli_query($conn, $query)) {
        $medicine_id = mysqli_insert_id($conn); // Get newly inserted medicine ID

        // Add Initial Stock Entry (Only if stock is greater than 0)
        if ($stock > 0) {
            $stockQuery = "INSERT INTO medicine_stocks (medicine_id, stock, expiry_date) 
                           VALUES ('$medicine_id', '$stock', '$expiry_date')";

            if (!mysqli_query($conn, $stockQuery)) {
                echo json_encode(["success" => false, "message" => "Stock insert failed: " . mysqli_error($conn)]);
                exit;
            }
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
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);
    $transaction_type = $_POST['transaction_type']; // 'IN' for adding stock, 'OUT' for dispensing

    if ($medicine_id <= 0 || $quantity <= 0 || !in_array($transaction_type, ['IN', 'OUT'])) {
        echo json_encode(["success" => false, "message" => "Invalid stock update parameters."]);
        exit;
    }

    // If dispensing stock, check if enough is available
    if ($transaction_type === 'OUT') {
        $checkStock = "SELECT COALESCE(SUM(quantity), 0) AS total_stock FROM medicine_stocks WHERE medicine_id = '$medicine_id'";
        $stockResult = mysqli_query($conn, $checkStock);
        $stockRow = mysqli_fetch_assoc($stockResult);

        if ($stockRow['total_stock'] < $quantity) {
            echo json_encode(["success" => false, "message" => "Not enough stock available."]);
            exit;
        }
    }

    // Insert Stock Transaction
    $query = "INSERT INTO medicine_stocks (medicine_id, quantity, expiry_date, transaction_type) 
              VALUES ('$medicine_id', '$quantity', '$expiry_date', '$transaction_type')";

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

    // Delete Stock First
    $deleteStockQuery = "DELETE FROM medicine_stocks WHERE medicine_id = '$id'";
    mysqli_query($conn, $deleteStockQuery);

    // Delete Medicine
    $query = "DELETE FROM medicines WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Medicine and stock history deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete medicine: " . mysqli_error($conn)]);
    }
}
?>

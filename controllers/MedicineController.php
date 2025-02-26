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
    case 'delete_medicine':
        deleteMedicine();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

// Fetch Medicines
function fetchMedicines() {
    global $conn;
    $response = ["success" => false, "medicines" => []];

    $query = "SELECT * FROM medicines ORDER BY name ASC";
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

// Add Medicine
function addMedicine() {
    global $conn;

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = (int)$_POST['stock'];
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);

    if (empty($name) || empty($category) || empty($stock) || empty($expiry_date)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $query = "INSERT INTO medicines (name, category, stock, expiry_date) 
              VALUES ('$name', '$category', '$stock', '$expiry_date')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Medicine added successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add medicine: " . mysqli_error($conn)]);
    }
}

// Delete Medicine
function deleteMedicine() {
    global $conn;

    $id = (int)$_POST['id'];

    if ($id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid medicine ID."]);
        exit;
    }

    $query = "DELETE FROM medicines WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Medicine deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete medicine: " . mysqli_error($conn)]);
    }
}
?>

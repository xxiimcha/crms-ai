<?php
include('../config/database.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'fetch_admissions':
        fetchAdmissions();
        break;
    case 'get_student_details':
        getStudentDetails();
        break;
    case 'get_professor_details':
        getProfessorDetails();
        break;
    case 'save_admission':
        saveAdmission();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}

/**
 * Fetch all admission records
 */
function fetchAdmissions() {
    global $conn;
    $response = ["success" => false, "admissions" => []];

    // Fetch admission records from the database
    $query = "SELECT * FROM admissions ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    // Fetch student data from API
    $students_api = "https://registrar.bcp-sms1.com/api/students.php";
    $students_data = json_decode(file_get_contents($students_api), true);
    $students = [];

    if (!empty($students_data['users'])) {
        foreach ($students_data['users'] as $student) {
            $students[$student['student_info']['student_number']] = $student['student_info'];
        }
    }

    // Fetch professor data from API
    $professors_api = "https://hr.bcp-sms1.com/api/user-api/get_user.php";
    $professors_data = json_decode(file_get_contents($professors_api), true);
    $professors = [];

    if (!empty($professors_data['data'])) {
        foreach ($professors_data['data'] as $professor) {
            $professors[$professor['user_id']] = $professor;
        }
    }

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $person_id = $row['person_id'];
            $admission_type = $row['admission_type'];
            $name = "Undefined";

            // 🔹 Fetch student or professor details
            if ($admission_type === "Student" && isset($students[$person_id])) {
                $student = $students[$person_id];
                $name = $student['first_name'] . " " . $student['last_name'];
                $email = $student['email'];
                $course = $student['course'];
                $year_level = $student['year_level'];
            } elseif ($admission_type === "Professor" && isset($professors[$person_id])) {
                $professor = $professors[$person_id];
                $name = $professor['firstname'] . " " . $professor['lastname'];
                $email = $professor['email'];
                $course = "N/A"; // Professors don't have a course
                $year_level = "N/A"; // Professors don't have a year level
            } else {
                $email = $row['email'];
                $course = $row['course'];
                $year_level = $row['year_level'];
            }

            $response["admissions"][] = [
                "id" => $row["id"],
                "name" => $name,
                "year_level" => $year_level,
                "course" => $course,
                "email" => $email,
                "status" => $row["status"]
            ];
        }
        $response["success"] = true;
    } else {
        $response["message"] = "Failed to fetch admissions: " . mysqli_error($conn);
    }

    echo json_encode($response);
}


/**
 * Fetch student details from API based on student number
 */
function getStudentDetails() {
    $student_number = $_GET['student_number'] ?? '';

    if (empty($student_number)) {
        echo json_encode(["success" => false, "message" => "Student number is required."]);
        exit;
    }

    $api_url = "https://registrar.bcp-sms1.com/api/students.php";
    $student_data = json_decode(file_get_contents($api_url), true);

    if (!empty($student_data['users'])) {
        foreach ($student_data['users'] as $student) {
            if ($student['student_info']['student_number'] === $student_number) {
                echo json_encode([
                    "success" => true,
                    "student" => [
                        "student_number" => $student['student_info']['student_number'],
                        "firstname" => $student['student_info']['first_name'],
                        "middlename" => $student['student_info']['middle_name'],
                        "lastname" => $student['student_info']['last_name'],
                        "email" => $student['student_info']['email'],
                        "year_level" => $student['student_info']['year_level'],
                        "section" => $student['student_info']['section'],
                        "course" => $student['student_info']['course'],
                        "gender" => $student['student_info']['gender'],
                        "age" => calculateAge($student['student_info']['birth_date']),
                    ]
                ]);
                exit;
            }
        }
    }

    echo json_encode(["success" => false, "message" => "Student not found."]);
}

/**
 * Fetch professor details from API based on Employee ID
 */
function getProfessorDetails() {
    $employee_id = $_GET['employee_id'] ?? '';

    if (empty($employee_id)) {
        echo json_encode(["success" => false, "message" => "Employee ID is required."]);
        exit;
    }

    $api_url = "https://hr.bcp-sms1.com/api/user-api/get_user.php";
    $professor_data = json_decode(file_get_contents($api_url), true);

    if (!empty($professor_data['data'])) {
        foreach ($professor_data['data'] as $professor) {
            if ($professor['user_id'] === $employee_id) {
                echo json_encode([
                    "success" => true,
                    "professor" => [
                        "employee_id" => $professor['user_id'],
                        "firstname" => $professor['firstname'],
                        "lastname" => $professor['lastname'],
                        "email" => $professor['email'],
                        "age" => $professor['age'],
                        "gender" => $professor['gender']
                    ]
                ]);
                exit;
            }
        }
    }

    echo json_encode(["success" => false, "message" => "Professor not found."]);
}

/**
 * Save admission and lab test scheduling (if applicable)
 */
function saveAdmission() {
    global $conn;

    $admission_type = mysqli_real_escape_string($conn, $_POST['admission_type']);
    $person_id = '';

    // Determine person_id based on admission type
    if ($admission_type === "Student") {
        $person_id = mysqli_real_escape_string($conn, $_POST['student_number'] ?? '');
    } elseif ($admission_type === "Professor") {
        $person_id = mysqli_real_escape_string($conn, $_POST['employee_id'] ?? '');
    }

    $firstname = mysqli_real_escape_string($conn, $_POST['firstname'] ?? '');
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename'] ?? '');
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $gender = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
    $age = mysqli_real_escape_string($conn, $_POST['age'] ?? '');
    $year_level = mysqli_real_escape_string($conn, $_POST['year_level'] ?? '');
    $section = mysqli_real_escape_string($conn, $_POST['section'] ?? '');
    $course = mysqli_real_escape_string($conn, $_POST['course'] ?? '');
    $position = mysqli_real_escape_string($conn, $_POST['position'] ?? '');

    // 🔹 Ensure symptoms are formatted correctly
    $symptoms = isset($_POST['symptoms']) ? json_encode($_POST['symptoms']) : json_encode([]);

    // 🔹 Ensure empty diagnosis does not cause NULL errors
    $diagnosis = mysqli_real_escape_string($conn, $_POST['correct_diagnosis'] ?? 'Unknown');
    $status = "Pending";

    // Insert into `admissions` table
    $query = "INSERT INTO admissions 
                (admission_type, person_id, firstname, middlename, lastname, email, gender, age, year_level, section, course, position, symptoms, diagnosis, status) 
              VALUES 
                ('$admission_type', '$person_id', '$firstname', '$middlename', '$lastname', '$email', '$gender', '$age', '$year_level', '$section', '$course', '$position', '$symptoms', '$diagnosis', '$status')";

    if (mysqli_query($conn, $query)) {
        $admission_id = mysqli_insert_id($conn);

        // If lab test scheduling is enabled, save lab tests
        if (isset($_POST['lab_schedule_checkbox']) && $_POST['lab_schedule_checkbox'] === 'on') {
            saveLabTest($admission_id);
        }

        echo json_encode(["success" => true, "message" => "Admission saved successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error saving admission: " . mysqli_error($conn)]);
    }
}

/**
 * Save Lab Test Details
 */
function saveLabTest($admission_id) {
    global $conn;

    if (!isset($_POST['lab_procedures']) || !is_array($_POST['lab_procedures'])) {
        return; // Exit function if no lab tests were selected
    }

    $schedule_time = mysqli_real_escape_string($conn, $_POST['schedule_time'] ?? date('Y-m-d H:i:s'));

    // Prepare SQL statement
    $query = "INSERT INTO lab_tests (admission_id, test_name, scheduled_date, status) VALUES ";
    $values = [];

    foreach ($_POST['lab_procedures'] as $test_name) {
        $test_name = mysqli_real_escape_string($conn, $test_name);
        $values[] = "($admission_id, '$test_name', '$schedule_time', 'Pending')";
    }

    // Insert only if tests were selected
    if (!empty($values)) {
        $query .= implode(", ", $values);
        if (!mysqli_query($conn, $query)) {
            error_log("Error saving lab test: " . mysqli_error($conn));
        }
    }
}

/**
 * Helper function to calculate age from birthdate
 */
function calculateAge($birthDate) {
    $today = new DateTime();
    $birth = new DateTime($birthDate);
    $age = $today->diff($birth)->y;
    return $age;
}
?>

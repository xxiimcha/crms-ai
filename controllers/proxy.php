<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiUrl = isset($_GET['type']) && $_GET['type'] === "professor"
    ? "https://hr.bcp-sms1.com/api/user-api/get_user.php"
    : "https://registrar.bcp-sms1.com/api/students.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>

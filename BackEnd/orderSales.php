<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
include("./connect.php");

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection error.']));
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getOrder($conn);
        break;
    default:
        die(json_encode(['error' => 'Invalid request method.']));
}

function getOrder($conn) {
    $result = $conn->query('SELECT * FROM order_tb');

    // user's info
    // $data = json_decode(file_get_contents('php://input'), true);
    // $user_id = $data['userid'];
    // $user_id = 1001;
    // $result = $conn->query('SELECT * FROM order_tb WHERE user_id = ?');
    // $result->bind_param("i", $user_id);

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}


$conn->close();
?>
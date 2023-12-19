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
        $userid = 1017;
        $selectQuery = "SELECT * FROM order_tb WHERE userid=$userid";
        $data = $conn->query($selectQuery);
        $outData = [];
        if($data->num_rows > 0){
            while($row = $data->fetch_assoc()){               
                array_push($outData,$row);
            }
            echo json_encode($outData);
         }
         $conn->close();
        break;
    case 'POST':
        
        break;
    case 'PUT':
        
        break;
    case 'DELETE':
        
        break;
    default:
        die(json_encode(['error' => 'Invalid request method.']));
}
?>
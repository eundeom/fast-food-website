<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
?>
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
            getMenu($conn);
            break;
        case 'POST':
            $userData = json_decode(file_get_contents('php://input'), true);
            if ($userData['user'] === 'A') {
                addMenu($conn);
            } else{
                saveMenu($conn);
            }
            break;
        case 'PUT':
            updateMenu($conn);
            break;
        case 'DELETE':
            deleteMenu($conn);
            break;
        default:
            die(json_encode(['error' => 'Invalid request method.']));
    }

    function tmpFunc($conn){
        $data = json_decode(file_get_contents('php://input'), true);
        print_r($data);
    }

    function getMenu($conn) {
        # show the menu from menu_tb
        $result = $conn->query('SELECT * FROM menu_tb');
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }


    
    $statusMsg = ''; 

    
    function saveMenu($conn){
        // $selectedItems = json_decode($_POST['prod'], true);
        $data = json_decode(file_get_contents('php://input'), true);
        $selectedItems = json_decode($data['prod'],true);
        $id = 0; 
        $prod_id = ""; 
        $prodName = ""; 
        $quantity = 0;
        
        $insertQuery = $conn->prepare("INSERT INTO order_tb (id, prod_id, prodName, quantity) VALUES (?, ?, ?, ?)");
        $insertQuery->bind_param("issi", $id, $prod_id, $prodName, $quantity);

        foreach($selectedItems as $item) {
            print_r($item);
            $id = 1;
            $prod_id = $item['id']; //s
            $prodName = $item['product']; //s
            $quantity = $item['selctAmount']; //i

            $insertQuery->execute();
        }
        $insertQuery->close();
        $conn->close();

    }


    function addMenu($conn) {

        # read the inventory JSON file
        $file = fopen("../data/inventory.json", "r") or die("Unable to open the ");
        $data = fread($file, filesize("../data/inventory.json"));
        fclose($file);
        $data = json_decode($data); 

        # Declare variables 
        $id = null;
        $prodName = null;
        $quantity = null;
        $price = null;
        $food_type = null;
        $prodDescr = null;

        # insert the value to DB
        $insertQuery = $conn->prepare("INSERT INTO menu_tb VALUES(?,?,?,?,?,?)");
        $insertQuery->bind_param("isidss", $id, $prodName, $quantity, $price, $food_type, $prodDescr); //isidss

        foreach($data as $e){
            # set parameters and execute
            $id = $e->id;
            $prodName = $e->product;
            $quantity = $e->amount;
            $price = $e->cost;
            $food_type = null;
            $prodDescr = null;
            
            $insertQuery->execute();
        }
        $insertQuery->close();

        $data = json_decode(file_get_contents('php://input'), true);
        
        $sql = 'INSERT INTO menu_tb (prodName, quantity, price, prodDescr) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sids', $data['prodName'], $data['quantity'], $data['price'], $data['prodDescr']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Menu added successfully.']);
        } else {
            echo json_encode(['error' => 'Menu addition failed.']);
        }

        $stmt->close();
    }

    function updateMenu($conn) {
        $data = json_decode(file_get_contents('php://input'), true);

        $sql = 'UPDATE menu_tb SET prodName = ?, quantity = ?, price = ?, prodDescr = ? WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sidsi', $data['prodName'], $data['quantity'], $data['price'], $data['prodDescr'], $data['id']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Menu deleted successfully.']);
        } else {
            echo json_encode(['error' => 'Menu deletion failed.']);
        }
    
        $stmt->close();
    }

    function deleteMenu($conn) {
        $data = json_decode(file_get_contents('php://input'), true);

        $sql = 'DELETE FROM menu_tb WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $data['id']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Menu deleted successfully.']);
        } else {
            echo json_encode(['error' => 'Menu deletion failed.']);
        }

        $stmt->close();
    }

    function addMenuForC($conn) {
        print_r($_POST["prod"]);
        $data = json_decode(file_get_contents('php://input'), true);

        $sql = 'INSERT INTO menu_tb (prodName, quantity, price, prodDescr) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sids', $data['prodName'], $data['quantity'], $data['price'], $data['prodDescr']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Menu added successfully.']);
        } else {
            echo json_encode(['error' => 'Menu addition failed.']);
        }

        $stmt->close();
    }

    // $conn->close();
?>
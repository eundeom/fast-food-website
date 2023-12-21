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
            saveMenu($conn);
            break;
        default:
            die(json_encode(['error' => 'Invalid request method.']));
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

    function saveMenu($conn){
        $data = json_decode(file_get_contents('php://input'), true);
        $selectedItems = json_decode($data['prod'],true);
        $userID = json_decode($data['user'], true);

        foreach($selectedItems as $item) {
            $prodID = $item['id']; 
            $quantity = $item['selctAmount']; 

            $updateQuery = $conn->prepare("UPDATE menu_tb SET quantity = quantity - ? WHERE id = ?");
            $updateQuery->bind_param("ii", $quantity, $prodID);
            $updateQuery->execute();


            $insertQuery = $conn->prepare("INSERT INTO order_tb (id, prod_id, prodName, quantity, price, user_id, user_fname, user_lname, total_values, order_date, rating)
                        SELECT NULL as id,
                            f.id,
                            f.prodName,
                            $quantity as quantity,
                            f.price,
                            u.id,
                            u.fname,
                            u.lname,
                            f.price * $quantity as total_values,
                            NOW() as order_date, 
                            NULL as rating 
                        FROM menu_tb f
                        LEFT JOIN user_tb u ON u.id = $userID
                        WHERE f.id = $prodID");
            $insertQuery->execute();
        }
        
        // $insertQuery->close();
        $conn->close();

    }


    /// from JSON to DB and read from DB
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


    // function addMenuForC($conn) {
    //     print_r($_POST["prod"]);
    //     $data = json_decode(file_get_contents('php://input'), true);

    //     $sql = 'INSERT INTO menu_tb (prodName, quantity, price, prodDescr) VALUES (?, ?, ?, ?)';
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param('sids', $data['prodName'], $data['quantity'], $data['price'], $data['prodDescr']);

    //     if ($stmt->execute()) {
    //         echo json_encode(['message' => 'Menu added successfully.']);
    //     } else {
    //         echo json_encode(['error' => 'Menu addition failed.']);
    //     }

    //     $stmt->close();
    // }

    // $conn->close();
?>
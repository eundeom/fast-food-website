<?php
    header("Access-Control-Allow-Origin: *");
    include("./connect.php");

    if(isset($_POST['submit'])){
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $file_path = '../data/users.json';

        // Read json file
        $data = file_get_contents($file_path);
        $data_array = json_decode($data, true);

        // set user id
        $last_item = end($data_array);
        $new_id = ($last_item !== false) ? $last_item['id'] + 1 : 1;

        // add new data in json
        $data_new = array(
            'id' => $new_id,
            'fname' => $fname,
            'lname' => $lname,
            'mobile' => $mobile,
            'email' => $email,
            'password' => $password,
            'user_type' => "C"
        );

        $data_array[] = $data_new;

        // update json data
        $new_data = json_encode($data_array);

        if(file_put_contents($file_path, $new_data)){
            echo "Success";
        }

        // add the data in mysql
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO user_tb (id, fname, lname, mobile, email, password, user_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $new_id, $fname, $lname, $mobile, $email, $password, "C");

        if ($stmt->execute()) {
            echo "MySQL: Data added successfully";
        } else {
            echo "MySQL: Failed to add data";
        }
        $stmt->close();
        $conn->close();
    }
?>

<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: *");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userData = fopen("../data/users.json", "r") or die(json_encode(['error' => 'Unable to open the file.']));
    $fileContents = fread($userData, filesize("../data/users.json"));
    fclose($userData);
    
    $users = json_decode($fileContents, true);

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $foundUser = null;
        foreach ($users as $user) {
            if ($user['email'] == $email && $user['password'] == $password) {
                $foundUser = $user;
                break;
            }
        }

        if ($foundUser) {
            if ($_POST["inlineRadioOptions"] == 'customer' && $foundUser['user_type'] == 'C') {
                echo json_encode([
                    'email' => $foundUser['email'],
                    'fname' => $foundUser['fname'],
                    'lname' => $foundUser['lname'],
                    'userType' => $foundUser['user_type']
                ]);
            } elseif ($_POST["inlineRadioOptions"] == 'adminstrator' && $foundUser['user_type'] == 'A') {
                echo json_encode([
                    'email' => $foundUser['email'],
                    'fname' => $foundUser['fname'],
                    'lname' => $foundUser['lname'],
                    'userType' => $foundUser['user_type']
                ]);
            } else {
                echo json_encode(['error' => 'Invalid user type.']);
            }
        } else {
            echo json_encode(['error' => 'Invalid email or password.']);
        }
    }
}
?>

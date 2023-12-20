<?php
   header("Access-Control-Allow-Origin: *"); 
   header("Access-Control-Allow-Headers: *");

   if($_SERVER["REQUEST_METHOD"]=="POST"){
      $userData = fopen("/Applications/XAMPP/xamppfiles/htdocs/PHP/fast-food-website/fast-food-website/data/users.json","r") or die(json_encode(['error' => 'Unable to open the file.']));
      $user = json_decode(fread($userData,filesize("/Applications/XAMPP/xamppfiles/htdocs/PHP/fast-food-website/fast-food-website/data/users.json")));
      fclose($userData);
     if(isset($_POST["email"]) && isset($_POST["password"])){
      foreach($user as $uObj){
         if($uObj->email == $_POST["email"] && $uObj->password == $_POST["password"]){
            echo(json_encode($uObj));
            break;
         }
      }
     }else{
      $inputData = file_get_contents("php://input");
      $inputData = json_decode($inputData);
      foreach($user as $uObj){
         if($uObj->email == $inputData->email && $uObj->password == $inputData->password){
            echo json_encode($uObj);
            break;
         }
      }
     }
   }
   
?>
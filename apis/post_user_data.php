<?php

error_reporting(0);

header('Access-Controll-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Controll-Allow-Method: POST');
header('Access-Controll-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization,X-Request-With');

include('user_function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if($requestMethod == "POST"){

    $inputData = json_decode(file_get_contents("php://input"),true);
    if(empty($inputData)){
        
        $storeUser = storeUser($_POST);
    }
    else{
        $storeUser = storeUser($inputData);
        
    }

    echo $storeUser;
    

}
else{

    $data = [
        'status' => 405,
        'message' => $requestMethod. 'Method not allowed',
     ];
     header("HTTP/1.0 405 Method not allowed");
     echo json_encode($data);
}



?>
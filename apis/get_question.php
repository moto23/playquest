<?php
header('Access-Controll-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Controll-Allow-Method: GET');
header('Access-Controll-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization,X-Request-With');

include('user_function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if($requestMethod == "GET"){

    if(isset($_GET['mode_id'])){
        $getQuestion = getQuestion($_GET);
        echo $getQuestion;
    }
    

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
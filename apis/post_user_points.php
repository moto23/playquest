<?php

error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include('user_function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "POST") {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
    $mode_id = isset($_GET['mode_id']) ? $_GET['mode_id'] : '';
    $points = isset($_GET['points']) ? $_GET['points'] : '';
    $reward = isset($_GET['reward']) ? $_GET['reward'] : '';

    $storeUserPoints = storeUserPoints([
        'user_id' => $user_id,
        'mode_id' => $mode_id,
        'points' => $points,
        'reward' => $reward,
    ]);

    echo $storeUserPoints;
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method not allowed',
    ];
    header("HTTP/1.0 405 Method not allowed");
    echo json_encode($data);
}

?>

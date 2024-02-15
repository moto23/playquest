<?php

require "../inc/dbcon.php";

function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
     ];
     header("HTTP/1.0 422 Unprocessable Entity");
     echo json_encode($data);
     exit();
}

function storeUser($params){
    global $conn;
    $user_name = mysqli_real_escape_string($conn,$params['user_name']);
    $user_key = mysqli_real_escape_string($conn,$params['user_key']);
    $phone_no = mysqli_real_escape_string($conn,$params['phone_no']);
    $user_qualification = mysqli_real_escape_string($conn,$params['user_qualification']);
    $year = mysqli_real_escape_string($conn,$params['year']);

    if(empty(trim($user_name))){
        return error422('Enter user name');
    }
    elseif(empty(trim($user_key))){
        return error422('Enter user key');
    }elseif(empty(trim($phone_no))){
        return error422('Enter phone number');
    }elseif(empty(trim($user_qualification))){
        return error422('Enter qualification');
    }elseif(empty(trim($year))){
        return error422('Enter year');
    }
    else{
        $query = "INSERT INTO user(user_name,user_key,user_qualification,phone_no,user_year, first_login, recent_login) values('$user_name','$user_key','$user_qualification','$phone_no','$year', CURDATE(), CURDATE())";
        $result = mysqli_query($conn,$query);

        if($result){
            $data = [
                'status' => 201,
                'message' => 'User created successfully',
             ];
             header("HTTP/1.0 201 Created");
             return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
             ];
             header("HTTP/1.0 500 Internal Server Error");
             return json_encode($data);
        }
    }

}


function getUserId($labelParams){
    global $conn;

    if($labelParams['user_key'] == null){
        return error422('Enter user key');
    }

    $user_key_query = mysqli_real_escape_string($conn,$labelParams['user_key']);
    $query = "SELECT user_id FROM user WHERE user_key ='$user_key_query' LIMIT 1";
    $result = mysqli_query($conn,$query);

    if($result){
        if(mysqli_num_rows($result) == 1){
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'User id fetched successfully',
                'data' => $res
             ];
             header("HTTP/1.0 200 OK");
             return json_encode($data);
        }
        else{
            $data = [
                'status' => 469,
                'message' => 'No user found',
             ];
             header("HTTP/1.0 469 Not found");
             return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
         ];
         header("HTTP/1.0 500 Internal Server Error");
         return json_encode($data);
    }
}

function getGameMode($labelParams){
    global $conn;

    if($labelParams['champ_id'] == null){
        return error422('Enter Championship id');
    }
    if($labelParams['user_id'] == null){
        return error422('Enter User id');
    }

    $user_champ_query = mysqli_real_escape_string($conn,$labelParams['champ_id']);
    $user_key_query = mysqli_real_escape_string($conn,$labelParams['user_id']);

    // $query = "SELECT game_mode.mode_id, mode_name, time_minutes, no_of_question, user_points.user_id FROM game_mode left join user_points on game_mode.mode_id = user_points.mode_id  AND (user_points.user_id = '$user_key_query' OR user_points.user_id IS NULL) WHERE champ_id = '$user_champ_query';";
    
    $query = "SELECT mode_id, mode_name, time_minutes, no_of_question FROM game_mode WHERE champ_id = '$user_champ_query';";

    $result = mysqli_query($conn,$query);


    if($result){
        if(mysqli_num_rows($result) > 0){
            // $res = mysqli_fetch_assoc($result);
            $res = mysqli_fetch_all($result,MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Game Mode fetched successfully',
                'data' => $res
             ];
             header("HTTP/1.0 200 OK");
             return json_encode($data);
        }
        else{
            $data = [
                'status' => 469,
                'message' => 'No game modes',
             ];
             header("HTTP/1.0 469 Not found");
             return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
         ];
         header("HTTP/1.0 500 Internal Server Error");
         return json_encode($data);
    }
}

function getUserData($params){
    global $conn;

    if($params['user_id'] == null){
        return error422('Enter user Id');
    }

    $user_id_query = mysqli_real_escape_string($conn,$params['user_id']);
    $query = "SELECT user.user_id, user_name, user_qualification, user_year, sum(points) as points, count(*) as champs FROM user left join user_points on user.user_id = user_points.user_id WHERE user.user_id ='$user_id_query'";
    $result = mysqli_query($conn,$query);

    if($result){
        if(mysqli_num_rows($result) == 1){
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'User data fetched successfully',
                'data' => $res
             ];
             header("HTTP/1.0 200 OK");
             return json_encode($data);
        }
        else{
            $data = [
                'status' => 469,
                'message' => 'No user found',
             ];
             header("HTTP/1.0 469 Not found");
             return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
         ];
         header("HTTP/1.0 500 Internal Server Error");
         return json_encode($data);
    }
}

function getCategoryList(){

    global $conn;

    $query = "select start_date, end_date, start_time, end_time, champ_id , category.category_id , category_name , champ_name from category left join championship on category.category_id = championship.category_id";
    $query_run = mysqli_query($conn,$query);

    if($query_run){

        if(mysqli_num_rows($query_run) > 0){

            $res = mysqli_fetch_all($query_run,MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'category fetched successfully',
                'data' => $res
             ];
             header("HTTP/1.0 200 OK");
             return json_encode($data);

        }else{
            $data = [
                'status' => 469,
                'message' => 'No category found',
             ];
             header("HTTP/1.0 469 No category found");
             return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
         ];
         header("HTTP/1.0 500 Internal Server Error");
         return json_encode($data);
    }

}

function getQuestion($params){
    global $conn;

    if($params['mode_id'] == null){
        return error422('Enter correct championship id or game mode');
    }

    $modeId = mysqli_real_escape_string($conn,$params['mode_id']);


    $query = "SELECT question.question_id, question_text, option1_text, option2_text, option3_text, option4_text, correct_answer, total_coins from question inner join chosen_questions on question.label_id = chosen_questions.label_id inner join answer on question.question_id = answer.question_id where chosen_questions.mode_id = '$modeId';";
    // select question_text, option1_text, option2_text, option3_text, option4_text, correct_answer from question inner join chosen_questions on question.label_id = chosen_questions.label_id inner join answer on question.question_id = answer.question_id where chosen_questions.mode_id = 2;
    $result = mysqli_query($conn,$query);

    if($result){
        if(mysqli_num_rows($result) > 0){
            $res = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Questions fetched successfully',
                'data' => $res
             ];
             header("HTTP/1.0 200 OK");
             return json_encode($data);
        }
        else{
            $data = [
                'status' => 469,
                'message' => 'No questions found',
             ];
             header("HTTP/1.0 469 Not found");
             return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
         ];
         header("HTTP/1.0 500 Internal Server Error");
         return json_encode($data);
    }
}


function storeUserPoints($params){
    global $conn;
    $user_id = mysqli_real_escape_string($conn,$params['user_id']);
    $mode_id = mysqli_real_escape_string($conn,$params['mode_id']);
    $points = mysqli_real_escape_string($conn,$params['points']);
    $reward = mysqli_real_escape_string($conn,$params['reward']);

    

    if(empty(trim($user_id))){
        return error422('Enter user id of points');
    }
    elseif(empty(trim($mode_id))){
        return error422('Enter mode id of points');
    }
    else{
        $query = "INSERT INTO user_points(user_id,mode_id,points,reward) values('$user_id','$mode_id','$points','$reward')";
        $result = mysqli_query($conn,$query);

        if($result){
            $data = [
                'status' => 201,
                'message' => 'Points added successfully',
             ];
             header("HTTP/1.0 201 Created");
             return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
             ];
             header("HTTP/1.0 500 Internal Server Error");
             return json_encode($data);
        }
    }

}


function storeWrongQuestion($params){
    global $conn;
    $user_id = mysqli_real_escape_string($conn,$params['user_id']);
    $question_id = mysqli_real_escape_string($conn,$params['question_id']); 

    if(empty(trim($user_id))){
        return error422('Enter user id of wrong question');
    }
    elseif(empty(trim($question_id))){
        return error422('Enter question id of wrong question');
    }
    else{
        $query = "INSERT INTO wrong_question(user_id,question_id) values('$user_id','$question_id')";
        $result = mysqli_query($conn,$query);

        if($result){
            $data = [
                'status' => 201,
                'message' => 'Wrong question added successfully',
             ];
             header("HTTP/1.0 201 Created");
             return json_encode($data);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
             ];
             header("HTTP/1.0 500 Internal Server Error");
             return json_encode($data);
        }
    }

}


function putUserLogin($params){
    global $conn;

    if($params['user_id'] == null){
        return error422('Enter user id');
    }

    $user_id_query = mysqli_real_escape_string($conn,$params['user_id']);
    $query = "update user set recent_login = CURDATE() where user_id = '$user_id_query'";
    $result = mysqli_query($conn,$query);

    if($result){
       
            

            $data = [
                'status' => 200,
                'message' => 'User id fetched successfully',
           
             ];
             header("HTTP/1.0 200 OK");
             return json_encode($data);
        
       
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
         ];
         header("HTTP/1.0 500 Internal Server Error");
         return json_encode($data);
    }



}




function getUserPoints($params){
    global $conn;

    if($params['user_id'] == null){
        return error422('Enter user id');
    }

    $user_id_query = mysqli_real_escape_string($conn,$params['user_id']);
    $query = "SELECT sum(points) from user_points where user_id=$user_id_query LIMIT 1";
    $result = mysqli_query($conn,$query);

    if($result){
        if(mysqli_num_rows($result) == 1){
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'User id fetched successfully',
                'data' => $res
             ];
             header("HTTP/1.0 200 OK");
             return json_encode($data);
        }
        else{
            $data = [
                'status' => 469,
                'message' => 'No user found',
             ];
             header("HTTP/1.0 469 Not found");
             return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
         ];
         header("HTTP/1.0 500 Internal Server Error");
         return json_encode($data);
    }
}




?>
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
include_once '../config/conf.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$stmt = $user->read();
$num = $stmt->rowCount();

if($num>0){
    $users_arr=array();
    $users_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $user_res=array(
            "id" => $id,
            "username" => $username,
            "password" => $password,
            "deposit" => $deposit,
            "role" => $role
        );
        
        array_push($users_arr["records"], $user_res);
    }
    
    http_response_code(200);
    echo json_encode($users_arr);
}else{
    http_response_code(404);
    echo json_encode(array("message" => "No users found."));
}


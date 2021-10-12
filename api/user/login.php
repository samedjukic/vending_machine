<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;

include_once '../config/database.php';

include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input", true));

if(!empty($data->username) && !empty($data->password) ){

    $user->username = $data->username;
    $user->password = md5($data->password);
    
    $stmt = $user->readOne();
    $num = $stmt->rowCount();

    if($num>0){
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $user_id = $row['id'];
        $role = $row['role'];
            
        $payload = array('user_id'=>$user_id, 'role'=>$role, 'exp'=>(time() + 3600));
        
        $jwt = JWT::encode($payload, 'secret');
            
        echo json_encode(array('message' => "Sucessfully login.", 'token' => $jwt));
    }else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to login. Invalid user."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to login. Data is incomplete."));
}
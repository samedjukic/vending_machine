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

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

if (!$authHeader){
    http_response_code(400);
    echo json_encode(array("message" => "Access denied."));
    die();
}

$arr = explode(" ", $authHeader);

$jwt = $arr[1];

$data = json_decode(file_get_contents("php://input"));

if($jwt) {
    
    $decoded = JWT::decode($jwt, JWT_SECRET_KEY, array('HS256'));
    
    if ($decoded->role == 'buyer'){
        if (in_array($data->deposit, ACCEPTED_COINS)){
            $user->id = $decoded->user_id;
            
            $user->readOneById();
            
            $user->deposit += $data->deposit;
            
            if($user->update()){
                http_response_code(200);
                echo json_encode(array("message" => "User deposit was updated."));
            }else{
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update user deposit."));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update user deposit. We dont accept that coin amount."));
        }
    }else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to update user deposit. You dont have buyer role."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Access denied."));
}


?>
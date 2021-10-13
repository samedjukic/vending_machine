<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;

include_once '../config/conf.php';
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
    $user->id = $decoded->user_id;
        
    $user->username = $data->username;
    $user->password = md5($data->password);
    $user->deposit = $data->deposit;
    $user->role = $data->role;
        
    if($user->update()){
        http_response_code(200);
        echo json_encode(array("message" => "User was updated."));
    }else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update user."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Access denied."));
}


?>
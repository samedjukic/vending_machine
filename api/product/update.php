<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;

include_once '../config/database.php';
include_once '../config/conf.php';
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

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
    
    if ($decoded->role == 'seller'){
        $product->id = $data->id;
        
        $product->readOne();
        
        if ($product->sellerId == $decoded->user_id){
            $product->productName = $data->productName;
            $product->cost = $data->cost;
            $product->amountAvailable = $data->amountAvailable;
            $product->sellerId = $decoded->user_id;
            
            if($product->update()){
                http_response_code(200);
                echo json_encode(array("message" => "Product was updated."));
            }else{
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update product."));
            }
        }else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update product. You are not the owner of this product."));
        }
    }else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to update product. You dont have seller role."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Access denied."));
}


?>
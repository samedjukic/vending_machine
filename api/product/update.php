<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;

// include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

if (!$authHeader){
    http_response_code(400);
    echo json_encode(array("message" => "Access denied."));
    die();
}

$arr = explode(" ", $authHeader);

$jwt = $arr[1];

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

if($jwt) {
    
    $decoded = JWT::decode($jwt, 'secret', array('HS256'));
    
    if ($decoded->role == 'seller'){
        $product->id = $data->id;
        
        $product->readOne();
        
        if ($product->sellerId == $decoded->user_id){
        
            // set product property values
            $product->productName = $data->productName;
            $product->cost = $data->cost;
            $product->amountAvailable = $data->amountAvailable;
            $product->sellerId = $decoded->user_id;
            
            // update the product
            if($product->update()){
                
                // set response code - 200 ok
                http_response_code(200);
                
                // tell the user
                echo json_encode(array("message" => "Product was updated."));
            }
            
            // if unable to update the product, tell the user
            else{
                
                // set response code - 503 service unavailable
                http_response_code(503);
                
                // tell the user
                echo json_encode(array("message" => "Unable to update product."));
            }
        }else {
            // set response code - 400 bad request
            http_response_code(400);
            
            // tell the user
            echo json_encode(array("message" => "Unable to update product. You are not the owner of this product."));
        }
    }else{
        // set response code - 400 bad request
        http_response_code(400);
        
        // tell the user
        echo json_encode(array("message" => "Unable to update product. You dont have seller role."));
    }
}


?>
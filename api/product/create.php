<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../../vendor/autoload.php";
use \Firebase\JWT\JWT;

// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

$jwt = $arr[1];

// get posted data
$data = json_decode(file_get_contents("php://input"));

if($jwt) {
    
    $decoded = JWT::decode($jwt, 'secret', array('HS256'));
    
    if ($decoded->role == 'seller'){
    // make sure data is not empty
        if(!empty($data->productName) && !empty($data->cost) && !empty($data->amountAvailable)){
                
                // set product property values
                $product->productName = $data->productName;
                $product->cost = $data->cost;
                $product->amountAvailable = $data->amountAvailable;
                $product->sellerId = $decoded->user_id;
                
                // create the product
                if($product->create()){
                    
                    // set response code - 201 created
                    http_response_code(201);
                    
                    // tell the user
                    echo json_encode(array("message" => "Product was created."));
                }
                
                // if unable to create the product, tell the user
                else{
                    
                    // set response code - 503 service unavailable
                    http_response_code(503);
                    
                    // tell the user
                    echo json_encode(array("message" => "Unable to create product."));
                }
        }
        
        // tell the user data is incomplete
        else{
            
            // set response code - 400 bad request
            http_response_code(400);
            
            // tell the user
            echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
        }
    }else{
        // set response code - 400 bad request
        http_response_code(400);
        
        // tell the user
        echo json_encode(array("message" => "Unable to create product. You dont have seller role."));
    }
}else{
    http_response_code(400);
    
    // tell the user
    echo json_encode(array("message" => "Access denied."));
}
?>
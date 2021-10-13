<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/conf.php';
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$product->id = isset($_GET['id']) ? $_GET['id'] : die();

$product->readOne();

if($product->productName!=null){
    $product_arr = array(
        "id" =>  $product->id,
        "productName" => $product->productName,
        "cost" => $product->cost,
        "amountAvailable" => $product->amountAvailable,
        "sellerId" => $product->sellerId
    );
    
    http_response_code(200);
    echo json_encode($product_arr);
}else{
    http_response_code(404);
    echo json_encode(array("message" => "Product does not exist."));
}
?>
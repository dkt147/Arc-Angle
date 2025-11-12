<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->order_id) && !empty($data->photo)) {
    $query = "UPDATE orders SET photo = :photo WHERE id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":photo", $data->photo);
    $stmt->bindParam(":order_id", $data->order_id);
    
    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Order image updated successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update order image."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update order image. Data is incomplete."));
}
?>
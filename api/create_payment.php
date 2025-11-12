<?php

header("Content-Type: application/json");

header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: POST");

header("Access-Control-Allow-Headers: Content-Type");



include_once '../config/database.php';



// Include Stripe PHP library (you'll need to install it via composer)

// require_once '../vendor/autoload.php';



$database = new Database();

$db = $database->getConnection();



$data = json_decode(file_get_contents("php://input"));



if(!empty($data->order_id) && !empty($data->amount)) {

    try {

        // Verify order exists

        $order_query = "SELECT * FROM orders WHERE id = :order_id AND status = 'pending'";

        $order_stmt = $db->prepare($order_query);

        $order_stmt->bindParam(":order_id", $data->order_id);

        $order_stmt->execute();

        

        if($order_stmt->rowCount() == 0) {

            throw new Exception("Order not found or already processed");

        }

        

        // Initialize Stripe (replace with your secret key)

        // \Stripe\Stripe::setApiKey('sk_test_your_secret_key');

        

        // Create Stripe payment intent

        // $paymentIntent = \Stripe\PaymentIntent::create([

        //     'amount' => $data->amount * 100, // Convert to cents

        //     'currency' => 'usd',

        //     'metadata' => ['order_id' => $data->order_id]

        // ]);

        

        // For now, we'll simulate a payment intent ID

        $stripe_payment_intent_id = "pi_" . bin2hex(random_bytes(16));

        

        // Create transaction record

        $query = "INSERT INTO transactions 

                  (order_id, stripe_payment_intent_id, amount, status) 

                  VALUES 

                  (:order_id, :stripe_payment_intent_id, :amount, 'requires_payment_method')";

        

        $stmt = $db->prepare($query);

        $stmt->bindParam(":order_id", $data->order_id);

        $stmt->bindParam(":stripe_payment_intent_id", $stripe_payment_intent_id);

        $stmt->bindParam(":amount", $data->amount);

        

        if($stmt->execute()) {

            http_response_code(201);

            echo json_encode(array(

                "message" => "Payment intent created successfully",

                "payment_intent_id" => $stripe_payment_intent_id,

                "client_secret" => "simulated_client_secret" // In real implementation: $paymentIntent->client_secret

            ));

        } else {

            throw new Exception("Failed to create transaction record");

        }

        

    } catch(Exception $e) {

        http_response_code(400);

        echo json_encode(array("message" => $e->getMessage()));

    }

} else {

    http_response_code(400);

    echo json_encode(array("message" => "Unable to create payment. Data is incomplete."));

}

?>
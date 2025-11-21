<?php

header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: POST");

header("Access-Control-Allow-Headers: Content-Type");



include_once '../config/database.php';



error_log(print_r($_FILES, true));



// echo json_encode($_FILES['photo']);

// exit;



function generateRandomPassword($length = 12) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";

    return substr(str_shuffle($chars), 0, $length);

}



function sendPasswordEmail($email, $password, $username) {

    $subject = "Your Account Password";

    $message = "

    Hello $username,

    

    Your account has been created successfully!

    

    Your login credentials:

    Email: $email

    Password: $password

    

    Please login and change your password for security.

    

    Best regards,

    Your Team

    ";

    

    $headers = "From: noreply@yourdomain.com\r\n" .

               "Reply-To: noreply@yourdomain.com\r\n" .

               "X-Mailer: PHP/" . phpversion();

    

    return mail($email, $subject, $message, $headers);

}



function handleFileUpload($file, $upload_dir = __DIR__ . "/upload/") {

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {

        error_log("Upload error: " . $file['error']);

        return '';

    }



    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

    if (!in_array($file['type'], $allowed_types)) {

        error_log("Invalid file type: " . $file['type']);

        return '';

    }



    if (!file_exists($upload_dir)) {

        mkdir($upload_dir, 0777, true);

    }



    $extension = pathinfo($file["name"], PATHINFO_EXTENSION);

    $filename = uniqid() . "." . $extension;

    $target_file = $upload_dir . $filename;



    if (move_uploaded_file($file["tmp_name"], $target_file)) {

        return $filename;

    } else {

        error_log("Failed to move file to $target_file");

    }



    return '';

}





$database = new Database();

$db = $database->getConnection();



// Validate required fields

$required_fields = ['full_name', 'email', 'reason', 'creator_type', 'address_1', 'city', 'state', 'postal_code', 'country'];



foreach ($required_fields as $field) {

    if (empty($_POST[$field])) {

        http_response_code(400);

        echo json_encode(["message" => "Missing required field: $field"]);

        exit;

    }

}



try {

    $db->beginTransaction();



    $full_name = $_POST['full_name'];

    $email = $_POST['email'];

    $reason = $_POST['reason']; // expected as comma-separated string

    $creator_type = $_POST['creator_type'];

    $address_1 = $_POST['address_1'];

    $address_2 = $_POST['address_2'] ?? '';

    $city = $_POST['city'];

    $state = $_POST['state'];

    $postal_code = $_POST['postal_code'];

    $country = $_POST['country'];



    // Check if user already exists

    $check_user = $db->prepare("SELECT id FROM users WHERE email = :email or username = :username");

    $check_user->bindParam(":email", $email);
    $check_user->bindParam(":username", $full_name);

    $check_user->execute();



    if ($check_user->rowCount() > 0) {
         http_response_code(409);
        echo json_encode(["message" =>"User with this email or name already exists"]);
        exit;
        // throw new Exception("User with this email or name already exists");
    }



    // Create user

    $random_password = generateRandomPassword();

    $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);



    $create_user = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

    $create_user->bindParam(":username", $full_name);

    $create_user->bindParam(":email", $email);

    $create_user->bindParam(":password", $hashed_password);



    if (!$create_user->execute()) {

        throw new Exception("Failed to create user");

    }



    $user_id = $db->lastInsertId();



    // Handle file uploads

    $photo_filename = handleFileUpload($_FILES['photo']);

    $bg_filename = handleFileUpload($_FILES['bg']);



    // Create order

    $create_order = $db->prepare("

        INSERT INTO orders 

        (user_id, cards, creator_type, address_1, address_2, city, state, postal_code, country, photo, bg) 

        VALUES 

        (:user_id, :cards, :creator_type, :address_1, :address_2, :city, :state, :postal_code, :country, :photo, :bg)

    ");



    $create_order->bindParam(":user_id", $user_id);

    $create_order->bindParam(":cards", $reason);

    $create_order->bindParam(":creator_type", $creator_type);

    $create_order->bindParam(":address_1", $address_1);

    $create_order->bindParam(":address_2", $address_2);

    $create_order->bindParam(":city", $city);

    $create_order->bindParam(":state", $state);

    $create_order->bindParam(":postal_code", $postal_code);

    $create_order->bindParam(":country", $country);

    $create_order->bindParam(":photo", $photo_filename);

    $create_order->bindParam(":bg", $bg_filename);



    if (!$create_order->execute()) {

        throw new Exception("Failed to create order");

    }



    $order_id = $db->lastInsertId();



    // Send email
    $email_sent = true;
    // $email_sent = sendPasswordEmail($email, $random_password, $full_name);



    $db->commit();



    http_response_code(201);

    

    // Fetch full order details

$get_order = $db->prepare("SELECT * FROM orders WHERE id = :order_id");

$get_order->bindParam(':order_id', $order_id);

$get_order->execute();



$order_data = $get_order->fetch(PDO::FETCH_ASSOC);



if (!$order_data) {

    throw new Exception("Failed to retrieve created order");

}



// Then respond with full order data

http_response_code(201);

echo json_encode([

    "message" => "Order created successfully",

    "order" => $order_data,

    "user_id" => $user_id,

    "email_sent" => $email_sent

]);





} catch (Exception $e) {

    $db->rollBack();
 // Log the error for debugging purposes
    error_log("Error: " . $e->getMessage());
    http_response_code(400);

    echo json_encode(["message" => $e->getMessage()]);

}

?>


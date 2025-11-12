<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get query parameters for filtering/pagination
$user_id = $_GET['user_id'] ?? null;
$status = $_GET['status'] ?? null;
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$offset = ($page - 1) * $limit;

try {
    // 1. Fetch paginated orders
    $query = "SELECT o.*, u.username, u.email, t.amount as payment_amount, t.status as payment_status 
              FROM orders o 
              LEFT JOIN users u ON o.user_id = u.id 
              LEFT JOIN transactions t ON o.id = t.order_id 
              WHERE 1=1";
    
    $params = [];

    if ($user_id) {
        $query .= " AND o.user_id = :user_id";
        $params[':user_id'] = $user_id;
    }

    if ($status) {
        $query .= " AND o.status = :status";
        $params[':status'] = $status;
    }

    $query .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Total count for pagination
    $count_query = "SELECT COUNT(*) as total FROM orders WHERE 1=1";
    $count_params = [];

    if ($user_id) {
        $count_query .= " AND user_id = :user_id";
        $count_params[':user_id'] = $user_id;
    }

    if ($status) {
        $count_query .= " AND status = :status";
        $count_params[':status'] = $status;
    }

    $count_stmt = $db->prepare($count_query);
    foreach ($count_params as $key => $value) {
        $count_stmt->bindValue($key, $value);
    }
    $count_stmt->execute();
    $total = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 3. Group by city to get order counts per region
    $region_query = "SELECT city, COUNT(*) as order_count FROM orders GROUP BY city ORDER BY order_count DESC";
    $region_stmt = $db->prepare($region_query);
    $region_stmt->execute();
    $regions = $region_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Final response
    http_response_code(200);
    echo json_encode(array(
        "orders" => $orders,
        "regions" => $regions, // now city-based summary
        "pagination" => array(
            "page" => (int)$page,
            "limit" => (int)$limit,
            "total" => (int)$total,
            "pages" => ceil($total / $limit)
        )
    ));

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Unable to fetch orders: " . $e->getMessage()));
}
?>

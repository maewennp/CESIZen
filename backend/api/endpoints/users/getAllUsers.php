<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/User.php';
require_once '../../controllers/UsersControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new UsersControllers($db);

    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7);
    }

    $users = $controller->adminGetAllUsers($token);

    if (isset($users['error'])) {
        http_response_code(403);
        echo json_encode($users);
    } else {
        http_response_code(200);
        echo json_encode($users);
    }

} catch (Exception $e) {
    error_log("Erreur getAllUsers: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}
?>

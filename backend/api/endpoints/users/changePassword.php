<?php
require_once '../../../database.php';
require_once '../../../api/models/User.php';
require_once '../../../api/controllers/UsersControllers.php';
require_once '../../../api/cors.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new UsersControllers($db);

    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7);
    }

    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id_user'], $input['old_password'], $input['new_password'])) {
        http_response_code(400);
        echo json_encode(["error" => "Champs requis manquants"]);
        exit;
    }

    $result = $controller->changePasswordConnected($token, $input['id_user'], $input['old_password'], $input['new_password']);

    if (isset($result['error'])) {
        http_response_code(403);
    } else {
        http_response_code(200);
    }
    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur changePassword : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}
?>

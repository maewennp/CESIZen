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

    $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input)) {
        http_response_code(400);
        echo json_encode(["error" => "Critères de recherche manquants"]);
        exit;
    }

    $result = $controller->getOneUser($token, $input);

    if (isset($result['error'])) {
        http_response_code(404);
    } else {
        http_response_code(200);
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur getOneUser: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}
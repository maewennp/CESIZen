<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/User.php';
require_once '../../controllers/UsersControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new UsersControllers($db);

    // Authentification
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';
    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7);
    }

    // Récupération des données
    $input = json_decode(file_get_contents("php://input"), true);

    if (
        empty($input) ||
        !isset($input['username'], $input['email'], $input['password'], $input['is_admin'])
    ) {
        http_response_code(400);
        echo json_encode(["error" => "Champs requis manquants"]);
        exit;
    }

    $result = $controller->adminCreateUser($token, $input);

    if (isset($result['error'])) {
        http_response_code(403);
    } else {
        http_response_code(201);
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur createUser: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}
?>

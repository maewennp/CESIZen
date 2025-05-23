<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/Info.php';
require_once '../../controllers/InfoControllers.php';


header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new InfoControllers($db);

    // Récupération du token
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7); // Retire "Bearer "
    }

    // Récupération des données du POST
    $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input)) {
        http_response_code(400);
        echo json_encode(["error" => "Aucune donnée reçue."]);
        exit;
    }

    // Appel au contrôleur
    $result = $controller->createInfo($token, $input);

    if (isset($result['error'])) {
        http_response_code(403);
    } else {
        http_response_code(201); // Created
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur createInfo: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}

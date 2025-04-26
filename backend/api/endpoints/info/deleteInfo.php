<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/Info.php';
require_once '../../controllers/InfoControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new InfoControllers($db);

    // Récupérer le token
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7); // Retire "Bearer "
    }

    // Récupérer l'ID depuis l'URL (query param ?id_content=...)
    if (!isset($_GET['id_content'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID requis."]);
        exit;
    }

    $id_content = (int) $_GET['id_content'];

    $result = $controller->deleteInfo($token, $id_content);

    if (isset($result['error'])) {
        http_response_code(403);
    } else {
        http_response_code(200); 
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur deleteInfo: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}

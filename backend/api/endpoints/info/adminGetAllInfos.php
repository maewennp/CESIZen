<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/Info.php';
require_once '../../controllers/InfoControllers.php';
require_once '../../../vendor/autoload.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new InfoControllers($db);

    // Récupérer le token depuis l'en-tête Authorization
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authHeader);

    $auth = $controller->checkAuth($token);

    if (isset($auth->error)) {
        http_response_code(403);
        echo json_encode(["error" => $auth->error]);
        exit;
    }

    if (!($auth->is_admin ?? false)) {
        http_response_code(403);
        echo json_encode(["error" => "Accès réservé aux administrateurs"]);
        exit;
    }

    $infos = $controller->adminGetAllInfos($token);
    http_response_code(200);
    echo json_encode($infos);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur", "details" => $e->getMessage()]);
}

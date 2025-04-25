<?php

require_once '../../../database.php';
require_once '../../../api/controllers/UsersControllers.php';
require_once '../../../api/models/User.php';
require_once '../../../api/cors.php';

header("Content-Type: application/json; charset=UTF-8");

try {
    $db = Database::getConnection();
    $controller = new UsersControllers($db);

    // Récupère le token du header Authorization
    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? '';

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(["error" => "Token manquant ou mal formé"]);
        exit;
    }

    $token = trim(str_replace("Bearer", "", $authHeader));
    $result = $controller->getProfile($token);

    http_response_code(isset($result['error']) ? 401 : 200);
    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur serveur: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}

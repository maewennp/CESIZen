<?php
require_once '../../../database.php';
require_once '../../../api/controllers/AuthControllers.php';
require_once '../../../api/cors.php';

try {
    $db = Database::getConnection();
    $authController = new AuthController($db);

    // Vérifie si la requête contient du JSON valide
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["error" => "Format JSON invalide"]);
        //exit();
    }

    $result = $authController->login($data);
    error_log("Résultat de la connexion : " . print_r($result, true));

    // Retourne un code 200 si OK, sinon 401
    if (isset($result['error'])) {
        http_response_code(401);
    } else {
        http_response_code(200);
    }
    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur serveur: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur interne"]);
}


<?php
require_once '../../../database.php';
require_once '../../../api/models/User.php';
require_once '../../../api/controllers/UsersControllers.php';
require_once '../../../api/cors.php';
require_once '../../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    $db = Database::getConnection();
    $controller = new UsersControllers($db);

    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['reset_token'], $input['new_password'])) {
        http_response_code(400);
        echo json_encode(["error" => "Champs requis manquants"]);
        exit;
    }

    $secret = $_ENV['JWT_SECRET'] ?? 'secret_key';

    try {
        $decoded = JWT::decode($input['reset_token'], new Key($secret, 'HS256'));
        $email = $decoded->email ?? null;

        if (!$email) {
            throw new Exception("Token invalide");
        }

        $response = $controller->changePassword($email, $input['new_password']);
        http_response_code(isset($response['error']) ? 400 : 200);
        echo json_encode($response);

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Token invalide ou expiré"]);
    }

} catch (Exception $e) {
    error_log("Erreur resetPassword : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}
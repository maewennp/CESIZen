<?php
require_once '../../../database.php';
require_once '../../../api/models/User.php';
require_once '../../../api/cors.php';
require_once '../../../vendor/autoload.php';

use Firebase\JWT\JWT;

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $userModel = new User($db);

    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['email'])) {
        http_response_code(400);
        echo json_encode(["error" => "Email obligatoire."]);
        exit();
    }

    $email = trim($data['email']);

    // Vérifie si l'email existe
    $user = $userModel->getUserByEmail($email);
    if (!$user) {
        http_response_code(404);
        echo json_encode(["error" => "Email non trouvé."]);
        exit();
    }

    $secret = $_ENV['JWT_SECRET'] ?? 'secret_key';

    // Crée un token spécial reset password
    $payload = [
        "email" => $email,
        "exp" => time() + (15 * 60) // expire dans 15 minutes
    ];

    $resetToken = JWT::encode($payload, $secret, 'HS256');

    
    // Pour l'instant on le retourne le token directement pour test
    http_response_code(200);
    echo json_encode([
        "message" => "Token de réinitialisation généré avec succès.",
        "reset_token" => $resetToken
    ]);

} catch (Exception $e) {
    error_log("Erreur dans forgotPassword.php : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}

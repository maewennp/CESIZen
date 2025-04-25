<?php
require_once '../../../database.php';
require_once '../../../api/models/User.php';
require_once '../../../api/cors.php';
require_once '../../../vendor/autoload.php';
require_once '../../../api/utils/sendEmail.php';

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
        // Tips sécu : on renvoie un 200 pour ne pas révéler les emails existants
        http_response_code(200);
        echo json_encode(["message" => "Si l'email existe, un lien vous a été envoyé."]);
        exit();
    }

    $secret = $_ENV['JWT_SECRET'] ?? 'secret_key';

    // Crée un token spécial reset password
    $payload = [
        "email" => $email,
        "exp" => time() + (15 * 60) // expire dans 15 minutes
    ];

    $resetToken = JWT::encode($payload, $secret, 'HS256');

    //envoi l'email
    $emailSent = sendResetEmail($email, $resetToken);

    if (!$emailSent) {
      http_response_code(500);
      echo json_encode(["error" => "Erreur lors de l'envoi de l'email."]);
      exit();
    }

    http_response_code(200);
    echo json_encode(["message" => "Si l'email existe, un lien vous a été envoyé."]);
} catch (Exception $e) {
    error_log("Erreur forgotPassword.php : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}

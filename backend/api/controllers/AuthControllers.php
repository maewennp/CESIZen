<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthControllers
{
    private $userModel;
    private $jwtSecret;

    public function __construct($db)
    {
        $this->userModel = new User($db);
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'secret_key';
    }

    public function login($data)
    {
        if  (!isset($data['email'], $data['password'])) {
          return ["error" => "Email et mot de passe requis"];
        }

        // Vérifie si l'email est valide et si le mot de passe n'est pas vide
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || empty($data['password'])) {
          return ["error" => "Format de données invalide"];
        }

        $user = $this->userModel->getUserByEmail($data['email']);

        if (!$user) {
          return ["error" => "Utilisateur non trouvé"];
        }

        if (!password_verify($data['password'], $user['password'])) {
          var_dump($data['password'], $user['password']);
          error_log("Échec de la vérification du mot de passe pour : " . $data['email']);
          return ["error" => "Mot de passe incorrect"];
        }

        if (isset($user['is_active']) && !$user['is_active']) {
          return ["error" => "Compte désactivé. Contactez un administrateur."];
        }

        $payload = [
            'iss' => 'CESIZen',
            'iat' => time(),
            "exp" => time() + 3600 * 24, // 1 jour
            'sub' => $user['id_user'],
            'username' => $user['username'],
            'email' => $user['email'],
            'is_admin' => (bool)$user['is_admin']
        ];

        try {
          $token = JWT::encode($payload, $this->jwtSecret, 'HS256');
          return ["token" => $token];
        } catch (Exception $e) {
            error_log("Erreur JWT: " . $e->getMessage());
            return ["error" => "Erreur lors de la génération du token JWT"];
        }
    }

    public function register($data)
  {
    if (!isset($data['username'], $data['email'], $data['password'])) {
        return ["error" => "Données incomplètes"];
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return ["error" => "Email invalide"];
    }
    $is_admin = $data['is_admin'] ?? false; // facultatif dans l'inscription
    return $this->userModel->createUser($data['username'], $data['email'], $data['password'], $is_admin);
  }
}

<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsersControllers
{
    private $userModel;
    private $jwtSecret;

    public function __construct($db)
    {
        $this->userModel = new User($db);
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'secret_key';
    }

    private function checkAuth($token)
    {
        try {
            if (!$token) {
                return ["error" => "Token manquant"];
            }

            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            error_log("Erreur JWT checkAuth: " . $e->getMessage());
            return (object) ["error" => "Token invalide ou expiré"];
        }
    }

    public function getProfile($token)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) {
            return ["error" => $auth->error];
        }

        $user = $this->userModel->getUserById($auth->sub);

        if (!$user) {
            return ["error" => "Utilisateur introuvable"];
        }

        unset($user['password']);
        return ["profile" => $user];
    }

    public function adminGetAllUsers($token)
    {
        $auth = $this->checkAuth($token);

        if (isset($auth->error)) {
            return ["error" => $auth->error];
        }

        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès interdit - Admin uniquement"];
        }

        return $this->userModel->getAllUsers();
    }


    public function deleteUser($token, $id_user)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];

        // Seul l'utilisateur concerné OU un admin peut supprimer
        if ($auth->sub != $id_user && !($auth->is_admin ?? false)) {
            return ["error" => "Accès refusé"];
        }

        return $this->userModel->deleteUser($id_user);
    }

    public function updateUser($token, $id_user, $data)
    {
        // Vérifier le token
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) {
            return ["error" => $auth->error];
        }

        // Vérifier si l'utilisateur connecté est admin ou propriétaire du compte
        $isAdmin = $auth->is_admin ?? false;
        $userIdFromToken = $auth->sub ?? null;

        if (!$isAdmin && $userIdFromToken != $id_user) {
            return ["error" => "Accès refusé"];
        }

        // Protéger : si ce n'est pas un admin, empêcher de modifier is_admin
        if (!$isAdmin) {
            unset($data['is_admin']);
        }

        // Appeler la méthode du modèle
        $result = $this->userModel->updateUser($id_user, $data);

        if (isset($result['error'])) {
            return ["error" => $result['error']];
        }

        // On retourne le profil mis à jour pour le front
        $updatedUser = $this->userModel->getUserById($id_user);
        return [
            "message" => "Utilisateur mis à jour avec succès",
            "profile" => $updatedUser
        ];
    }

    public function toggleIsActiveUser($token, $id_user)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];
    
        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès réservé aux administrateurs"];
        }
    
        return $this->userModel->toggleIsActiveUser($id_user);
    }

    public function changePassword($email, $newPassword)
    {
        return $this->userModel->resetPassword($email, $newPassword);
    }

    public function createUserAsAdmin($token, $data)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];

        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès refusé"];
        }

        return $this->userModel->createUser(
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            true // admin
        );
    }

    public function getOneUser($token, $criteria)
    {
        $auth = $this->checkAuth($token);

        if (isset($auth->error)) {
            return ["error" => $auth->error];
        }

        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès interdit"];
        }

        return $this->userModel->getUser($criteria);
    }

    public function changePasswordConnected($token, $id_user, $old_password, $new_password)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) {
            return ["error" => $auth->error];
        }

        $isAdmin = $auth->is_admin ?? false;
        $userIdFromToken = $auth->sub ?? null;

        if (!$isAdmin && $userIdFromToken != $id_user) {
            return ["error" => "Accès refusé"];
        }

        // Récupérer l'utilisateur
        $user = $this->userModel->getUserByIdWithPassword($id_user);
        if (!$user) {
            return ["error" => "Utilisateur introuvable"];
        }

        // Vérifier l'ancien mot de passe (sauf admin)
        if (!$isAdmin && !password_verify($old_password, $user['password'])) {
            return ["error" => "Ancien mot de passe incorrect"];
        }

        // Mettre à jour le mot de passe
        $result = $this->userModel->updateUser($id_user, ['password' => $new_password]);
        if (isset($result['error'])) {
            return ["error" => $result['error']];
        }

        return ["message" => "Mot de passe modifié avec succès"];
    }

    public function adminCreateUser($token, $data)
{
    $auth = $this->checkAuth($token);
    if (isset($auth->error)) {
        return ["error" => $auth->error];
    }
    if (!($auth->is_admin ?? false)) {
        return ["error" => "Accès réservé aux administrateurs"];
    }

    // Appelle la méthode du modèle pour créer l'utilisateur
    $result = $this->userModel->AdminCreateUser($data);
    if (isset($result['error'])) {
        return ["error" => $result['error']];
    }

    // Retourne le profil créé
    $createdUser = $this->userModel->getUserByEmail($data['email']);
    return [
        "message" => "Utilisateur créé avec succès",
        "profile" => $createdUser
    ];
}

}

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

    public function getAllUsers() //public function getAllUsers($token)
    {
        // $auth = $this->checkAuth($token);
        // if (isset($auth->error)) return ["error" => $auth->error];

        // if (!($auth->is_admin ?? false)) {
        //     return ["error" => "Accès refusé : droits insuffisants"];
        // }

        return $this->userModel->getAllUsers();
    }

    public function deleteUser($token, $id_user)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];

        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès refusé"];
        }

        return $this->userModel->deleteUser($id_user);
    }

    public function updateUser($token, $data)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];

        return $this->userModel->updateUser($auth->sub, $data);
    }

    public function changeStatus($token, $id_user)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];

        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès refusé"];
        }

        return $this->userModel->changeStatus($id_user);
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
}

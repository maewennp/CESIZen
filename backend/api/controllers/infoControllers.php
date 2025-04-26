<?php

require_once __DIR__ . '/../models/Info.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class InfoControllers
{
    private $infoModel;
    private $jwtSecret;

    public function __construct($db)
    {
        $this->infoModel = new InfoModel($db);
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'secret_key';
    }

    public function checkAuth($token)
{
    try {
        if (!$token) {
            return (object) ["error" => "Token manquant"];
        }

        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        
        //  on transforme l'objet $decoded en tableau PHP
        $decoded_array = (array) $decoded;
        
        return (object) $decoded_array; // on retransforme en objet pour garder la même logique
    } catch (Exception $e) {
        return (object) ["error" => "Token invalide ou expiré"];
    }
}

    public function getAllVisibleInfos()
    {
        return $this->infoModel->getAllVisibleInfos();
    }

    public function getAllInfos($token)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];
        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès refusé"];
        }
        return $this->infoModel->getAllInfos();
    }

    public function adminGetAllInfos($token)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) {
            return ["error" => $auth->error];
        }

        if (!property_exists($auth, 'is_admin') || !$auth->is_admin) {
          return ["error" => "Accès réservé aux administrateurs"];
      }

        return $this->infoModel->getAllInfos();
    }

    public function getInfoById($id_content)
    {
        return $this->infoModel->getInfoById($id_content);
    }

    public function createInfo($token, $data)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) {
            return ["error" => $auth->error];
        }

        if (!($auth->is_admin ?? false)) {
            return ["error" => "Accès refusé"];
        }

        return $this->infoModel->createInfo($data); 
    }

    public function updateInfo($token, $id_content, $data)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];
        if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

        return $this->infoModel->updateInfo($id_content, $data);
    }

    public function deleteInfo($token, $id_content)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];
        if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

        $deleted = $this->infoModel->deleteInfo($id_content);

        if ($deleted) {
            return ["message" => "Information supprimée avec succès"];
        } else {
            return ["error" => "Erreur lors de la suppression"];
        }
    }

    public function toggleVisibilityInfo($token, $id_content)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];
        if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

        $result = $this->infoModel->toggleVisibility($id_content);

        if ($result) {
            return ["message" => "Visibilité modifiée avec succès"];
        } else {
            return ["error" => "Erreur lors de la modification"];
        }
    }
}

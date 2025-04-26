<?php

require_once __DIR__ . '/../models/RelaxActivity.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RelaxActivityControllers
{
    private $relaxModel;
    private $jwtSecret;

    public function __construct($db)
    {
        $this->relaxModel = new RelaxActivity($db);
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'secret_key';
    }

    private function checkAuth($token)
    {
        try {
            if (!$token) {
                return (object)["error" => "Token manquant"];
            }

            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            return (object)["error" => "Token invalide ou expiré"];
        }
    }

    // Récupérer toutes les activités visibles (pour tous)
    public function getAllActiveRelaxActivities()
    {
        return $this->relaxModel->getAllActiveRelaxActivities();
    }

    // Récupérer toutes les activités (admin)
    public function adminGetAllRelaxActivities($token)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];

        if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

        return $this->relaxModel->getAllRelaxActivities();
    }

    // Récupérer une seule activité
    public function getOneRelaxActivity($id_activity)
    {
        return $this->relaxModel->getRelaxActivityById($id_activity);
    }

    // Créer une activité (admin)
    public function createRelaxActivity($token, $data)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];
        if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

        return $this->relaxModel->createRelaxActivity($data);
    }

    // Modifier une activité (admin)
    public function updateRelaxActivity($token, $id_activity, $data)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];

        if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

        return $this->relaxModel->updateRelaxActivity($id_activity, $data);
    }

    // Supprimer une activité (admin)
    public function deleteRelaxActivity($token, $id_activity)
    {
      $auth = $this->checkAuth($token);
      if (isset($auth->error)) return ["error" => $auth->error];

      if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

      return $this->relaxModel->deleteRelaxActivity($id_activity);
    }

    // Activer / Désactiver une activité (admin)
    public function toggleActiveRelaxActivity($token, $id_activity)
    {
        $auth = $this->checkAuth($token);
        if (isset($auth->error)) return ["error" => $auth->error];
        if (!($auth->is_admin ?? false)) return ["error" => "Accès refusé"];

        return $this->relaxModel->toggleActiveRelaxActivity($id_activity);
    }
}

?>
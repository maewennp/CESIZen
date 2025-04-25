<?php
require_once '../../../database.php';
require_once '../../../api/controllers/AuthControllers.php';
require_once '../../../api/models/User.php';
require_once '../../../api/cors.php';

try {
  $db = Database::getConnection();
  $authController = new AuthControllers($db);

  $rawData = file_get_contents("php://input");
  $data = json_decode($rawData, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
      http_response_code(400);
      echo json_encode(["error" => "Format JSON invalide"]);
      exit;
  }

  $result = $authController->register($data);

  http_response_code(isset($result['error']) ? 400 : 201);
  echo json_encode($result);

} catch (Exception $e) {
  error_log("Erreur dans register.php: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(["error" => "Erreur serveur"]);
}

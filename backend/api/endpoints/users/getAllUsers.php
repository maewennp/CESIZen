<?php

require_once '../../../database.php';
require_once '../../controllers/UsersControllers.php';
require_once '../../models/User.php';
require_once '../../cors.php';

try {
    $db = Database::getConnection();
    $controller = new UsersControllers($db);

    $result = $controller->getAllUsers();

    http_response_code(200);
    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur dans getAllUsers: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}

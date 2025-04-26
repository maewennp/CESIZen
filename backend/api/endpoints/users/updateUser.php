<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/User.php';
require_once '../../controllers/UsersControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new UsersControllers($db);

    // Récupération du token dans le header Authorization
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7);
    }

    // Récupération des données envoyées
    $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input) || !isset($input['id_user'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID utilisateur et données requises"]);
        exit;
    }

    $id_user = (int)$input['id_user'];
    unset($input['id_user']); // On ne veut pas updater l'id_user 

    // Appel du controller
    $result = $controller->updateUser($token, $id_user, $input);

    if (isset($result['error'])) {
        http_response_code(403); 
    } else {
        http_response_code(200); 
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur updateUser: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}
?>

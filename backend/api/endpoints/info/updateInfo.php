<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/Info.php';
require_once '../../controllers/InfoControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new InfoControllers($db);

    // Récupération du token
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7); // Retire "Bearer "
    }

    // Récupération des données du POST
    $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input) || !isset($input['id_content'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID et données requises."]);
        exit;
    }

    $id_content = (int)$input['id_content'];
    unset($input['id_content']); // on enlève pour éviter de l'envoyer dans l'update

    // Appel au contrôleur
    $result = $controller->updateInfo($token, $id_content, $input);

    if (isset($result['error'])) {
        http_response_code(403);
    } else {
        http_response_code(200); // OK
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur updateInfo: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}

<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/RelaxActivity.php';
require_once '../../controllers/RelaxActivityControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new RelaxActivityControllers($db);

    // Récupération du token
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7); // Supprime "Bearer "
    }

    // Récupération du body JSON
    $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input) || !isset($input['id_activity'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID activité requis."]);
        exit;
    }

    $id_activity = (int)$input['id_activity'];

    $result = $controller->deleteRelaxActivity($token, $id_activity);

    if (isset($result['error'])) {
        http_response_code(403);
    } else {
        http_response_code(200); 
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur deleteRelaxActivity: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}
?>

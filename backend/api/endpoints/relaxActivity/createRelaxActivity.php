<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/RelaxActivity.php';
require_once '../../controllers/RelaxActivityControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new RelaxActivityControllers($db);

    // Récupération du token dans le header
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7); // On enlève "Bearer "
    }

    // Récupération des données POST envoyées
    $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input)) {
        http_response_code(400);
        echo json_encode(["error" => "Aucune donnée reçue."]);
        exit;
    }

    // Appel du controller
    $result = $controller->createRelaxActivity($token, $input);

    if (isset($result['error'])) {
        http_response_code(403);
    } else {
        http_response_code(201); // Created
    }

    echo json_encode($result);

} catch (Exception $e) {
    error_log("Erreur createRelaxActivity: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}
?>

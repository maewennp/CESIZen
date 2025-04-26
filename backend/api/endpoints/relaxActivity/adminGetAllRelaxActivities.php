<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/RelaxActivity.php';
require_once '../../controllers/RelaxActivityControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new RelaxActivityControllers($db);

    // Récupération du token dans les headers
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7); // Retire "Bearer "
    }

    // Appel au contrôleur
    $activities = $controller->adminGetAllRelaxActivities($token);

    if (isset($activities['error'])) {
        http_response_code(403);
    } else {
        http_response_code(200);
    }

    echo json_encode($activities);

} catch (Exception $e) {
    error_log("Erreur adminGetAllRelaxActivities: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}
?>

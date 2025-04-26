<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/RelaxActivity.php';
require_once '../../controllers/RelaxActivityControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new RelaxActivityControllers($db);

    $activities = $controller->getAllActiveRelaxActivities();

    http_response_code(200);
    echo json_encode($activities);

} catch (Exception $e) {
    error_log("Erreur getAllActiveRelaxActivities: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}
?>

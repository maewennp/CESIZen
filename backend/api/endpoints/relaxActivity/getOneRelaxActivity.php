<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/RelaxActivity.php';
require_once '../../controllers/RelaxActivityControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new RelaxActivityControllers($db);

    if (!isset($_GET['id_activity'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID activité manquant."]);
        exit;
    }

    $id_activity = (int)$_GET['id_activity'];
    $activity = $controller->getOneRelaxActivity($id_activity);

    if ($activity) {
        http_response_code(200);
        echo json_encode($activity);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Activité non trouvée."]);
    }
} catch (Exception $e) {
    error_log("Erreur getOneRelaxActivity: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur."]);
}
?>

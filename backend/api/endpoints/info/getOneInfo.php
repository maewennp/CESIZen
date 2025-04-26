<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/Info.php';
require_once '../../controllers/InfoControllers.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $db = Database::getConnection();
    $controller = new InfoControllers($db);

    if (!isset($_GET['id_content']) || !is_numeric($_GET['id_content'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID d'information manquant ou invalide"]);
        exit;
    }

    $id_content = intval($_GET['id_content']);
    $result = $controller->getInfoById($id_content);

    if (isset($result['error'])) {
        http_response_code(404);
        echo json_encode($result);
    } else {
        http_response_code(200);
        echo json_encode($result);
    }

} catch (Exception $e) {
    error_log("Erreur dans getOneInfo.php : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}

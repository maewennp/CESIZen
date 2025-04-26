<?php
require_once '../../cors.php';
require_once '../../../database.php';
require_once '../../models/Info.php';
require_once '../../controllers/InfoControllers.php';


try {
    $db = Database::getConnection();
    $infoController = new InfoControllers($db);

    $infos = $infoController->getAllVisibleInfos();

    http_response_code(200);
    echo json_encode($infos);

} catch (Exception $e) {
    error_log("Erreur dans getAllInfos.php : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
}

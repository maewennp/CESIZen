<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
// Autorisation des méthodes HTTP
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
// Autorisation des headers personnalisés
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// Autorisation des cookies / credentials
header("Access-Control-Allow-Credentials: true");
// Type de contenu par défaut
header("Content-Type: application/json; charset=UTF-8");

// Gestion des requêtes OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
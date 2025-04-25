<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../vendor/autoload.php';

try {
  $db = Database::getConnection();
  echo "✅ Connexion à la base de données réussie !";
} catch (Exception $e) {
  echo "❌ Échec de la connexion : " . $e->getMessage();
}
<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../api/models/RelaxActivity.php';
require_once __DIR__ . '/../../database.php';

class RelaxActivityTest extends TestCase
{
    private $db;
    private $relaxActivityModel;

    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->relaxActivityModel = new RelaxActivity($this->db);
    }

    // test de création d'une activité de relaxation 
    public function testCreateRelaxActivitySuccess()
    {
        $data = [
            'activity_label' => 'Exercice Test ' . uniqid(),
            'content' => 'Contenu de test pour activité',
            'category' => 'relaxation',
            'type' => 'audio',
            'media_activity' => null,
            'is_active' => 1,
        ];

        $id_activity = $this->relaxActivityModel->createRelaxActivity($data);

        $this->assertIsNumeric($id_activity);
        $this->assertGreaterThan(0, $id_activity);
    }

    public function testToggleActiveRelaxActivity()
    {
        // Créer une activité temporaire
        $data = [
            'activity_label' => 'Toggle activité ' . uniqid(),
            'content' => 'Exercice de toggle activité',
            'category' => 'respiration',
            'type' => 'texte',
            'media_activity' => null,
            'is_active' => 1,
        ];

        $id_activity = $this->relaxActivityModel->createRelaxActivity($data);

        // Toggle l'état actif/inactif
        $result = $this->relaxActivityModel->toggleActiveRelaxActivity($id_activity);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('is_active', $result);
    }
}

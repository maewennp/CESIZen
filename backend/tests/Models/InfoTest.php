<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../api/models/Info.php';
require_once __DIR__ . '/../../database.php';

class InfoTest extends TestCase
{
    private $db;
    private $infoModel;

    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->infoModel = new InfoModel($this->db);
    }

    public function testCreateInfoSuccess()
    {
        $data = [
            'content_label' => 'Test Info ' . uniqid(),
            'body' => 'Ceci est un test pour créer une info.',
            'media_content' => 'https://example.com/image.jpg',
            'visible' => true,
            'id_user' => 1,
        ];

        $result = $this->infoModel->createInfo($data);

        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertIsNumeric($result['id_content']);
    }

    public function testToggleVisibility()
    {
        // Créer d'abord une info temporaire pour pouvoir la tester
        $data = [
            'content_label' => 'Info à toggle ' . uniqid(),
            'body' => 'Contenu à basculer',
            'visible' => true,
            'id_user' => 1,
        ];

        $created = $this->infoModel->createInfo($data);
        $id_content = $created['id_content'];

        // Test du toggle de visibilité
        $result = $this->infoModel->toggleVisibility($id_content);

        $this->assertTrue($result);
    }
}

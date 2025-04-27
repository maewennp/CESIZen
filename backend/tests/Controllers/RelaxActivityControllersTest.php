<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../api/controllers/RelaxActivityControllers.php';
require_once __DIR__ . '/../../database.php';

class RelaxActivityControllersTest extends TestCase
{
    private $db;
    private $relaxController;
    private $fakeAdminToken;
    private $fakeUserToken;

    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->relaxController = new RelaxActivityControllers($this->db);

        // Simuler un token JWT valide pour ADMIN
        $this->fakeAdminToken = $this->generateFakeToken([
            'sub' => 1,
            'is_admin' => true
        ]);

        // Simuler un token JWT valide pour USER
        $this->fakeUserToken = $this->generateFakeToken([
            'sub' => 2,
            'is_admin' => false
        ]);
    }

    private function generateFakeToken(array $payload): string
    {
        $secret = $_ENV['JWT_SECRET'] ?? 'secret_key';
        return \Firebase\JWT\JWT::encode(array_merge([
            'iss' => 'CESIZen',
            'iat' => time(),
            'exp' => time() + 3600
        ], $payload), $secret, 'HS256');
    }

    public function testAdminCanGetAllRelaxActivities()
    {
        $result = $this->relaxController->adminGetAllRelaxActivities($this->fakeAdminToken);

        $this->assertIsArray($result);
        if (isset($result['error'])) {
            $this->fail('Erreur rencontrée : ' . $result['error']);
        }
        $this->assertArrayHasKey(0, $result); // Il doit y avoir au moins une activité
    }

    public function testNonAdminCannotCreateRelaxActivity()
    {
        $fakeData = [
            'activity_label' => 'Test Relaxation',
            'content' => 'Contenu test',
            'category' => 'relaxation',
            'type' => 'audio',
            'media_activity' => null,
            'is_active' => 1
        ];

        $result = $this->relaxController->createRelaxActivity($this->fakeUserToken, $fakeData);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertSame('Accès refusé', $result['error']);
    }
}

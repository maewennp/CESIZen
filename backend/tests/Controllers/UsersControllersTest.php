<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../api/controllers/UsersControllers.php';
require_once __DIR__ . '/../../database.php';

class UsersControllersTest extends TestCase
{
    private $db;
    private $usersController;
    private $fakeAdminToken;
    private $fakeUserToken;

    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->usersController = new UsersControllers($this->db);

        // Simuler un token JWT valide pour ADMIN
        $this->fakeAdminToken = $this->generateFakeToken([
            'sub' => 1,
            'is_admin' => true
        ]);

        // Simuler un token JWT valide pour USER normal
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

    public function testAdminCanGetAllUsers()
    {
        $result = $this->usersController->adminGetAllUsers($this->fakeAdminToken);

        $this->assertIsArray($result);
        if (isset($result['error'])) {
            $this->fail('Erreur rencontrée : ' . $result['error']);
        }
        $this->assertArrayHasKey(0, $result); // On attend au moins un utilisateur
    }

    public function testNonAdminCannotGetAllUsers()
    {
        $result = $this->usersController->adminGetAllUsers($this->fakeUserToken);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertSame('Accès interdit - Admin uniquement', $result['error']);
    }
}

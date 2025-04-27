<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../api/controllers/AuthControllers.php';
require_once __DIR__ . '/../../database.php';

class AuthControllersTest extends TestCase
{
    private $db;
    private $authController;

    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->authController = new AuthControllers($this->db);
    }

    public function testLoginSuccess()
    {
        // Utilisateur existant dans ta base (ex: admin@cesizen.fr / password123)
        $credentials = [
            'email' => 'admin@cesizen.fr',
            'password' => 'password123'
        ];

        $result = $this->authController->login($credentials);

        $this->assertArrayHasKey('token', $result);
        $this->assertIsString($result['token']);
    }

    public function testRegisterSuccess()
    {
        $data = [
            'username' => 'testuser_' . uniqid(),
            'email' => 'testuser_' . uniqid() . '@cesizen.fr',
            'password' => 'testpassword'
        ];

        $result = $this->authController->register($data);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('Utilisateur créé avec succès', $result['message']);
    }
}

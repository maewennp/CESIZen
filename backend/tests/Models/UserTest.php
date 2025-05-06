<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../api/models/User.php';
require_once __DIR__ . '/../../database.php';

class UserTest extends TestCase
{
    private $db;
    private $userModel;

    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->userModel = new User($this->db);
    }

    //test pour créer un user
    public function testCreateUserSuccess()
    {
        $username = 'testuser_' . uniqid();
        $email = 'test_' . uniqid() . '@cesizen.fr';
        $password = 'testpassword';

        $result = $this->userModel->createUser($username, $email, $password);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertSame('Utilisateur créé avec succès', $result['message']);
    }

    // test pour get un user par son email
    public function testGetUserByEmail()
    {
        $email = 'admin@cesizen.fr'; 
        
        $user = $this->userModel->getUserByEmail($email);

        $this->assertIsArray($user);
        $this->assertArrayHasKey('id_user', $user);
        $this->assertEquals($email, $user['email']);
    }
}

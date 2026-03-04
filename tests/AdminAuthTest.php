<?php
use PHPUnit\Framework\TestCase;

require_once 'admin/modelAdmin/modelAdmin.php';
require_once 'inc/Database.php';

class AdminAuthTest extends TestCase
{
    /**
     * Test: Successful authorization (correct login and password)
     */
    public function testVerifyUserCredentialsSuccess()
    {
        $dbMock = $this->createMock(database::class);

        // Hash the test password
        $testPassword = 'adminPassword123';
        $hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);

        // Set up Mock to return user data
        $dbMock->expects($this->once())
               ->method('getOne')
               ->willReturn([
                   'id' => 1,
                   'username' => 'AdminUser',
                   'email' => 'admin@test.com',
                   'password' => $hashedPassword,
                   'status' => 'admin'
               ]);

        $result = modelAdmin::verifyUserCredentials('admin@test.com', $testPassword, $dbMock);

        $this->assertIsArray($result);
        $this->assertEquals('AdminUser', $result['username']);
    }

    /**
     * Test: Authorization error (wrong password)
     */
    public function testVerifyUserCredentialsWrongPassword()
    {
        $dbMock = $this->createMock(database::class);

        // Hash one password and input another
        $hashedPassword = password_hash('correctPassword', PASSWORD_DEFAULT);

        $dbMock->method('getOne')->willReturn([
            'id' => 1,
            'email' => 'admin@test.com',
            'password' => $hashedPassword
        ]);

        // Try to log in with the wrong password
        $result = modelAdmin::verifyUserCredentials('admin@test.com', 'wrongPassword', $dbMock);

        $this->assertFalse($result);
    }

    /**
     * Test: Authorization error (user not found)
     */
    public function testVerifyUserCredentialsUserNotFound()
    {
        $dbMock = $this->createMock(database::class);

        // DB returns null
        $dbMock->method('getOne')->willReturn(null);

        $result = modelAdmin::verifyUserCredentials('missing@user.com', 'anyPassword', $dbMock);

        $this->assertFalse($result);
    }
}

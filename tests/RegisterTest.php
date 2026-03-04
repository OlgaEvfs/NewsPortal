<?php
use PHPUnit\Framework\TestCase;

require_once 'model/Register.php';
require_once 'inc/Database.php';

class RegisterTest extends TestCase
{
    /**
     * Test: Successful registration (Using MOCK for database)
     */
    public function testSuccessfulRegistration()
    {
        $dbMock = $this->createMock(Database::class);

        // Expect getOne to return null (email doesn't exist)
        $dbMock->method('getOne')->willReturn(null);
        
        $dbMock->expects($this->once())
               ->method('executeRun')
               ->willReturn(true);

        $result = Register::processRegistration(
            'TestUser',
            'test@example.com',
            'password123',
            'password123',
            $dbMock
        );

        $this->assertTrue($result[0]);
    }

    /**
     * Test: Password mismatch error
     */
    public function testPasswordsMustMatch()
    {
        $dbMock = $this->createMock(Database::class);

        $result = Register::processRegistration(
            'TestUser',
            'test@example.com',
            'password123',
            'different_password',
            $dbMock
        );

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Passwords do not match', $result[1]);
    }

    /**
     * Test: Invalid email error
     */
    public function testInvalidEmail()
    {
        $dbMock = $this->createMock(Database::class);
        $dbMock->expects($this->never())->method('executeRun');

        $result = Register::processRegistration(
            'TestUser',
            false,
            'password123',
            'password123',
            $dbMock
        );

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Incorrect email', $result[1]);
    }

    /**
     * Test: Email already exists error
     */
    public function testEmailAlreadyExists()
    {
        $dbMock = $this->createMock(Database::class);

        // Mock database finding an existing user
        $dbMock->method('getOne')->willReturn(['email' => 'test@example.com']);
        $dbMock->expects($this->never())->method('executeRun');

        $result = Register::processRegistration(
            'TestUser',
            'test@example.com',
            'password123',
            'password123',
            $dbMock
        );

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('This email is already registered', $result[1]);
    }
}

<?php
use PHPUnit\Framework\TestCase;

require_once 'model/Register.php';
require_once 'inc/Database.php';

class RegisterTest extends TestCase
{
    /**
     * Тест: Успешная регистрация (Используем MOCK для базы данных)
     */
    public function testSuccessfulRegistration()
    {
        $dbMock = $this->createMock(Database::class);

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
     * Тест: Ошибка при несовпадении паролей
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
        $this->assertStringContainsString('Пароли не совпадают', $result[1]);
    }

    /**
     * Тест: Ошибка при невалидном email
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
        $this->assertStringContainsString('Неправильный email', $result[1]);
    }
}

<?php
use PHPUnit\Framework\TestCase;

require_once 'admin/modelAdmin/modelAdmin.php';
require_once 'inc/Database.php';

class AdminAuthTest extends TestCase
{
    /**
     * Тест: Успешная авторизация (верные логин и пароль)
     */
    public function testVerifyUserCredentialsSuccess()
    {
        $dbMock = $this->createMock(database::class);

        // Хешируем тестовый пароль
        $testPassword = 'adminPassword123';
        $hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);

        // Настраиваем Mock, чтобы он вернул данные пользователя
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
     * Тест: Ошибка авторизации (неверный пароль)
     */
    public function testVerifyUserCredentialsWrongPassword()
    {
        $dbMock = $this->createMock(database::class);

        // Хешируем один пароль, а вводить будем другой
        $hashedPassword = password_hash('correctPassword', PASSWORD_DEFAULT);

        $dbMock->method('getOne')->willReturn([
            'id' => 1,
            'email' => 'admin@test.com',
            'password' => $hashedPassword
        ]);

        // Пытаемся зайти с неправильным паролем
        $result = modelAdmin::verifyUserCredentials('admin@test.com', 'wrongPassword', $dbMock);

        $this->assertFalse($result);
    }

    /**
     * Тест: Ошибка авторизации (пользователь не найден)
     */
    public function testVerifyUserCredentialsUserNotFound()
    {
        $dbMock = $this->createMock(database::class);

        // БД возвращает null
        $dbMock->method('getOne')->willReturn(null);

        $result = modelAdmin::verifyUserCredentials('missing@user.com', 'anyPassword', $dbMock);

        $this->assertFalse($result);
    }
}

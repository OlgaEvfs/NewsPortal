<?php
use PHPUnit\Framework\TestCase;

require_once 'model/News.php';
require_once 'inc/Database.php';

class NewsTest extends TestCase
{
    /**
     * Тест: Успешное получение списка новостей
     */
    public function testGetAllNews()
    {
        $dbMock = $this->createMock(Database::class);

        // Имитируем, что БД вернула массив из двух новостей
        $testData = [
            ['id' => 1, 'title' => 'Новость 1', 'text' => 'Текст 1'],
            ['id' => 2, 'title' => 'Новость 2', 'text' => 'Текст 2'],
        ];

        $dbMock->expects($this->once())
               ->method('getAll')
               ->with($this->stringContains('SELECT * FROM news')) // Проверяем, что SQL правильный
               ->willReturn($testData);

        $result = News::getAllNews($dbMock);

        $this->assertCount(2, $result); // Проверяем, что получили именно 2 новости
        $this->assertEquals('Новость 1', $result[0]['title']);
    }

    /**
     * Тест: Проверка безопасности (SQL Injection Prevention)
     * Мы передаем плохую строку, но ожидаем, что в SQL попадет только число
     */
    public function testGetNewsByIdSecurity()
    {
        $dbMock = $this->createMock(Database::class);

        // Передаем "злой" ID
        $evilId = "5; DROP TABLE users";

        // Ожидаем, что в метод getOne придет SQL, где id = 5 (после (int) приведения)
        $dbMock->expects($this->once())
               ->method('getOne')
               ->with($this->stringContains('id=5')) // Должно стать просто 5
               ->willReturn(['id' => 5, 'title' => 'Secure News']);

        $result = News::getNewsByID($evilId, $dbMock);
        
        $this->assertEquals(5, $result['id']);
    }

    /**
     * Тест: Если новости не существует
     */
    public function testGetNewsByIdNotFound()
    {
        $dbMock = $this->createMock(Database::class);

        // Имитируем, что БД ничего не нашла
        $dbMock->method('getOne')->willReturn(null);

        $result = News::getNewsByID(999, $dbMock);
        
        $this->assertNull($result);
    }
}

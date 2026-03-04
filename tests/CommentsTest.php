<?php
use PHPUnit\Framework\TestCase;

require_once 'model/Comments.php';
require_once 'inc/Database.php';

class CommentsTest extends TestCase
{
    /**
     * Тест: Очистка текста от опасных тегов (XSS Protection)
     */
    public function testXssProtection()
    {
        $dangerousInput = "<script>alert('hacked')</script>";
        $expectedOutput = "&lt;script&gt;alert(&#039;hacked&#039;)&lt;/script&gt;";

        $result = Comments::validateComment($dangerousInput);

        $this->assertEquals($expectedOutput, $result);
    }

    /**
     * Тест: Запрет пустого комментария
     */
    public function testEmptyCommentValidation()
    {
        $this->assertFalse(Comments::validateComment("   ")); // Пробелы
        $this->assertFalse(Comments::validateComment(""));    // Пустота
    }

    /**
     * Тест: Успешная вставка комментария
     */
    public function testInsertCommentSuccess()
    {
        $dbMock = $this->createMock(Database::class);

        // Ожидаем, что БД выполнит запрос и вернет true
        $dbMock->expects($this->once())
               ->method('executeRun')
               ->willReturn(true);

        $result = Comments::insertComment("Hello, world!", 5, $dbMock);

        $this->assertTrue($result);
    }

    /**
     * Тест: Получение количества комментариев
     */
    public function testGetCommentsCount()
    {
        $dbMock = $this->createMock(Database::class);

        // Имитируем ответ от БД (как в методе getOne)
        $dbMock->method('getOne')
               ->willReturn(['count' => 12]);

        $result = Comments::getCommentsCountByNewsID(1, $dbMock);

        $this->assertEquals(12, $result);
    }
}

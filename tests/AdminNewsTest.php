<?php
use PHPUnit\Framework\TestCase;

require_once 'admin/modelAdmin/modelAdminNews.php';
require_once 'inc/Database.php';

class AdminNewsTest extends TestCase
{
    /**
     * Тест: Успешное добавление новости
     */
    public function testProcessNewsAddSuccess()
    {
        $dbMock = $this->createMock(Database::class);

        $dbMock->expects($this->once())
               ->method('executeRun')
               ->with($this->stringContains("INSERT INTO `news`"))
               ->willReturn(true);

        $result = modelAdminNews::processNewsAdd(
            'Заголовок новости',
            'Текст новости',
            1, // ID категории
            '', // Без картинки
            $dbMock
        );

        $this->assertTrue($result);
    }

    /**
     * Тест: Ошибка при пустых полях (валидация)
     */
    public function testProcessNewsAddValidation()
    {
        $dbMock = $this->createMock(Database::class);
        
        // Ожидаем, что БД вообще не будет вызвана
        $dbMock->expects($this->never())->method('executeRun');

        // Передаем пустой заголовок
        $result = modelAdminNews::processNewsAdd('', 'Текст', 1, '', $dbMock);
        $this->assertFalse($result);
    }

    /**
     * Тест: Редактирование новости БЕЗ изменения картинки
     */
    public function testProcessNewsEditNoImage()
    {
        $dbMock = $this->createMock(Database::class);

        // Проверяем, что в SQL нет упоминания picture, если картинка пустая
        $dbMock->expects($this->once())
               ->method('executeRun')
               ->with($this->logicalAnd(
                   $this->stringContains("UPDATE `news`"),
                   $this->logicalNot($this->stringContains("`picture` ="))
               ))
               ->willReturn(true);

        $result = modelAdminNews::processNewsEdit(5, 'Новый заголовок', 'Новый текст', 2, '', $dbMock);
        $this->assertTrue($result);
    }

    /**
     * Тест: Защита от XSS в заголовке
     */
    public function testProcessNewsAddXssProtection()
    {
        $dbMock = $this->createMock(Database::class);

        // Заголовок с опасным скриптом
        $dangerousTitle = "<script>alert('XSS')</script>";
        $expectedSafeTitle = "&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;";

        $dbMock->expects($this->once())
               ->method('executeRun')
               ->with($this->stringContains($expectedSafeTitle))
               ->willReturn(true);

        modelAdminNews::processNewsAdd($dangerousTitle, 'Текст', 1, '', $dbMock);
    }

    /**
     * Тест: Удаление новости
     */
    public function testProcessNewsDelete()
    {
        $dbMock = $this->createMock(Database::class);

        $dbMock->expects($this->once())
               ->method('executeRun')
               ->with($this->stringContains("DELETE FROM `news` WHERE `news`.`id` = 10"))
               ->willReturn(true);

        $result = modelAdminNews::processNewsDelete(10, $dbMock);
        $this->assertTrue($result);
    }
}

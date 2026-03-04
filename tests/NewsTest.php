<?php
use PHPUnit\Framework\TestCase;

require_once 'model/News.php';
require_once 'inc/Database.php';

class NewsTest extends TestCase
{
    /**
     * Test: Successful retrieval of news list
     */
    public function testGetAllNews()
    {
        $dbMock = $this->createMock(Database::class);

        // Simulate that the DB returned an array of two news items
        $testData = [
            ['id' => 1, 'title' => 'News 1', 'text' => 'Text 1'],
            ['id' => 2, 'title' => 'News 2', 'text' => 'Text 2'],
        ];

        $dbMock->expects($this->once())
               ->method('getAll')
               ->with($this->stringContains('SELECT * FROM news')) // Check that the SQL is correct
               ->willReturn($testData);

        $result = News::getAllNews($dbMock);

        $this->assertCount(2, $result); // Check that exactly 2 news items were received
        $this->assertEquals('News 1', $result[0]['title']);
    }

    /**
     * Test: Security check (SQL Injection Prevention)
     * We pass a bad string but expect only a number to get into the SQL
     */
    public function testGetNewsByIdSecurity()
    {
        $dbMock = $this->createMock(Database::class);

        // Pass an "evil" ID
        $evilId = "5; DROP TABLE users";

        // Expect SQL where id = 5 (after (int) casting) to come into the getOne method
        $dbMock->expects($this->once())
               ->method('getOne')
               ->with($this->stringContains('id=5')) // Should become just 5
               ->willReturn(['id' => 5, 'title' => 'Secure News']);

        $result = News::getNewsByID($evilId, $dbMock);
        
        $this->assertEquals(5, $result['id']);
    }

    /**
     * Test: If news does not exist
     */
    public function testGetNewsByIdNotFound()
    {
        $dbMock = $this->createMock(Database::class);

        // Simulate that the DB found nothing
        $dbMock->method('getOne')->willReturn(null);

        $result = News::getNewsByID(999, $dbMock);
        
        $this->assertNull($result);
    }
}

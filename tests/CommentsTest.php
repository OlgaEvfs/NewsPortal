<?php
use PHPUnit\Framework\TestCase;

require_once 'model/Comments.php';
require_once 'inc/Database.php';

class CommentsTest extends TestCase
{
    /**
     * Test: Clearing text from dangerous tags (XSS Protection)
     */
    public function testXssProtection()
    {
        $dangerousInput = "<script>alert('hacked')</script>";
        $expectedOutput = "&lt;script&gt;alert(&#039;hacked&#039;)&lt;/script&gt;";

        $result = Comments::validateComment($dangerousInput);

        $this->assertEquals($expectedOutput, $result);
    }

    /**
     * Test: Prohibiting empty comment
     */
    public function testEmptyCommentValidation()
    {
        $this->assertFalse(Comments::validateComment("   ")); // Spaces
        $this->assertFalse(Comments::validateComment(""));    // Empty
    }

    /**
     * Test: Successful comment insertion
     */
    public function testInsertCommentSuccess()
    {
        $dbMock = $this->createMock(Database::class);

        // Expect the DB to execute the query and return true
        $dbMock->expects($this->once())
               ->method('executeRun')
               ->willReturn(true);

        $result = Comments::insertComment("Hello, world!", 5, $dbMock);

        $this->assertTrue($result);
    }

    /**
     * Test: Getting comments count
     */
    public function testGetCommentsCount()
    {
        $dbMock = $this->createMock(Database::class);

        // Simulate response from DB (as in getOne method)
        $dbMock->method('getOne')
               ->willReturn(['count' => 12]);

        $result = Comments::getCommentsCountByNewsID(1, $dbMock);

        $this->assertEquals(12, $result);
    }
}

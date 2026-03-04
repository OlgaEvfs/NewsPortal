<?php
use PHPUnit\Framework\TestCase;

require_once 'admin/modelAdmin/modelAdminNews.php';
require_once 'inc/Database.php';

class AdminNewsTest extends TestCase
{
    /**
     * Test: Successful news addition
     */
    public function testProcessNewsAddSuccess()
    {
        $dbMock = $this->createMock(Database::class);

        $dbMock->expects($this->once())
               ->method('executeRun')
               ->with($this->stringContains("INSERT INTO `news`"))
               ->willReturn(true);

        $result = modelAdminNews::processNewsAdd(
            'News title',
            'News text',
            1, // Category ID
            '', // Without image
            $dbMock
        );

        $this->assertTrue($result);
    }

    /**
     * Test: Error on empty fields (validation)
     */
    public function testProcessNewsAddValidation()
    {
        $dbMock = $this->createMock(Database::class);
        
        // Expect that the DB will not be called at all
        $dbMock->expects($this->never())->method('executeRun');

        // Pass an empty title
        $result = modelAdminNews::processNewsAdd('', 'Text', 1, '', $dbMock);
        $this->assertFalse($result);
    }

    /**
     * Test: Editing news WITHOUT changing image
     */
    public function testProcessNewsEditNoImage()
    {
        $dbMock = $this->createMock(Database::class);

        // Check that there is no mention of picture in SQL if the image is empty
        $dbMock->expects($this->once())
               ->method('executeRun')
               ->with($this->logicalAnd(
                   $this->stringContains("UPDATE `news`"),
                   $this->logicalNot($this->stringContains("`picture` ="))
               ))
               ->willReturn(true);

        $result = modelAdminNews::processNewsEdit(5, 'New title', 'New text', 2, '', $dbMock);
        $this->assertTrue($result);
    }

    /**
     * Test: XSS protection in title
     */
    public function testProcessNewsAddXssProtection()
    {
        $dbMock = $this->createMock(Database::class);

        // Title with dangerous script
        $dangerousTitle = "<script>alert('XSS')</script>";
        $expectedSafeTitle = "&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;";

        $dbMock->expects($this->once())
               ->method('executeRun')
               ->with($this->stringContains($expectedSafeTitle))
               ->willReturn(true);

        modelAdminNews::processNewsAdd($dangerousTitle, 'Text', 1, '', $dbMock);
    }

    /**
     * Test: News deletion
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

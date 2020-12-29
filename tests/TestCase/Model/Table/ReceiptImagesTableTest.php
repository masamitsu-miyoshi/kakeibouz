<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceiptImagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceiptImagesTable Test Case
 */
class ReceiptImagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceiptImagesTable
     */
    protected $ReceiptImages;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ReceiptImages',
        'app.Payments',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ReceiptImages') ? [] : ['className' => ReceiptImagesTable::class];
        $this->ReceiptImages = TableRegistry::getTableLocator()->get('ReceiptImages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ReceiptImages);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PaymentCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PaymentCategoriesTable Test Case
 */
class PaymentCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PaymentCategoriesTable
     */
    protected $PaymentCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PaymentCategories',
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
        $config = TableRegistry::getTableLocator()->exists('PaymentCategories') ? [] : ['className' => PaymentCategoriesTable::class];
        $this->PaymentCategories = TableRegistry::getTableLocator()->get('PaymentCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PaymentCategories);

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
}

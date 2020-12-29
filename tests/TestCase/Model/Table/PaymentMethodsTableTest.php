<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PaymentMethodsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PaymentMethodsTable Test Case
 */
class PaymentMethodsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PaymentMethodsTable
     */
    protected $PaymentMethods;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PaymentMethods',
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
        $config = TableRegistry::getTableLocator()->exists('PaymentMethods') ? [] : ['className' => PaymentMethodsTable::class];
        $this->PaymentMethods = TableRegistry::getTableLocator()->get('PaymentMethods', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PaymentMethods);

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

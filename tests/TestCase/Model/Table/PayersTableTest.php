<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PayersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PayersTable Test Case
 */
class PayersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PayersTable
     */
    protected $Payers;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Payers',
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
        $config = TableRegistry::getTableLocator()->exists('Payers') ? [] : ['className' => PayersTable::class];
        $this->Payers = TableRegistry::getTableLocator()->get('Payers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Payers);

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

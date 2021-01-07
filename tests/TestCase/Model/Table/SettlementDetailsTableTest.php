<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DebitsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DebitsTable Test Case
 */
class SettlementDetailsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DebitsTable
     */
    protected $SettlementDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.SettlementDetails',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SettlementDetails') ? [] : ['className' => DebitsTable::class];
        $this->SettlementDetails = $this->getTableLocator()->get('SettlementDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SettlementDetails);

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

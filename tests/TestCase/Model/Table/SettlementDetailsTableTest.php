<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SettlementDetailsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SettlementDetailsTable Test Case
 */
class SettlementDetailsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SettlementDetailsTable
     */
    protected $SettlementDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.SettlementDetails',
        'app.Settlements',
        'app.Payments',
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
        $config = $this->getTableLocator()->exists('SettlementDetails') ? [] : ['className' => SettlementDetailsTable::class];
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

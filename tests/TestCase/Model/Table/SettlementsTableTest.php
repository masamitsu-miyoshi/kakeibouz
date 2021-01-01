<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SettlementsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SettlementsTable Test Case
 */
class SettlementsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SettlementsTable
     */
    protected $Settlements;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Settlements',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Settlements') ? [] : ['className' => SettlementsTable::class];
        $this->Settlements = $this->getTableLocator()->get('Settlements', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Settlements);

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

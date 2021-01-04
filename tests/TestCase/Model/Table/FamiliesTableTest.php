<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FamiliesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FamiliesTable Test Case
 */
class FamiliesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FamiliesTable
     */
    protected $Families;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Families',
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
        $config = $this->getTableLocator()->exists('Families') ? [] : ['className' => FamiliesTable::class];
        $this->Families = $this->getTableLocator()->get('Families', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Families);

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

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShippingTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShippingTable Test Case
 */
class ShippingTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ShippingTable
     */
    public $Shipping;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Shipping',
        'app.Items',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Shipping') ? [] : ['className' => ShippingTable::class];
        $this->Shipping = TableRegistry::getTableLocator()->get('Shipping', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Shipping);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

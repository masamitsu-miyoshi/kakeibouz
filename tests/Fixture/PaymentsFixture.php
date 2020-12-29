<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PaymentsFixture
 */
class PaymentsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '支払日', 'precision' => null],
        'payment_method_id' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '支払方法', 'precision' => null],
        'cost_category_id' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => 'カテゴリ', 'precision' => null],
        'product_name' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '但し書き', 'precision' => null],
        'store_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'payer_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '立替人', 'precision' => null, 'autoIncrement' => null],
        'amount' => ['type' => 'decimal', 'length' => 10, 'precision' => 0, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '金額'],
        'receipt_file' => ['type' => 'binary', 'length' => 16777215, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'date' => '2020-04-06',
                'payment_method_id' => 'Lorem ipsum dolor ',
                'cost_category_id' => 'Lorem ipsum dolor ',
                'product_name' => 'Lorem ipsum dolor sit amet',
                'store_id' => 1,
                'payer_id' => 1,
                'amount' => 1.5,
                'receipt_file' => 'Lorem ipsum dolor sit amet',
                'created' => '2020-04-06 15:01:49',
                'modified' => '2020-04-06 15:01:49',
            ],
        ];
        parent::init();
    }
}

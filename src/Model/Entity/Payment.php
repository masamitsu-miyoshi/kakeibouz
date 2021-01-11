<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payment Entity
 *
 * @property int $id
 * @property int $family_id
 * @property \Cake\I18n\FrozenDate|null $date
 * @property string|null $payment_method_id
 * @property string|null $cost_category_id
 * @property string $product_name
 * @property int|null $store_id
 * @property int|null $payer_id
 * @property string $amount
 * @property string|resource|null $receipt_file
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\PaymentMethod $payment_method
 * @property \App\Model\Entity\CostCategory $cost_category
 * @property \App\Model\Entity\Store $store
 * @property \App\Model\Entity\Payer $payer
 * @property \App\Model\Entity\ReceiptImage $receipt_image
 */
class Payment extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'date' => true,
        'payment_method_id' => true,
        'cost_category_id' => true,
        'product_name' => true,
        'store_id' => true,
        'paid_user_id' => true,
        'amount' => true,
        'private_amount' => true,
        'billed_user_id' => true,
    ];
}

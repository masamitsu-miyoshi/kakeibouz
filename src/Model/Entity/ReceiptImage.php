<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReceiptImage Entity
 *
 * @property int $id
 * @property int|null $payment_id
 * @property string|null $name
 * @property string|null $media_type
 * @property string|resource|null $data
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Payment $payment
 */
class ReceiptImage extends Entity
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
        'payment_id' => true,
        'name' => true,
        'media_type' => true,
        'data' => true,
        'created' => true,
        'modified' => true,
        'payment' => true,
    ];
}

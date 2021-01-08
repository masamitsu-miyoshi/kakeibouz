<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bill Entity
 *
 * @property int $id
 * @property int|null $book_id
 * @property int|null $payment_id
 * @property int|null $user_id
 * @property string|null $bill_rate
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Book $book
 * @property \App\Model\Entity\Payment $payment
 * @property \App\Model\Entity\User $user
 */
class Bill extends Entity
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
        'book_id' => true,
        'payment_id' => true,
        'user_id' => true,
        'bill_rate' => true,
        'created' => true,
        'modified' => true,
        'book' => true,
        'payment' => true,
        'user' => true,
    ];
}

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PaymentMethod[]|\Cake\Collection\CollectionInterface $paymentMethods
 */
?>
<div class="paymentMethods index content">
    <?= $this->Html->link(__('New Payment Method'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Payment Methods') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paymentMethods as $paymentMethod): ?>
                <tr>
                    <td><?= $this->Number->format($paymentMethod->id) ?></td>
                    <td><?= h($paymentMethod->name) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $paymentMethod->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $paymentMethod->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $paymentMethod->id], ['confirm' => __('Are you sure you want to delete # {0}?', $paymentMethod->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>

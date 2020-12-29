<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ReceiptImage[]|\Cake\Collection\CollectionInterface $receiptImages
 */
?>
<div class="files index content">
    <?= $this->Html->link(__('New File'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('ReceiptImages') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('media_type') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receiptImages as $receiptImage): ?>
                <tr>
                    <td><?= $this->Number->format($receiptImage->id) ?></td>
                    <td><?= h($receiptImage->name) ?></td>
                    <td><?= h($receiptImage->media_type) ?></td>
                    <td><?= h($receiptImage->created) ?></td>
                    <td><?= h($receiptImage->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $receiptImage->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $receiptImage->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $receiptImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $receiptImage->id)]) ?>
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

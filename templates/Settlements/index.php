<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Settlement[]|\Cake\Collection\CollectionInterface $settlements
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="settlements index content">
    <?= $this->Html->link(__('月末締め処理'), ['action' => 'create'], ['class' => 'button float-right']) ?>
    <h3><?= __('月末締め') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('code') ?></th>
                    <?php foreach ($users as $userId => $userCode):?>
                        <th><?= $userCode ?></th>
                    <?php endforeach; ?>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settlements as $settlement): ?>
                <tr>
                    <td><?= h($settlement->code) ?></td>
                    <?php foreach ($users as $userId => $userCode):?>
                        <td><?= $this->Number->currency(collection($settlement->debits)->firstMatch(['user_id' => $userId])->amount) ?></td>
                    <?php endforeach; ?>
                    <td><?= h($settlement->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $settlement->id]) ?>
                        <?= $this->Html->link(__('Download'), ['action' => 'download', $settlement->id]) ?>
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

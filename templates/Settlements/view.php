<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Settlement $settlement
 * @var \App\Model\Entity\User[] $users
 */

?>
<div class="settlements view content">
    <?= $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $settlement->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $settlement->code), 'class' => 'button float-right']
    ) ?>
    <h3><?= h($settlement->code) ?></h3>
    <div class="related">
        <h4><?= __('決済') ?></h4>
        <?php if (!empty($settlement->debits)) : ?>
            <div class="table-responsive">
                <table>
                    <tr>
                        <th><?= __('User') ?></th>
                        <th><?= __('Billed') ?></th>
                        <th><?= __('Paid') ?></th>
                        <th><?= __('Debit') ?></th>
                    </tr>
                    <?php foreach ($settlement->debits as $debit) : ?>
                        <tr>
                            <td><?= h($users[$debit->user_id]) ?></td>
                            <td><?= $this->Number->currency($debit->billed_amount) ?></td>
                            <td><?= $this->Number->currency($debit->paid_amount) ?></td>
                            <td><?= $this->Number->currency($debit->amount) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php foreach ($settlement->debits as $debit) : ?>
        <div class="related">
            <h4><?= __('Bill to {0}', $users[$debit->user_id]) ?></h4>
            <?php if (!empty($debit->bills)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Payment') ?></th>
                            <th><?= __('Rate') ?></th>
                            <th><?= __('Bill Amount') ?></th>
                        </tr>
                        <?php foreach ($debit->bills as $bill) : ?>
                            <tr>
                                <td><?= $this->Html->link('P' . $bill->payment_id, ['controller' => 'payments', 'action' => 'edit', $bill->payment_id, '?' => ['ref' => $this->request->getUri()->getPath()]]) ?></td>
                                <td><?= h($bill->rate) ?></td>
                                <td><?= $this->Number->currency($bill->amount) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

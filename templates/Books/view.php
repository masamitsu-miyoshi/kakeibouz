<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Book $book
 * @var \App\Model\Entity\User[] $users
 */

?>
<div class="books view content">
    <?= $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $book->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $book->code), 'class' => 'button float-right']
    ) ?>
    <h3><?= h($book->code) ?></h3>
    <div class="related">
        <h4><?= __('決済') ?></h4>
        <?php if (!empty($book->settlements)) : ?>
            <div class="table-responsive">
                <table>
                    <tr>
                        <th><?= __('User') ?></th>
                        <th><?= __('Billed') ?></th>
                        <th><?= __('Paid') ?></th>
                        <th><?= __('Settlement') ?></th>
                    </tr>
                    <?php foreach ($book->settlements as $settlement) : ?>
                        <tr>
                            <td><?= h($users[$settlement->user_id]) ?></td>
                            <td><?= $this->Number->currency($settlement->billed_amount) ?></td>
                            <td><?= $this->Number->currency($settlement->paid_amount) ?></td>
                            <td><?= $this->Number->currency($settlement->amount) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php foreach ($book->settlements as $settlement) : ?>
        <div class="related">
            <h4><?= __('Bill to {0}', $users[$settlement->user_id]) ?></h4>
            <?php if (!empty($settlement->bills)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Payment') ?></th>
                            <th><?= __('Rate') ?></th>
                            <th><?= __('Bill Amount') ?></th>
                        </tr>
                        <?php foreach ($settlement->bills as $bill) : ?>
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

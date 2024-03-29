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
                            <th><?= __('支払番号') ?></th>
                            <th><?= __('支払内容') ?></th>
                            <th><?= __('支払日') ?></th>
                            <th><?= __('請求割合') ?></th>
                            <th><?= __('請求金額') ?></th>
                        </tr>
                        <?php foreach ($settlement->bills as $bill) : ?>
                            <tr>
                                <td><?= $this->Html->link('P' . $bill->payment_id, ['controller' => 'payments', 'action' => 'edit', $bill->payment_id, '?' => ['ref' => $this->request->getUri()->getPath()]]) ?></td>
                                <td><?= h($bill->payment->product_name) ?></td>
                                <td><?= h($bill->payment->date ? $bill->payment->date->i18nFormat('M/d') : '-') ?></td>
                                <td class="<?php if ($bill->rate == 1): ?>important<?php endif; ?>"><?= h($bill->rate) ?></td>
                                <td><?= $this->Number->currency($bill->amount) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

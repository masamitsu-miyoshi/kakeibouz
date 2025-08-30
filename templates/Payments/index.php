<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payment[]|\Cake\Collection\CollectionInterface $payments
 */

$paymentsByDate = $payments->groupBy(function ($payment) {
    return $payment->date ? $payment->date->i18nFormat('yyyy-MM-dd') : '0000-00-00';
});

$daysInMonth = [];
$date = $dateFrom;
while ($date < $dateTo):
    $daysInMonth[] = $date->i18nFormat('yyyy-MM-dd');
    $date = $date->addDay();
endwhile;

?>
<div class="payments index content">
    <div>
        <?= $this->Html->link('👈' . __('Prev'), '/payments/' . $dateFrom->subMonth(1)->i18nFormat('yyyy/MM'), ['class' => 'float-left']) ?>
        <?= $this->Html->link(__('Next') . '👉', '/payments/' . $dateFrom->addMonth(1)->i18nFormat('yyyy/MM'), ['class' => 'float-right']) ?>
    </div>
    <h3 style="text-align: center;"><?= $this->Html->link($dateFrom->i18nFormat(' yyyy-MM'), ['year' => $dateFrom->i18nFormat('yyyy'), 'month' => $dateFrom->i18nFormat('MM')]) ?></h3>
    <?= $this->Html->link(__('新規支払'), ['action' => 'edit', '?' => ['ref' => $this->request->getUri()->getPath(), 'date' => $dateFrom->i18nFormat('yyyy-MM-01')]], ['class' => 'button float-right']) ?>
    <div class="sum">
        <dl>
            <?php foreach ($totalPaymentsByPayer as $payerId => $totalPayment): ?>
            <dt><?= $this->Html->link($totalPayment->payer_name, ['year' => $dateFrom->i18nFormat('yyyy'), 'month' => $dateFrom->i18nFormat('MM'), '?' => ['payer_id' => $payerId]]) ?></dt>
            <dd><?= $this->Number->currency($totalPayment->payment_amount) ?></dd>
            <?php endforeach; ?>
        </dl>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>📅<span class="label"><?= __('日付') ?></span></th>
                    <th>💳<span class="label"><?= __('支払方法') ?></span></th>
                    <th>🗂<span class="label"><?= __('カテゴリ') ?></span></th>
                    <th>🏷<span class="label"><?= __('支払内容') ?></span></th>
                    <th>🏬<span class="label"><?= __('支払先') ?></span></th>
                    <th>💰<span class="label"><?= __('金額') ?></span></th>
                    <th>👥<span class="label"><?= __('支払人') ?></span></th>
                    <th>👤<span class="label"><?= __('請求宛') ?></span></th>
                    <th class="actions">✏<span class="label"><?= __('編集') ?></span></th>
                </tr>
            </thead>
                <?php foreach ($daysInMonth as $date): ?>
                <tbody class="<?= $date ?>">
                    <?php $records = $paymentsByDate->get($date); ?>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td><?= $date ?></td>
                            <td>empty</td>
                            <td><?php var_dump($records); ?> </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td><?= $date ?></td>
                            <td>YES</td>
                            <td><?php var_dump($records); ?> </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php endforeach; ?>
                
                <?php foreach ($paymentsByDate as $date => $records): ?>
                <tbody class="<?= $date ?>">
                    <?php foreach ($records as $index => $payment): ?>
                    <tr class="<?= $index % 2 === 0 ? 'even' : 'odd' ?>">
                        <?php if ($index === 0): ?><td rowspan="<?= count($records) ?>"><?= h($payment->date ? $payment->date->i18nFormat('M/d(eee)') : '-') ?></td><?php endif; ?>
                        <td><?= h($paymentMethods[$payment->payment_method_id] ?? '-') ?></td>
                        <td><?= h($costCategories[$payment->cost_category_id] ?? '-') ?></td>
                        <td><?= h($payment->product_name) ?? '-' ?></td>
                        <td><?= h($stores[$payment->store_id] ?? '-') ?></td>
                        <td><?= $this->Number->currency($payment->amount - $payment->private_amount) ?></td>
                        <td><?= h($users[$payment->paid_user_id] ?? '-') ?></td>
                        <td><?= h($users[$payment->billed_user_id] ?? __('ALL')) ?></td>
                        <td class="actions"><?php
                            echo $this->Html->link('P' . $payment->id, ['action' => 'edit', $payment->id, '?' => ['ref' => $this->request->getUri()->getPath()]]);
                            if ($payment->book_id) {
                                echo ' ' . $this->Html->link('✅', ['controller' => 'books', 'action' => 'view', $payment->book_id, '?' => ['ref' => $this->request->getUri()->getPath()]]);
                            }
                            ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php endforeach; ?>
        </table>
    </div>
</div>

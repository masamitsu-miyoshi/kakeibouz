<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payment[]|\Cake\Collection\CollectionInterface $payments
 */
?>
<div class="payments index content">
    <div>
        <?= $this->Html->link('👈' . __('Prev'), '/payments/' . $dateFrom->subMonth(1)->i18nFormat('yyyy/MM'), ['class' => 'float-left']) ?>
        <?= $this->Html->link(__('Next') . '👉', '/payments/' . $dateFrom->addMonth(1)->i18nFormat('yyyy/MM'), ['class' => 'float-right']) ?>
    </div>
    <h3 style="text-align: center;"><?= $this->Html->link($dateFrom->i18nFormat(' yyyy-MM'), ['year' => $dateFrom->i18nFormat('yyyy'), 'month' => $dateFrom->i18nFormat('MM')]) ?></h3>
    <?= $this->Html->link(__('新規支払'), ['action' => 'edit', '?' => ['ref' => $this->request->getUri()->getPath()]], ['class' => 'button float-right']) ?>
    <div class="sum">
        <dl>
            <?php foreach ($totalPaymentsByPayer as $payerId => $totalPayment): ?>
            <dt><?= $this->Html->link($totalPayment->payer_name, ['year' => $dateFrom->i18nFormat('yyyy'), 'month' => $dateFrom->i18nFormat('MM'), '?' => ['payer_id' => $payerId]]) ?></dt>
            <dd><?= $this->Number->format($totalPayment->payment_amount) ?></dd>
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
                    <th>🏷<span class="label"><?= __('商品名') ?></span></th>
                    <th>🏬<span class="label"><?= __('支払先') ?></span></th>
                    <th>👥<span class="label"><?= __('立替人') ?></span></th>
                    <th>💰<span class="label"><?= __('金額') ?></span></th>
                    <th>🧾<span class="label"><?= __('レシート') ?></span></th>
                    <th class="actions">✏<span class="label"><?= __('編集') ?></span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= h($payment->date ? $payment->date->i18nFormat('M/d') : '-') ?></td>
                    <td><?= h($paymentMethods[$payment->payment_method_id] ?? '-') ?></td>
                    <td><?= h($costCategories[$payment->cost_category_id] ?? '-') ?></td>
                    <td><?= h($payment->product_name) ?? '-' ?></td>
                    <td><?= h($stores[$payment->store_id] ?? '-') ?></td>
                    <td><?= h($payers[$payment->payer_id] ?? '-') ?></td>
                    <td><?= $this->Number->format($payment->amount - $payment->private_amount) ?></td>
                    <td><?= $payment->receipt_image_id ? $this->Html->link('あり', ['action' => 'view', $payment->id, '?' => ['action' => 'view', $payment->id]]) : '-' ?></td>
                    <td class="actions"><?php
                        if (empty($payment->cutoff_date)) {
                            echo $this->Html->link('P' . $payment->id, ['action' => 'edit', $payment->id, '?' => ['ref' => $this->request->getUri()->getPath()]]);
                        } else {
                            echo 'P' . $payment->id . ' ✅';
                        }
                        ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

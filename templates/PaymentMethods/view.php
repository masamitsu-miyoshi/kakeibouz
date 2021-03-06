<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PaymentMethod $paymentMethod
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Payment Method'), ['action' => 'edit', $paymentMethod->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Payment Method'), ['action' => 'delete', $paymentMethod->id], ['confirm' => __('Are you sure you want to delete # {0}?', $paymentMethod->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Payment Methods'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Payment Method'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="paymentMethods view content">
            <h3><?= h($paymentMethod->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($paymentMethod->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($paymentMethod->id) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Payments') ?></h4>
                <?php if (!empty($paymentMethod->payments)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Date') ?></th>
                            <th><?= __('Payment Method Id') ?></th>
                            <th><?= __('Payment Category Id') ?></th>
                            <th><?= __('Store Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Payer Id') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($paymentMethod->payments as $payments) : ?>
                        <tr>
                            <td><?= h($payments->id) ?></td>
                            <td><?= h($payments->date) ?></td>
                            <td><?= h($payments->payment_method_id) ?></td>
                            <td><?= h($payments->payment_category_id) ?></td>
                            <td><?= h($payments->store_id) ?></td>
                            <td><?= h($payments->name) ?></td>
                            <td><?= h($payments->payer_id) ?></td>
                            <td><?= h($payments->amount) ?></td>
                            <td><?= h($payments->created) ?></td>
                            <td><?= h($payments->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Payments', 'action' => 'view', $payments->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Payments', 'action' => 'edit', $payments->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Payments', 'action' => 'delete', $payments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payments->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

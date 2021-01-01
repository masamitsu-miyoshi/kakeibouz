<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Settlement $settlement
 */
?>
        <div class="settlements view content">
            <?= $this->Html->link(__('Delete'), ['action' => 'delete'], ['class' => 'button float-right']) ?>
            <h3><?= h($settlement->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Code') ?></th>
                    <td><?= h($settlement->code) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($settlement->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($settlement->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($settlement->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Payments') ?></h4>
                <?php if (!empty($settlement->payments)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Receipt Image Id') ?></th>
                            <th><?= __('Date') ?></th>
                            <th><?= __('Payment Method Id') ?></th>
                            <th><?= __('Cost Category Id') ?></th>
                            <th><?= __('Product Name') ?></th>
                            <th><?= __('Store Id') ?></th>
                            <th><?= __('Payer Id') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Private Amount') ?></th>
                            <th><?= __('Cutoff Date') ?></th>
                            <th><?= __('Settlement Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($settlement->payments as $payments) : ?>
                        <tr>
                            <td><?= h($payments->id) ?></td>
                            <td><?= h($payments->receipt_image_id) ?></td>
                            <td><?= h($payments->date) ?></td>
                            <td><?= h($payments->payment_method_id) ?></td>
                            <td><?= h($payments->cost_category_id) ?></td>
                            <td><?= h($payments->product_name) ?></td>
                            <td><?= h($payments->store_id) ?></td>
                            <td><?= h($payments->payer_id) ?></td>
                            <td><?= h($payments->amount) ?></td>
                            <td><?= h($payments->private_amount) ?></td>
                            <td><?= h($payments->cutoff_date) ?></td>
                            <td><?= h($payments->settlement_id) ?></td>
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

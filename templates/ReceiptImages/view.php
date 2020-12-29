<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ReceiptImage $receiptImage
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit File'), ['action' => 'edit', $receiptImage->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete File'), ['action' => 'delete', $receiptImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $receiptImage->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List ReceiptImages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New File'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="files view content">
            <h3><?= h($receiptImage->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($receiptImage->name) ?></td>
                </tr>
                <tr>
                    <th></th>
                    <td><img src="data:<?= $receiptImage->media_type ?>;base64,<?= base64_encode(stream_get_contents($receiptImage->data)) ?>"></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($receiptImage->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($receiptImage->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

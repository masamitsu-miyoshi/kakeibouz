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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $receiptImage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $receiptImage->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List ReceiptImages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="files form content">
            <?= $this->Form->create($receiptImage, ['type'=>'file']) ?>
            <fieldset>
                <legend><?= __('Edit File') ?></legend>
                <?php
                    echo $this->Form->control('data');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

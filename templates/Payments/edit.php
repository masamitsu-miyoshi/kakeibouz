<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payment $payment
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('一覧へ戻る'), $ref ?? '/payments/') ?>
            <?= $this->Form->postLink(
                __('複製する'),
                ['action' => 'duplicate', $payment->id],
                ['confirm' => __('Are you sure you want to duplicate # {0}?', $payment->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Form->postLink(
                __('削除する'),
                ['action' => 'delete', $payment->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $payment->id), 'class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="payments form content">
            <?= $this->Form->create($payment, ['type'=>'file']) ?>
            <fieldset>
                <legend><?php
                    if ($payment->id) {
                        echo __('編集 {0}',  'P' . $payment->id);

                    } else {
                        echo __('新規');
                    }

                    ?></legend>
                <?php
                    echo $this->Form->control('date', ['label' => __('📅支払日'), 'empty' => true]);
                    echo $this->Form->control('payment_method_id', ['label' => __('💳支払方法'), 'type' => 'radio']);
                    echo $this->Form->control('cost_category_id', ['label' => __('🗂カテゴリ'), 'empty' => true]);
                    echo $this->Form->control('store_id', ['label' => __('🏬支払先'), 'empty' => true]);
                    echo $this->Form->control('product_name', ['label' => __('🏷商品名'), 'inputmode'=> 'kana']);
                    echo $this->Form->control('payer_id', ['label' => __('👥立替人'), 'type' => 'radio']);
                    echo $this->Form->control('amount', ['label' => __('💰支払金額'), 'default' => '', 'inputmode'=> 'numeric']);
                    echo $this->Form->control('private_amount', ['label' => __('除外金額'), 'default' => '', 'inputmode'=> 'numeric']);
                    echo '🧾レシート';
                    echo $this->Form->file('_receipt_image');
                    //echo $this->Html->image($payment->receipt_file);
                ?>
            </fieldset>
            <?= $this->Form->button(__('save')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payment $payment
 */
?>
<div class="payments form content">
    <div>
        <?= $this->Html->link('👈' . __('戻る'), $ref ?? '/payments/') ?>
    </div>
    <?= $payment->id ? $this->Form->postLink(
        __('削除'),
        ['action' => 'delete', $payment->id],
        ['confirm' => __('削除しますか？ # {0}?', $payment->id), 'class' => 'button float-right']
    ) : '' ?>
    <div>
    <h3 style="text-align: center;"><?php
                if ($payment->id) {
                    echo __('編集 {0}',  'P' . $payment->id);
                } else {
                    echo __('新規');
                }
                ?></h3>
        <?= $this->Form->create($payment) ?>
        <fieldset>
            <?php
                echo $this->Form->control('date', ['label' => __('📅支払日'), 'empty' => true]);
                echo $this->Form->control('payment_method_id', ['label' => __('💳支払方法'), 'type' => 'radio']);
                echo $this->Form->control('cost_category_id', ['label' => __('🗂カテゴリ'), 'empty' => true]);
                echo $this->Form->control('store_id', ['label' => __('🏬支払先'), 'empty' => true]);
                echo $this->Form->control('product_name', ['label' => __('🏷支払内容'), 'inputmode'=> 'kana']);
                echo $this->Form->control('paid_user_id', ['label' => __('👥支払人'), 'options' => $users, 'disabled' => !empty($payment->book_id)]);
                echo $this->Form->control('amount', ['label' => __('💰支払金額'), 'default' => '', 'inputmode'=> 'numeric', 'disabled' => !empty($payment->book_id)]);
                echo $this->Form->control('private_amount', ['label' => __('除外金額'), 'default' => '', 'inputmode'=> 'numeric', 'disabled' => !empty($payment->book_id)]);
                echo $this->Form->control('billed_user_id', ['label' => __('👤請求宛'), 'options' => $users, 'empty' => __('ALL'), 'disabled' => !empty($payment->book_id)]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('save')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
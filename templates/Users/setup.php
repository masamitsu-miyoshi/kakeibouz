<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <div class="column-responsive column">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Setup User') ?></legend>
                <?php
                    echo $this->Form->control('username', ['readonly' => true]);
                    echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Register')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

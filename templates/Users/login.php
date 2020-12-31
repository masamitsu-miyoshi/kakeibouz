<div class="row">
    <div class="column-responsive column">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <?php
                echo $this->Form->control('username');
                echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Login')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $costCategory
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Cost Category'), ['action' => 'edit', $costCategory->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Cost Category'), ['action' => 'delete', $costCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $costCategory->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Cost Categories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Cost Category'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="costCategories view content">
            <h3><?= h($costCategory->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($costCategory->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($costCategory->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

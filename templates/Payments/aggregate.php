<?php
/**
 * @var \App\View\AppView $this
 * @var array $payments
 * @var array $costCategories
 * @var string $year
 */

$aggregatedPayments = $payments->combine('cost_category_id', 'payment_amount', 'payment_month')->toArray();

$data = [];
$data[] = array_merge(['Year'], $costCategories);

foreach ($aggregatedPayments as $paymentMonth => $payments) {
    $row = [];
    $row[] = $paymentMonth;
    foreach ($costCategories as $costCategoryId => $costCategory) {
        $row[] = intval($payments[$costCategoryId] ?? 0);
    }
    $data[] = $row;
}

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?= json_encode($data) ?>);

        var chart = new google.visualization.LineChart(document.getElementById('chart'));

        chart.draw(data);
    }
</script>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?php foreach ($costCategories as $costCategoryId => $costCategory): ?>
                <?= $this->Html->link($costCategory, ['action' => 'aggregate', $costCategoryId], ['class' => 'side-nav-item']) ?>
            <?php endforeach; ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="payments form content">
            <div id="chart" style="width: 900px; height: 500px"></div>
        </div>
    </div>
</div>

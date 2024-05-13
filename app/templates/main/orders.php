<?php

use App\Util\AppHelper;

require AppHelper::templatePath('common/header.php')
?>

<div id="loader"><div class="loader"></div></div>
<div id="page-header">
    <div class="username"><?=$login?></div>
    <div class="logout"><button id="logout-btn" class="button">Выйти</button></div>
</div>

<table class="ui-table" id="orders-table">
    <thead>
        <tr>
            <th class="order-num">№</th>
            <th class="order-date">Создан</th>
            <th class="order-agent">Контрагент</th>
            <th class="order-organization">Орнизация</th>
            <th class="order-sum">Сумма</th>
            <th class="order-currency">Валюта</th>
            <th class="order-state">Статус</th>
            <th class="order-date">Когда изменен</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script type="application/javascript">
    const mkOrderStatesList = <?=json_encode($orderStatesList, JSON_UNESCAPED_UNICODE)?>
</script>
<?php require AppHelper::templatePath('common/footer.php') ?>

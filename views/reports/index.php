<h2>Прибыль</h2>
<? if (count($dataProvider['bank_profit'])) : ?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Месяц</th>
            <th scope="col">Сумма коммисий</th>
            <th scope="col">Сумма процентов</th>
            <th scope="col">Итог</th>
        </tr>
        </thead>
        <? foreach ($dataProvider['bank_profit'] as $date => $item) : ?>
            <tbody>
                <? $commission = $item['commission']['amount_sum'] ?? 0 ?>
                <? $percent = $item['profit']['amount_sum'] ?? 0 ?>
                <tr>
                    <th scope="row"><?= $date ?></th>
                    <th scope="row"><?= $commission ?></th>
                    <th scope="row"><?= $percent ?></th>
                    <th scope="row"><?= ($commission - $percent) ?>
                    </th>
                </tr>

            </tbody>
        <? endforeach; ?>
    </table>
<? else: ?>
    данные отсутсвуют
<? endif; ?>
<h2>Средняя сумма депозита</h2>
<h4>(Сумма депозитов/Количество депозитов) для возрастных групп.</h4>


<? $item = $dataProvider['deposit_stats'] ?>
<table class="table">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col">I группа - От 18 до 25 лет</th>
        <th scope="col">II группа - От 25 до 50 лет</th>
        <th scope="col">III группа - От 50 лет</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row">Количество депозитов</th>
        <td scope="row"><?= ($item[1]['count']) ?? 0 ?></td>
        <td scope="row"><?= ($item[2]['count']) ?? 0 ?></td>
        <td scope="row"><?= ($item[3]['count']) ?? 0 ?></td>
    </tr>
    <tr>
        <th scope="row">Сумма депозита</th>
        <td scope="row"><?= ($item[1]['amount_sum']) ?? 0 ?></td>
        <td scope="row"><?= ($item[2]['amount_sum']) ?? 0 ?></td>
        <td scope="row"><?= ($item[3]['amount_sum']) ?? 0 ?></td>
    </tr>
    <tr>
        <th scope="row">Средняя сумма депозита</th>
        <td scope="row">
            <?=
            ($item[1]['count']) ? $item[1]['amount_sum']/$item[1]['count'] : "-"
            ?>
        </td>
        <td scope="row">
            <?=
            ($item[2]['count']) ? $item[2]['amount_sum']/$item[2]['count'] : "-"
            ?>
        </td>
        <td scope="row">
            <?=
            ($item[3]['count']) ? $item[3]['amount_sum']/$item[3]['count'] : "-"
            ?>
        </td>
    </tr>

    </tbody>
</table>

課題：FC2BLOGの新着情報RSS
<hr/>

<div id="serchArea">
<?php e($form->create('Rss', aa('url', aa('action', 'index')))); ?>

<table>
    <tr>
        <th align="right">日付:</th>
        <td>
            <?php e($form->year('start', 2011, date("Y"))); ?>年
            <?php e($form->month('start', null, aa("monthNames", false))); ?>年
            <?php e($form->day('start')); ?>月
            <?php e($form->hour('start', true)); ?>時
            <?php e($form->minute('start', null, aa('interval', 10))); ?>分
            ～
            <?php e($form->year('end', 2011, date("Y"))); ?>年
            <?php e($form->month('end', null, aa("monthNames", false))); ?>年
            <?php e($form->day('end')); ?>月
            <?php e($form->hour('end', true)); ?>時
            <?php e($form->minute('end', null, aa('interval', 10))); ?>分
        </td>
    </tr>
    <tr>
        <th align="right">URL:</th>
        <td><?php e($form->text('link')); ?>（部分一致）</td>
    </tr>
    <tr>
        <th align="right">ユーザー名:</th>
        <td><?php e($form->text('username')); ?></td>
    </tr>
    <tr>
        <th align="right">サーバー番号:</th>
        <td><?php e($form->text('server_no')); ?></td>
    </tr>
    <tr>
        <th align="right">エントリーNo.:</th>
        <td>
            <?php e($form->text('entry_no')); ?>&nbsp;
            <?php e($form->checkbox('entry_no_flg')); ?>入力されたNo.以上を検索対象に含める
        </td>
    </tr>

    <tr>
        <td></td>
        <td><?php e($form->submit('検索', aa('div', false))); ?></td>
    </tr>
</table>

<?php e($form->end()); ?>
</div>

<hr/>

<div id="pageing"><?php e($this->element('pageing')); ?></div>

<table border="1">
<thead>
    <?php
        echo $html->tableHeaders(
            array(
                '日付',
                'URL',
                'タイトル',
                'description'
            ),
            aa('bgcolor', '#cccccc')
        );
    ?>
</thead>
<tbody>
    <?php
        $cells = array();
        foreach ($searchResult as $row) {
            $row = $row['Rss'];
            $cells[] = array(
                h($time->format('Y-m-d H:i:s', $row['dc_date'], '')),
                $html->link(h($row['link']), h($row['link']), aa('target', '_blank')),
                h($row['title']),
                h($row['description']),
            );
        }
        if (count($cells) > 0) {
            echo $html->tableCells($cells, a(), aa('bgcolor', '#cccccc'));
        }
    ?>
</tbody>
</table>

<div id="pageing"><?php e($this->element('pageing')); ?></div>

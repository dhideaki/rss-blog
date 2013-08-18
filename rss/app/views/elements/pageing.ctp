<?php
$separator = '&nbsp;&nbsp;';
$title = '《 最初';
$str = $paginator->first($title);
$pageing  = (empty($str) ? $title : $str) . $separator;
$pageing .= $paginator->prev('〈 前');
$pageing .= $paginator->counter(aa('format', $separator . '%start%-%end%（全 %count%件）' . $separator));
$pageing .= $paginator->next('次 〉');
$title = '最後 》';
$str = $paginator->last($title);
$pageing .= $separator . (empty($str) ? $title : $str);
echo $pageing;

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020\2\28 0028
 * Time: 0:06
 */
echo PHP_EOL . '------ start at ' . date('Y-m-d H:i:s') . ' ------' . PHP_EOL;
$list = require './all_download_list.php';
$products = ['elasticsearch', 'kibana', 'logstash'];
$OS = ['windows', 'linux'];
$limit = array(
    'maxVersion' => '7.3.2',
    'minVersion' => '7.0.0',
);
$i = 0;
foreach ($products as $pk => $pv) {
    foreach ($OS as $ok => $ov) {
        foreach ($list[$pv][$ov] as $v => $url) {
            if (strcmp($v, $limit['maxVersion']) <= 0 && strcmp($v, $limit['minVersion']) >= 0) {
                echo "$pv-$ov-$v:$url" . PHP_EOL;
            `cd d && wget --user-agent='Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36'  $url`;
            }
        }
    }
}
die("--- end at " . date('Y-m-d H:i:s') . " ---" . PHP_EOL);
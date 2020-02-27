<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 */

echo PHP_EOL . '------ start at ' . date('Y-m-d H:i:s') . ' ------' . PHP_EOL;
$url = 'https://www.elastic.co/cn/downloads/past-releases';
$baseReg = '#"url"[^"]*"(http[^"]*?%s-((\d*|\.)*)-%s[^"]*?)"#';

$products = ['elasticsearch', 'kibana'];
$OS = ['windows', 'linux', 'darwin'];

$logReg = '#"url"[^"]*"(http[^"]*?logstash-((\d*|\.)*)%s)"#';
$logOS = ['windows' => '.zip', 'linux' => '.tar.gz'];


//$page = `curl --user-agent 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36' $url`;
$page = file_get_contents('./t.html');
foreach ($products as $k => $product_name) {
    foreach ($OS as $os_key => $os_name) {
        echo "$product_name-$os_name:";
        $reg = sprintf($baseReg, $product_name, $os_name);
        $re = preg_match_all($reg, $page, $m);
        arsort($m[2]);
        $version = array_flip($m[2]);
        foreach ($version as $kv => $vv) {
            $all[$product_name][$os_name][$kv] = $m[1][$vv];
        }
        echo PHP_EOL;
    }
}

//logstash
foreach ($logOS as $k => $v) {
    echo "logstash-{$k}:";
    $reg = sprintf($logReg, $v);
    $re = preg_match_all($reg, $page, $m);
    arsort($m[2]);
    $version = array_flip($m[2]);
    foreach ($version as $kv => $vv) {
        $all['logstash'][$k][$kv] = $m[1][$vv];
    }
    echo PHP_EOL;
}
file_put_contents('./all_download_list.php', "<?php\nreturn " . var_export($all, true) . ";");
die("--- end at " . date('Y-m-d H:i:s') . " ---" . PHP_EOL);

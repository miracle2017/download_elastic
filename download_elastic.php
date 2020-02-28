<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 */

echo PHP_EOL . '------ start at ' . date('Y-m-d H:i:s') . ' ------' . PHP_EOL;
$url = 'https://www.elastic.co/cn/downloads/past-releases';
$baseReg = '#"url"[^"]*"(http[^"]*?%s-((\d*|\.)*)-%s-[^"]*?)"#';

$products = ['elasticsearch', 'kibana'];
$OS = ['windows', 'linux', 'darwin'];

$version = [];
if (file_exists('./version.log')) {
    if ($versionLogJson = file_get_contents('./version.log')) {
        try {
            $version = json_decode($versionLogJson, true);
        } catch (Exception $e) {
            var_dump($e);
            die('json_decode fail');
        }
    } else {
        var_dump(error_get_last());
        die("get version log fail");
    }
}


$page = `curl --user-agent 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36' $url`;
$updateVersion = [];
foreach ($products as $k => $product_name) {
    foreach ($OS as $os_key => $os_name) {
        echo "$product_name-$os_name:";
        $reg = sprintf($baseReg, $product_name, $os_name);
        $re = preg_match_all($reg, $page, $m);
        $currentVersion = $version[$product_name][$os_name] ?? "7.6.0";
        if (false !== ($key = array_search($currentVersion, $m[2]))) {
            $updateVersion[$product_name][$os_name] = (string)$m[2][0];
            if (empty($downloadList = array_splice($m[1], 0, $key))) {
                echo "current version is newest." . PHP_EOL;
                continue;
            }
            foreach ($downloadList as $download_key => $url) {
                `cd d && wget $url`;
            }
            var_dump($downloadList);
        } else {
            echo 'current version is not in pages';
        }
        echo PHP_EOL;
    }
}

$saveVersion = json_encode($updateVersion, JSON_UNESCAPED_UNICODE);
file_put_contents('./version.log', $saveVersion);
die("--- end at " . date('Y-m-d H:i:s') . " ---" . PHP_EOL);

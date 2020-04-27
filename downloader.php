<?php
require __DIR__ . '/vendor/autoload.php';

$github     = $argv[1] ?? '';
$distFolder = './tmp';

if (empty($github)) {
    echo "请指定要处理的github项目，比如:" . PHP_EOL;
    echo 'php downloader.php coleflowers/test' . PHP_EOL;
    exit;
}

// top-think/thinkphp
// $zip = 'https://github.com/coleflowers/test/archive/master.zip';

if (substr_count($github, 'https://github.com', 0) == false) {
    $zip = 'https://github.com/' . $github . '/archive/master.zip';
} else {
    $zip = $github;
}

echo 'Downloading:' . PHP_EOL;
echo $zip;
echo PHP_EOL;

$tmpFile = tempnam(sys_get_temp_dir(), 'guzzle-download');
$handle  = fopen($tmpFile, 'w');
echo $tmpFile . PHP_EOL;
$client = new \GuzzleHttp\Client(array(
    'base_uri'     => '',
    'verify'       => false,
    'sink'         => $tmpFile,
    'curl.options' => array(
        'CURLOPT_RETURNTRANSFER' => true,
        'CURLOPT_FILE'           => $handle,
    ),
));

$res = $client->get($zip);
echo $res->getStatusCode() . "\n";
echo $res->getHeaderLine('content-type') . "\n";
fclose($handle);

if ($res->getStatusCode() != 200) {
    echo "下载失败，请检查网络" . PHP_EOL;
    exit;
}

// 解压
$zip    = new ZipArchive;
$zipped = $zip->open($tmpFile);
$path   = $distFolder;
if ($zipped) {
    $extract = $zip->extractTo($path);
    if ($extract) {
        echo "Your file extracted to $path";
    } else {
        echo "your file not extracted";
    }
    $zip->close();
}

echo PHP_EOL;

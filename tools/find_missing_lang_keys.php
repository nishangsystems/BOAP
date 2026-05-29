<?php
$root = __DIR__ . '/../';
$langFile = $root . 'resources/lang/en/text.php';
$txt = file_get_contents($langFile);
preg_match_all('/\'([^\']+)\'\s*=>/', $txt, $m);
$existing = $m[1];
$existingSet = array_flip($existing);
$patterns = [
    '/__\(\s*\'([^\']+)\'\s*\)/',
    '/__\(\s*"([^"]+)"\s*\)/',
    '/@lang\(\s*\'([^\']+)\'\s*\)/',
    '/@lang\(\s*"([^"]+)"\s*\)/',
    '/trans\(\s*\'([^\']+)\'\s*\)/',
    '/trans\(\s*"([^"]+)"\s*\)/',
    '/trans_choice\(\s*\'([^\']+)\'/',
    '/trans_choice\(\s*"([^"]+)"/',
    '/Lang::get\(\s*\'([^\']+)\'\s*\)/',
    '/Lang::get\(\s*"([^"]+)"\s*\)/'
];

$used = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach ($it as $file) {
    $path = $file->getPathname();
    if ($file->isDir()) continue;
    if (strpos($path, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) continue;
    if (strpos($path, DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR) !== false) continue;
    if (!preg_match('/\.(php|blade\.php|js|ts)$/', $path)) continue;
    $data = @file_get_contents($path);
    if ($data === false) continue;
    foreach ($patterns as $pat) {
        if (preg_match_all($pat, $data, $mm)) {
            foreach ($mm[1] as $k) $used[$k] = true;
        }
    }
}

$used_keys = array_filter(array_keys($used), function($k){ return strpos($k, 'text.') === 0; });
$used_text_keys = [];
foreach ($used_keys as $k) {
    $parts = explode('.', $k, 2);
    if (count($parts) === 2) $used_text_keys[$parts[1]] = true;
}
$missing = array_diff(array_keys($used_text_keys), $existing);
sort($missing);
file_put_contents($root . 'tools/missing_text_keys.txt', implode("\n", $missing));
echo "EXISTING: " . count($existing) . "\n";
echo "FOUND_IN_CODE: " . count($used_text_keys) . "\n";
echo "MISSING: " . count($missing) . "\n";
foreach ($missing as $k) echo $k . "\n";
echo "WROTE tools/missing_text_keys.txt\n";
?>
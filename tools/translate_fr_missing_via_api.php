<?php
$en = include __DIR__ . '/../resources/lang/en/text.php';
$fr = include __DIR__ . '/../resources/lang/fr/text.php';
$missing = array_diff_key($en, $fr);

function sanitizePlaceholder($text) {
    return preg_replace_callback('/(:[a-zA-Z0-9_]+)/', function ($m) {
        return 'PLACEHOLDER_' . strtoupper(trim($m[1], ':')) . '_PLACEHOLDER';
    }, $text);
}

function restorePlaceholder($text) {
    return preg_replace_callback('/PLACEHOLDER_([A-Z0-9_]+)_PLACEHOLDER/', function ($m) {
        return ':' . strtolower($m[1]);
    }, $text);
}

function translatePhrase($text) {
    $temp = sanitizePlaceholder($text);
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=fr&dt=t&q=' . urlencode($temp);
    $json = @file_get_contents($url);
    if (!$json) {
        return null;
    }
    $data = json_decode($json, true);
    if (!isset($data[0][0][0])) {
        return null;
    }
    $translated = $data[0][0][0];
    $translated = trim(preg_replace('/\s+/', ' ', $translated));
    $translated = restorePlaceholder($translated);
    return $translated;
}

foreach ($missing as $key => $value) {
    $translation = translatePhrase($value);
    if ($translation === null || $translation === '') {
        $translation = $value;
    }
    $translation = addslashes($translation);
    echo "    '" . $key . "' => '" . $translation . "',\n";
}

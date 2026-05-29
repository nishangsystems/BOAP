<?php
$en = include 'resources/lang/en/text.php';
$fr = include 'resources/lang/fr/text.php';
$miss = array_diff_key($en, $fr);
ksort($miss);
foreach ($miss as $k => $v) {
    echo $k . "\t" . str_replace("\n", ' ', $v) . "\n";
}
?>

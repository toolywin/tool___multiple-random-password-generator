<?php
// popular words
include_once("../../includes/words.php");

$result = [];
foreach ($words_50_popular as $letter => $wordList) {
    shuffle($wordList);
    $result[$letter] = array_slice($wordList, 0, 5);
}
ksort($result);

header('Content-type: text/javascript');
echo 'var MEMORABLE_WORD = ';
print_r(json_encode($result));
echo ';';
exit;
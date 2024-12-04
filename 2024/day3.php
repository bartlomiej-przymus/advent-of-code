<?php
$sum = 0;
$sumWithConditions = 0;

$input = file_get_contents('input/day3.txt');
$results = [];
preg_match_all('/mul\(\d{1,3},\d{1,3}\)/', $input, $results);

foreach ($results[0] as $result) {
    $test = [];
    preg_match_all('/\d{1,3}/', $result, $test);
    $sum += $test[0][0] * $test[0][1];
}

echo "Sum of all the instructions: " . $sum . "\n";

$results2 = [];
preg_match_all("/mul\(\d{1,3},\d{1,3}\)|don't\(\)|do\(\)/", $input, $results2);
$results2 = array_values(array_filter($results2[0]));

$do = true;

foreach ($results2 as $result) {
    if ($result == "don't()") {
        $do = false;
    } elseif ($result == "do()") {
        $do = true;
    }

    if($do) {
        $test = [];
        if ($result != "do()") {
            preg_match_all('/\d{1,3}/', $result, $test);
            $sumWithConditions += $test[0][0] * $test[0][1];
        }
    }

}

echo "Sum of all the instructions after applying conditionals: " . $sumWithConditions . "\n";


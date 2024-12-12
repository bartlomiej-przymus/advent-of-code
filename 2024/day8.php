<?php

$input = ".....................................O..V.........
..................................................
................................O.........Z.......
....W....................................V....v...
........................m................8........
.....................................n........Z..v
.............F.....3...n....5m....................
................................................V.
................3............iv....Z.............V
...........................O..n..i........p......H
......W..6..............................i.........
......................................b...........
..................................n........p......
........M.......c...........m..5......1...........
...M............................L..5..A...........
...w...........9.............F5..................q
.W.....................................q....p.....
.......W........r.......H.....LA......q...........
................4.F....................A..........
........3.......a.....F...................A..L....
....ME...............................Q..........q.
.E..................ih...................Z........
................E...H...........h.................
.........m.........X..............................
..................0......C.................h......
.M......l.................Q.h.....................
..........C..............0........................
.............lX............3.c....................
......8.X.........c....r..a......H.....9..........
.................QE.....C.........................
..R................a........Q...................7.
...........................a......................
l..........X.R............1..I..........9.........
.................0R..............b.....z......x...
.......l.....w....r..........................b....
.8..........0...................P1z...............
.............c.........................L..........
.................C..N............o............9...
...........e..f..N................................
8.............................B...................
...........4...............................x......
....w....RY..........4.......................P....
.........yw.....Y.............o2...............7..
..6y........4..............fo..............7......
.........Y..6............o......................x.
.....Y....e.....y..I.r...........2................
....e.............................P.......z.bB....
.............6.................B........7......x..
..y.N........f...........1....I....z....B.........
.....e....f.............I.................2.......";

$testInput = "......#....#
...#....0...
....#0....#.
..#....0....
....0....#..
.#....A.....
...#........
#......#....
........A...
.........A..
..........#.
..........#.";


foreach(explode("\n", $input) as $row) {
    $grid[] = str_split($row);
}

foreach($grid as $rowIndex => $row) {
    foreach ($row as $columnIndex => $column) {
        if ($column != '.') {
            $antennas[$column][] = [$rowIndex, $columnIndex];
        }
    }
}

$maxRows = count($grid);
$maxCols = count($grid[0]);

function isInArea(
    array $coord,
    int $maxRows,
    int $maxCols
): bool {
    return
        $coord[0] >= 0 &&
        $coord[1] >= 0 &&
        $coord[0] < $maxRows &&
        $coord[1] < $maxCols;
}

function calculateAntinodeCoords(
    array $antennaAlphaCoords,
    array $antennaBetaCoords,
    bool $resonance = false
): array {
    $distanceInRows = abs($antennaAlphaCoords[0] - $antennaBetaCoords[0]);
    $distanceInColumns = abs($antennaAlphaCoords[1] - $antennaBetaCoords[1]);
    $antinodeCoords = [];

    if(! $resonance) {
        if ($antennaAlphaCoords[0] < $antennaBetaCoords[0]) {//first antenna is above second
            $antinodeAlphaRowCoord = $antennaAlphaCoords[0] - $distanceInRows;
            $antinodeBetaRowCoord = $antennaBetaCoords[0] + $distanceInRows;
        } elseif ($antennaAlphaCoords[0] > $antennaBetaCoords[0]) {//first antenna is below second one
            $antinodeAlphaRowCoord = $antennaAlphaCoords[0] + $distanceInRows;
            $antinodeBetaRowCoord = $antennaBetaCoords[0] - $distanceInRows;
        } elseif ($antennaAlphaCoords[0] == $antennaBetaCoords[0]) { //both antennas are on the same row
            $antinodeAlphaRowCoord = $antennaAlphaCoords[0];
            $antinodeBetaRowCoord = $antennaAlphaCoords[0];
        }

        if ($antennaAlphaCoords[1] < $antennaBetaCoords[1]) {//first antenna is to the left of second one
            $antinodeAlphaColumnCoord = $antennaAlphaCoords[1] - $distanceInColumns;
            $antinodeBetaColumnCoord = $antennaBetaCoords[1] + $distanceInColumns;
        } elseif ($antennaAlphaCoords[1] > $antennaBetaCoords[1]) { //first antenna is to the right of second one
            $antinodeAlphaColumnCoord = $antennaAlphaCoords[1] + $distanceInColumns;
            $antinodeBetaColumnCoord = $antennaBetaCoords[1] - $distanceInColumns;
        } elseif ($antennaAlphaCoords[1] == $antennaBetaCoords[1]) { // they are both on the same column
            $antinodeAlphaColumnCoord = $antennaAlphaCoords[1];
            $antinodeBetaColumnCoord = $antennaAlphaCoords[1];
        }
    } else {
//        $resonantAntinodes[] = $antennaAlphaCoords;
//        $resonantAntinodes[] = $antennaBetaCoords;
//        //part 2
//        while (isInArea($antinodeCoords, $maxRows, $maxCols)) {
//
//        }
    }


    return [
        [
            $antinodeAlphaRowCoord,
            $antinodeAlphaColumnCoord,
        ],
        [
            $antinodeBetaRowCoord,
            $antinodeBetaColumnCoord,
        ]
    ];
}

//only for test
//$expectedAntinodes = $antennas['#'];
//
//unset($antennas['#']);

function getAllPairs($inputArray) {
    $result = [];

    $len = count($inputArray);

    for ($i = 0; $i < $len - 1; $i++) {
        for ($j = $i + 1; $j < $len; $j++) {
            $result[] = [$inputArray[$i], $inputArray[$j]];
        }
    }

    return $result;
}

function doesNotExist(array $arrayOfCoords, array $input): bool {
    foreach ($arrayOfCoords as $coords) {
        if ($coords[0] === $input[0] &&
            $coords[1] === $input[1]) {

            return false;
        }
    }

    return true;
}

$antinodes = [];

foreach ($antennas as $type => $coords) {
    $countOfCoords = count($coords);

    $antennaPairs = getAllPairs($coords);

    foreach ($antennaPairs as $index => $pair) {
        $calculatedAntinodes = calculateAntinodeCoords($pair[0], $pair[1]);

        if (isInArea($calculatedAntinodes[0], $maxRows, $maxCols) && doesNotExist($antinodes, $calculatedAntinodes[0])) {
            $antinodes[] = $calculatedAntinodes[0];
        }

        if (isInArea($calculatedAntinodes[1], $maxRows, $maxCols) && doesNotExist($antinodes, $calculatedAntinodes[1])) {
            $antinodes[] = $calculatedAntinodes[1];
        }
    }
}

echo "Antinodes count: " . count($antinodes) . "\n";
//only for test
//echo "Expected antinodes count: " . count($expectedAntinodes) . "\n";
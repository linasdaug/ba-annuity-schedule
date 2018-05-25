<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'schedule.class.php';

$pv = 5000;     // present value
$np = 24;       // number of periods
$rate = 12;        // interest rate
$start = mktime(0,0,0,4,15,2017);



// TASK 1

$task = 1;

$schedule = new Schedule ($pv, $np, $rate, $start);
$schedule->toFile($task);


// TASK 2

$task = 2;

$newRate = 9;
$updTime = mktime(0,0,0,9,2,2017);
$schedule->update($updTime, $newRate);
$schedule->toFile($task);



// TASK 3


include 'inputform.php';





 ?>

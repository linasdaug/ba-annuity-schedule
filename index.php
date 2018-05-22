<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


// default inputs:

$presentValue = 10000;
$numberOfPeriods = 26;
$interestRate = 7;

// adjusted values:

$interestMonthly = $interestRate / 100 / 12;
$startTime = mktime(0,0,0,11,15,2016);
$startDate=date('Y/m/d', $startTime);


echo 'Payment #;Payment date;Remaining amount;Principal payment;Interest payment;Total payment;Interest rate';


// generating schedule: preparations and head

$totalPayment = number_format(floor(($interestMonthly * $presentValue) / (1 - pow(1 + $interestMonthly, $numberOfPeriods * -1)) * 100)/100, 2);

$remainingAmount = $presentValue;
$principalPayment = 0;
$paymentTime = $startTime;
$schedule1 = fopen("schedule1.csv", "w") or die("Unable to open file!");

fputcsv($schedule1,['Payment #','Payment date','Remaining amount','Principal payment','Interest payment','Total payment','Interest rate'], ",");

// generating schedule: table

for ($i=1; $i <= $numberOfPeriods; $i++) {
    $paymentDate=date('Y/m/d', $paymentTime);
    $remainingAmount = number_format((float)($remainingAmount-$principalPayment), 2, '.', '');
    $interestPayment = number_format((float)round($remainingAmount * $interestMonthly * 100)/100, 2);
    $principalPayment = number_format((float)$totalPayment - $interestPayment, 2);

    // to be csv
    fputcsv($schedule1,[$i,$paymentDate,$remainingAmount,$principalPayment,$interestPayment,$totalPayment,$interestRate], ",");

    $paymentTime = strtotime("+1 month", $paymentTime);
}


 ?>

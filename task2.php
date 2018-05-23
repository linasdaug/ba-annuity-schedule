<?php


// TASK #2: SCHEDULE 2

$presentValue = 5000;
$numberOfPeriods = 24;
$interestRate = 12;
$startTime = mktime(0,0,0,4,15,2017);
$updateTime = mktime(0,0,0,9,2,2017);
$newInterest = 9;

// adjusted values:

$currentInterest = $interestRate;
$interestMonthly = $interestRate / 100 / 12;
$startDate=date('Y/m/d', $startTime);


// generating schedule: preparations and head

$totalPayment = number_format(floor(($interestMonthly * $presentValue) / (1 - pow(1 + $interestMonthly, $numberOfPeriods * -1)) * 100)/100, 2);

$remainingAmount = $presentValue;
$principalPayment = 0;
$paymentTime = $startTime;
$schedule2 = fopen("schedule2.csv", "w") or die("Unable to open file!");

fputcsv($schedule2,['Payment #','Payment date','Remaining amount','Principal payment','Interest payment','Total payment','Interest rate'], ",");

// generating schedule: table

for ($i=1; $i <= $numberOfPeriods; $i++) {

    $remainingAmount = number_format((float)($remainingAmount-$principalPayment), 2, '.', '');


    // the month of interest change (transition month), average interest to be calculated:

    if (($paymentTime >= $updateTime) && ($updateTime > strtotime("-1 month", $paymentTime))) {
        $oldInterestValidDays = ($updateTime - strtotime("-1 month", $paymentTime))/86400;
        $newInterestValidDays = ($paymentTime - $updateTime)/86400;
        $avgInterest = number_format(((($interestRate * $oldInterestValidDays) + ($newInterest * $newInterestValidDays)) / ($oldInterestValidDays + $newInterestValidDays)), 2);
        $interestMonthly = $avgInterest / 100 / 12;
        $remainingPeriods = $numberOfPeriods - $i + 1;
        $totalPayment = number_format(floor(($interestMonthly * $remainingAmount) / (1 - pow(1 + $interestMonthly, $remainingPeriods * -1)) * 100)/100, 2);
        $currentInterest = $avgInterest;
    }


    // next full month after interest change, interest and total paymens recalculated:

    if (($paymentTime >= strtotime("+1 month", $updateTime)) && ($paymentTime < strtotime("+2 month", $updateTime))) {
        $interestMonthly = $newInterest / 100 / 12;
        $remainingPeriods = $numberOfPeriods - $i + 1;
        $totalPayment = number_format(floor(($interestMonthly * $remainingAmount) / (1 - pow(1 + $interestMonthly, $remainingPeriods * -1)) * 100)/100, 2);
        $currentInterest = $newInterest;
    }

    $paymentDate=date('Y/m/d', $paymentTime);

    $interestPayment = number_format((float)round($remainingAmount * $interestMonthly * 100)/100, 2);
    $principalPayment = number_format((float)$totalPayment - $interestPayment, 2);

    if ($i == $numberOfPeriods) {
        $principalPayment = $remainingAmount;
        $totalPayment = $principalPayment + $interestPayment;
    }

    fputcsv($schedule2,[$i,$paymentDate,$remainingAmount,$principalPayment,$interestPayment,$totalPayment,$currentInterest], ",");

    $paymentTime = strtotime("+1 month", $paymentTime);
}

echo "<br><br>";
echo "Task #2. All well. Schedule stored to <a href='schedule2.csv'>schedule2.csv</a>";





 ?>

<?php

include 'payment.class.php';

class Schedule {

    public $presentValue;
    public $numOfPeriods;
    public $rate;
    public $startTime;
    public $payments;

    function __construct($presentValue, $numOfPeriods, $rate, $startTime) {
        $this->presentValue = $presentValue;
        $this->numOfPeriods = $numOfPeriods;
        $this->rate = $rate;
        $this->startTime = $startTime;


        // generating payments information:

        $remainingAmount = $presentValue;
        $totalPayment = $this->totalPayment($presentValue, $numOfPeriods, $rate);

        for ($i=0; $i < $numOfPeriods; $i++) {
            $this->payments[$i] = new Payment($i+1, strtotime("+".$i." month", $startTime), $remainingAmount, $totalPayment, $rate);

            // last payment needs a very small, microscopic indeed, adjustment:
            if ($i == $numOfPeriods-1) {
                $this->payments[$i]->principalPayment = number_format($remainingAmount, 2, '.', '');
                $this->payments[$i]->totalPayment = $this->payments[$i]->principalPayment + $this->payments[$i]->interestPayment;
            }

            $remainingAmount -= $this->payments[$i]->principalPayment;
        }
    }

    // calculate "Total Monthly Payments:"

    public function totalPayment($value, $periods, $interest) {
        $interestMonthly = $interest / 12 / 100;
        $tp = floor(($interestMonthly * $value) / (1 - pow(1 + $interestMonthly, $periods * -1)) * 100)/100;
        return $tp;
    }


    // write schedule to .csv file:

    public function toFile($task) {
        $file = fopen("schedule".$task.".csv", "w") or die("Unable to open file!");
        fputcsv($file,['Payment #','Payment date','Remaining amount','Principal payment','Interest payment','Total payment','Interest rate'], ",");
        for ($i=0; $i < count($this->payments); $i++) {
            fputcsv($file, (array)$this->payments[$i], ",");
        }
        echo "Task #".$task." complete. Schedule saved to <a href='schedule".$task.".csv'>schedule".$task.".csv</a><br>";
    }

    public function findPayment($time) {
        for ($i=0; $i < count($this->payments); $i++) {
            if (strtotime($this->payments[$i]->date) > $time) {
                return $i;
            }
        }
    }

    public function update($time, $rate) {
        $id = $this->findPayment($time);

        // recalculate THE month in which the change occured:

        $oldInterestValidDays = ($time - strtotime($this->payments[$id-1]->date))/86400;
        $newInterestValidDays = (strtotime($this->payments[$id]->date) - $time)/86400;
        $avgInterest = number_format(((($this->payments[$id]->rate * $oldInterestValidDays) + ($rate * $newInterestValidDays)) / ($oldInterestValidDays + $newInterestValidDays)), 2);
        $interestMonthly = $avgInterest / 100 / 12;
        $this->payments[$id]->totalPayment = $this->totalPayment($this->payments[$id]->remainingAmount, $this->numOfPeriods - $id, $avgInterest);
        $this->payments[$id]->rate = $avgInterest;
        $this->payments[$id]->interestPayment = number_format((float)round($this->payments[$id]->remainingAmount * $interestMonthly * 100)/100, 2);
        $this->payments[$id]->principalPayment = $this->payments[$id]->totalPayment - $this->payments[$id]->interestPayment;


        // recalculate remaining payments

        $interestMonthly = $rate / 100 / 12;
        $remainingAmount = $this->payments[$id]->remainingAmount - $this->payments[$id]->principalPayment;
        $totalPayment = $this->totalPayment($remainingAmount, $this->numOfPeriods-$id-1, $rate);


        for ($i=$id+1; $i < $this->numOfPeriods; $i++) {
            $this->payments[$i]->remainingAmount = $remainingAmount;
            $this->payments[$i]->rate = $rate;
            $this->payments[$i]->totalPayment = $totalPayment;
            $interestMonthly = $rate / 12 / 100;
            $this->payments[$i]->interestPayment = number_format((float)round($remainingAmount * $interestMonthly * 100)/100, 2);
            $this->payments[$i]->principalPayment = $totalPayment - $this->payments[$i]->interestPayment;

            // last payment needs adjustment:
            if ($i == $this->numOfPeriods-1) {
                $this->payments[$i]->principalPayment = number_format($remainingAmount, 2);
                $this->payments[$i]->totalPayment = $this->payments[$i]->principalPayment + $this->payments[$i]->interestPayment;
            }

            $remainingAmount -= $this->payments[$i]->principalPayment;
        }
    }

    // function to display schedule on screen, if needed:

    public function toScreen() {
        echo "<table><thead><th>Payment #</th><th>Payment date</th><th>Remaining amount</th><th>Principal payment</th><th>Interest payment</th><th>Total payment</th><th>Interest rate</th>";
        foreach ($this->payments as $payment) {
            echo "<tr>";
            echo "<td>".$payment->id."</td>";
            echo "<td>".$payment->date."</td>";
            echo "<td>".$payment->remainingAmount."</td>";
            echo "<td>".$payment->principalPayment."</td>";
            echo "<td>".$payment->interestPayment."</td>";
            echo "<td>".$payment->totalPayment."</td>";
            echo "<td>".$payment->rate."</td>";
        }
    }

}

 ?>

<?php

class Payment {
    public $id;
    public $date;
    public $remainingAmount;
    public $principalPayment;
    public $interestPayment;
    public $totalPayment;
    public $rate;

    function __construct($id, $paymentTime, $remainingAmount, $totalPayment, $rate) {
        $this->id = $id;
        $this->date = date('Y/m/d', $paymentTime);
        $this->remainingAmount = $remainingAmount;
        $this->rate = $rate;
        $this->totalPayment = $totalPayment;
        $interestMonthly = $rate / 12 / 100;
        $this->interestPayment = number_format((float)round($remainingAmount * $interestMonthly * 100)/100, 2);
        $this->principalPayment = $totalPayment - $this->interestPayment;
    }

}

?>

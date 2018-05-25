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
        $this->remainingAmount = number_format((float)$remainingAmount, 2, '.', '');
        $this->rate = $rate;
        $this->totalPayment = number_format((float)$totalPayment, 2, '.', '');
        $interestMonthly = $rate / 12 / 100;
        $this->interestPayment = number_format((float)round($remainingAmount * $interestMonthly * 100)/100, 2, '.', '');
        $this->principalPayment = number_format((float)($totalPayment - $this->interestPayment), 2, '.', '');
    }

}

?>

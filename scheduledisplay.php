<script type="text/javascript">
// this function displays form to change interest rate for
// existing schedule (one time change)
function showinput() {
    document.getElementById("rateChanger").style.display = "none";
    let rateInputForm = document.getElementById("rateChangeForm");
    rateInputForm.style.display = "inline";
    let rateInput = document.getElementById("rateInput");
    rateInput.setAttribute("type", "number");
    let dateInput = document.getElementById("dateInput");
    dateInput.setAttribute("type", "date");
    let rateEnter = document.createElement("button");
    rateEnter.setAttribute("type", "submit");
    let buttonText = document.createTextNode("submit");
    rateEnter.appendChild(buttonText);
    rateInputForm.appendChild(rateEnter);
}

</script>

<?php

include 'schedule.class.php';

// initial values:

if ($_POST['presentValue']!==null) {
    $presentValue = $_POST['presentValue'];
};
if ($_POST['rate']!==null) {
    $rate = $_POST['rate'];
};
if ($_POST['numOfPeriods']!==null) {
    $numOfPeriods = $_POST['numOfPeriods'];
};
if (isset($_POST['year'])) {
    $year = $_POST['year'];
};
if (isset($_POST['month'])) {
    $month = $_POST['month'];
};
if (isset($_POST['day'])) {
    $day = $_POST['day'];
    $startTime = mktime(0,0,0,$month,$day,$year);
} else {
    $startTime = $_POST['start'];
};

// values if interest rate changes:

if (isset($_POST['newRate'])) {
    $newRate = $_POST['newRate'];
};
if (isset($_POST['newDate'])) {
    $updateTime = strtotime($_POST['newDate']);
};

$endTime = strtotime("+".$numOfPeriods." month", $startTime);
$startDate = date('Y-m-d', $startTime);
$endDate = date('Y-m-d', $endTime);

echo "<a href='index.php'>&#8624 return</a><br>";


// generating schedule:


if (isset($updateTime)) {
    $schedule = new Schedule($presentValue, $numOfPeriods, $rate, $startTime);
    $schedule->update($updateTime, $newRate);
} else {
    $schedule = new Schedule($presentValue, $numOfPeriods, $rate, $startTime);
}

$schedule->toFile(3);


// displaying schedule:

echo "<h1>Annuity Payment Schedule</h1><br>";
echo "Present value: ".$presentValue."<br>";
echo "Interest rate: ".$rate." ";
echo "<input id='rateChanger' type='text' onclick='showinput()' value=' enter rate change' style='cursor:pointer'><br>";

// if needed, interest rate may be changed one time:

echo "<form id='rateChangeForm' action='scheduledisplay.php' method='post' style='display:none'>Enter new rate and effective date: ";
echo "<input id='rateInput' name='newRate' type='hidden' min='0' max='100' step='0.01'>";
echo "<input id='dateInput' name='newDate' type='hidden' min='".$startDate."' max='".$endDate."'>";
echo "<input type='hidden' name='presentValue' value='".$presentValue."'>";
echo "<input type='hidden' name='rate' value='".$rate."'>";
echo "<input type='hidden' name='numOfPeriods' value='".$numOfPeriods."'>";
echo "<input type='hidden' name='start' value='".$startTime."'>";
echo "</form><br>";

if (isset($newRate)) {
    $newDate = date(date('Y/m/d', $updateTime));
    echo "new rate: ".$newRate.", effective from: ".$newDate."<br>";
}

echo "Number of periods (months): ".$numOfPeriods."<br>";
echo "First payment date: ".date('Y/m/d', $startTime)."<br><br>";


$schedule->toScreen();



 ?>

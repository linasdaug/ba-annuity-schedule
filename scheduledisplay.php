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
if(!isset($_SESSION)) { session_start(); };

if (isset($_POST['presentValue'])) {

    // initial values:

    $presentValue = $_POST['presentValue'];
    $rate = $_POST['rate'];
    $numOfPeriods = $_POST['numOfPeriods'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $startTime = mktime(0,0,0,$month,$day,$year);

} else {

    // values if interest rate changes:

    $newRate = $_POST['newRate'];
    $updateTime = strtotime($_POST['newDate']);
}




if (isset($updateTime)) {

    // if new interest entered, update schedule stored in session:

    $schedule = unserialize($_SESSION['schedule']);
    $schedule->update($updateTime, $newRate);
    $numOfPeriods = count($schedule->payments);
    $startTime = strtotime($schedule->payments[0]->date);
    $presentValue = $schedule->payments[0]->remainingAmount;
    $rate = $schedule->payments[0]->rate;

} else {

    // by default, generating schedule:

    $schedule = new Schedule($presentValue, $numOfPeriods, $rate, $startTime);
}

$endTime = strtotime("+".($numOfPeriods-1)." month", $startTime);
$startDate = date('Y-m-d', $startTime);
$endDate = date('Y-m-d', $endTime);

echo "<a href='index.php'>&#8624 return</a><br>";


$schedule->toFile(3);   // "3" means task #3


// displaying schedule:

echo "<h1>Annuity Payment Schedule</h1><br>";
echo "Present value: ".$presentValue."<br>";
echo "Interest rate: ".$rate." ";
echo "<input id='rateChanger' type='text' onclick='showinput()' value=' enter rate change' style='cursor:pointer'><br>";

// if needed, interest rate may be changed one time:

echo "<form id='rateChangeForm' action='scheduledisplay.php' method='post' style='display:none'>Enter new rate and effective date: ";
echo "<input id='rateInput' name='newRate' type='hidden' min='0' max='100' step='0.01' required>";
echo "<input id='dateInput' name='newDate' type='hidden' min='".$startDate."' max='".$endDate."' required>";
echo "</form><br>";

if (isset($newRate)) {
    $newDate = date(date('Y/m/d', $updateTime));
    echo "new rate: ".$newRate.", effective from: ".$newDate."<br>";
}

echo "Number of periods (months): ".$numOfPeriods."<br>";
echo "First payment date: ".date('Y/m/d', $startTime)."<br><br>";


$schedule->toScreen();


// store to session to be ready for changes, if any

$serializeSchedule = serialize($schedule);
$_SESSION['schedule'] = $serializeSchedule;


 ?>

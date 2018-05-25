<br><br>
<form action="scheduledisplay.php" method="post">
    <h3>Calculate Loan Schedule</h3><br>
    <label for="amount">Enter amount:</label><br>
    <input type="number" name="presentValue" min="100" max="100000000" required><br>
    <label for="rate">Enter interest rate:</label><br>
    <input type="number" name="rate" min="0.01" max="100" step="0.01" required><br>
    <label for="rate">Enter number of periods (months):</label><br>
    <input type="number" name="numOfPeriods" min="1" max="600" required><br>
    <h3>Enter date of the first payment:</h3>
    <label for="amount">Year</label><br>
    <input type="number" name="year" min="2010" max="2099" required><br>
    <label for="amount">Month (1 to 12)</label><br>
    <input type="number" name="month" min="1" max="12" required><br>
    <label for="amount">Day</label><br>
    <input type="number" name="day" min="1" max="31" required><br><br>
    <button type="submit" name="" value="">Submit data</button>
</form>

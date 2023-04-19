<!DOCTYPE html>
<head>
<title> Computing
 </title>
</head>
<?php
$FirstNumber = $_POST['FirstNumber'];
$SecondNumber = $_POST['SecondNumber'];
$ThirdNumber = $_POST['ThirdNumber'];
$ForthNumber = $_POST['ForthNumber'];
$FifthNumber = $_POST['FifthNumber'];
$operator = $_POST['operator'];
$CalculatorResult = '';
if (is_numeric($FirstNumber) && is_numeric($SecondNumber)) {
switch ($operator) {
case "Sum":
$CalculatorResult = $FirstNumber + $SecondNumber;
break;
case "Subtraction":
 $CalculatorResult = $FirstNumber - $SecondNumber;
break;
case "Multiplication":
$CalculatorResult = $FirstNumber * $SecondNumber;
break;
case "Computing":
$CalculatorResult = ($FirstNumber + $SecondNumber) / $ThirdNumber + (1);
}
}
?>
 
<body>
<div id="page-wrap">
<center>
<a href="index.php"><h2>Main Page</h2></a> 
</center>
<center>
<h1>[ Calculate the distance between each call ]</h1>
<form action="" method="post" id="quiz-form">
<p>
<input type="number" name="FirstNumber" id="FirstNumber" required="required" value="<?php echo $FirstNumber; ?>" /> <b>The duration of the audio file</b>
</p>
<p>
<input type="number" name="SecondNumber" id="SecondNumber" required="required" value="<?php echo $SecondNumber; ?>" /> <b>Waiting time per call</b>
</p>
<p>
<input type="number" name="ThirdNumber" id="ThirdNumber" required="required" value="<?php echo $ThirdNumber; ?>" /> <b>Number of trunk channels</b>
</p>
<p>
<input readonly="readonly" name="CalculatorResult" value="<?php echo $CalculatorResult; ?>"> <b> = The distance between each call </b>
</p>
<!-- <input type="submit" name="operator" value="Sum" /> 
<input type="submit" name="operator" value="Subtraction" />
<input type="submit" name="operator" value="Multiplication" /> -->
<input type="submit" name="operator" value="Computing" />
</center>
</form>
</div>
</body>
</html>
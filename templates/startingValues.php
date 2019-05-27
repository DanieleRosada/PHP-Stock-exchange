<h2>Parameters</h2>
<form method="POST" action="?action=startingValues">
		Starting Value:
	<br>
		<input name="StartingValue" type="number" value="1000">
	<br>
		Update time:
	<br>
		<input name="UpdateTime" type="number" value="5">
	<br>
		Percentage of increment:
	<br>
		<input name="Percentage" type="number" value="50">
	<br>
	<br>
		<input type="submit" value="Simulator" name="Simulator">  
</form>
<?php echo $ErrorMessage?>

	<p><?php echo $Username;?> - <a href="?action=index">Entra con un altro profilo</a></p>


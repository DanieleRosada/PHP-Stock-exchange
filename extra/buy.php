<?php
echo "ciao, funziono"; 
if(isset($_POST['Buy'])){
			$NumberOfShares=getPostValue('NumberOfShares',0);
			$ValueOfShares = buy($NumberOfShares,$CurrentValue);
			$InvestimentTime = (time());
			$_SESSION["InvestimentTime"]=$InvestimentTime;
			$_SESSION["ValueOfShares"]=$ValueOfShares;
			$_SESSION["NumberOfShares"]=$NumberOfShares;
			$buyOrSell='buy';
			$_SESSION["buyOrSell"]=$buyOrSell;
}
if(isset($_POST['Sell'])){
			$NumberOfShares=getPostValue('NumberOfShares',0);
			$ValueOfShares = sell($NumberOfShares,$CurrentValue);
			$InvestimentTime = (time());
			$_SESSION["InvestimentTime"]=(time());
			$_SESSION["ValueOfShares"]=$ValueOfShares;
			$_SESSION["NumberOfShares"]=$NumberOfShares;
			$buyOrSell='sell';
			$_SESSION["buyOrSell"]=$buyOrSell;
	
}
?>

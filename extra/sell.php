<?php
			$NumberOfShares=getPostValue('NumberOfShares',0);
			$ValueOfShares = sell($NumberOfShares,$CurrentValue);
			$InvestimentTime = (time());
			$_SESSION["InvestimentTime"]=(time());
			$_SESSION["ValueOfShares"]=$ValueOfShares;
			$_SESSION["NumberOfShares"]=$NumberOfShares;
			$buyOrSell='sell';
			$_SESSION["buyOrSell"]=$buyOrSell;
?>

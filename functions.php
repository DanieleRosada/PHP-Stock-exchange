<?php
//Funzione per accesso al db
function getDBHandler()
{
	global $config;
	try
	{
		/*** connect to MySQL database and get a handler ***/
		$dbh = new PDO(
			$config['db_connection_string'],
			$config['db_user'],
			$config['db_password']
			);
		return $dbh;
	}
	catch(PDOException $e)
	{
		// echo 'Error: ' . $e->getMessage() . "\n";
		return false;
	}
}
//Funzione per raccogliere i dati 
function getValueFromArray($array, $key, $default)
{
	return isset($array[$key]) ? $array[$key] : $default;
}
//Funzione per raccogliere i dati con il metodo Get 
function getUrlValue($key, $default)
{
	return getValueFromArray($_GET, $key, $default);
}
//Funzione per raccogliere i dati con il metodo Post 
function getPostValue($key, $default)
{
	return getValueFromArray($_POST, $key, $default);
}
//
function getSessionValue($key, $default)
{
	return getValueFromArray($_SESSION, $key, $default);
}
//Funzione per verificare se è un numero ed se è maggiore o uguale a 0
function isnumeric($value)
{
	if (is_numeric($value) and $value>=0)
		return true;
	else
		return false;
}
//Funzione che incrementa o diminuisce il valore corrente del grafico
function random($Percentage)
{
	 $buy_sell=rand (1 ,100);
	 $value=rand (0 ,10);
	 if ($buy_sell<=$Percentage)
		return $value;
	 else
		return -$value;
}
//Funzione per login, max 10 persone
function chckusername($username, $password){

/*global $dbh;
  $stmt = $dbh->prepare("SELECT * FROM login where User='$username' and  Password='$password'");

  $stmt->bindParam(1, $max_rank);
  $max_rank = 10;

  if ($stmt->execute()){
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			return true;
		}
   }
   else
		return false;*/
		if ($username="x" && $password="y"){
		return true;
		}else{
		return false;
		}
}
//Funzione che aggiorna il grafico
function UpdateGraph ($value, $time)
{
$file = fopen("data.tsv", "a");
fwrite($file, $time."\t".$value."\n");
fclose($file);
}
//Funzione che elimina e crea un nuovo grafico
function DeleteAndCreateFile()
{
unlink("data.tsv");
$file = fopen("data.tsv", "w");
fwrite($file,"date"."\t"."close"."\n");
fclose($file);
}
//Funzione che restituisce il risultato economico
function EconomicResult($NumberOfShares,$CurrentValue,$ValueOfShares, $buyOrSell){
	if ($NumberOfShares==0){
		$EconomicResult=0;
		return $EconomicResult;
	}
	elseif ($buyOrSell=='buy'){
		$EconomicResult=($NumberOfShares*$CurrentValue)-$ValueOfShares;
		return $EconomicResult." €";
	}
	elseif ($buyOrSell=='sell') {
		$EconomicResult=$ValueOfShares-($NumberOfShares*$CurrentValue);
		return $EconomicResult." €";
	}
		
}
//Funzione per vedere il valore delle azioni comprato
function buy($NumberOfShares,$CurrentValue)
{
	$ValueOfShares=$NumberOfShares*$CurrentValue;
	return $ValueOfShares . " €";
	
}
//Funzione per vedere il valore delle azioni venduto
function sell($NumberOfShares,$CurrentValue)
{
	$ValueOfShares=$NumberOfShares*$CurrentValue;
	return $ValueOfShares . " €";
}
//Funzione che restituisce un valore positivo se il valore è uguale o minore a 0
function MinValue($value){
		if ($value<=0)
			return true;
}
?>

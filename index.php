<?php
//require("config.php"); //Dati per accesso db
require("functions.php"); //Tutte le funzioni richiamate in questa pagina
session_start(); //Apro la sessione per il trasferimento di dati da una pagina web ad un altra

//Variabili base che mi serviranno successivamente
$ErrorMessage="";
$Date=time();
$Date=(date("d-m-Y",$Date));
$action=getUrlValue('action', '');
$template = $action;
$jqcode="";

//Collegamento database Phpmyadmin
$dbh= getDBHandler();

/*if (!$dbh)
{
	$action="problemidb";
}*/
$template = $action; 
//Tutte le varie pagine web
switch ($action)
{	
	case 'index': //Pagina principale
		$TopMessage= "The username is x and the password is y";
		$ErrorMessage=getSessionValue('ErrorMessage', "");
		$_SESSION["ErrorMessage"]="";
		$_SESSION["Username"]=null;
		$_SESSION["StartingValue"]=null;
		$_SESSION["Percentage"]=null;
	break;
	case 'problemidb': //Pagina se ho problemi al db
		include("templates/" . $template . ".php");
		die();
	break;
	case 'login': //Pagina per il login iniziale
		if(isset($_POST['Enter'])){
			$Username=getPostValue("username",-1);
			$Password=getPostValue("password",-1);
			if (chckusername($Username, $Password))
			{	
				$_SESSION["Username"]=$Username;
				header("Location: ?action=startingValues");
			}
			else{
				$_SESSION["ErrorMessage"]="Error, wrong Username or Password";
				header("Location: ?action=index");
			}
		}	
	break;	
	case 'startingValues': //Pagina per l'inserimento dei valori di partenza
		$TopMessage= "Enter the values";
		$Username=$_SESSION["Username"];
		//Verifico se l'utente ha già passato il login
		if (!$Username){
			header("Location: ?action=index");	
		}
		if(isset($_POST['Simulator'])){
			$StartingValue=getPostValue('StartingValue',-1);
			$UpdateTime=getPostValue('UpdateTime',-1);
			$Percentage=getPostValue('Percentage',-1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
			if (isnumeric($StartingValue) and isnumeric($UpdateTime) and isnumeric($Percentage)){
				$StartingTime = (time());
				$StartingTime = date("H:i:s",$StartingTime);
				$_SESSION["StartingTime"]=$StartingTime;
				$_SESSION["StartingValue"]=$StartingValue;
				$_SESSION["CurrentValue"]=$StartingValue;
				$_SESSION["UpdateTime"]=$UpdateTime*1000; //Moltiplico per mille perchè sono  millisecondi
				$_SESSION["Percentage"]=$Percentage;
				$_SESSION["InvestimentTime"]=0; 
				$_SESSION["ValueOfShares"]=0;
				$_SESSION["NumberOfShares"]=0;
				$_SESSION["TotalEconomicResult"]=0;
				$_SESSION["buyOrSell"]="";
				$_SESSION["FirstTime"]=true;
				//Operazioni sul file del grafico
				DeleteAndCreateFile();
				UpdateGraph($StartingValue, $StartingTime);
				header("Location: ?action=simulator");
			}
			else{
				$ErrorMessage= "Invalid values";
			}
		}
	break;
	case 'simulator':
		$TopMessage= "Try yourself";
		//Dati da aggiornare una volta sola
		$Username=$_SESSION["Username"];
		$StartingValue = $_SESSION["StartingValue"];
		$UpdateTime=$_SESSION["UpdateTime"];
		$StartingTime = $_SESSION["StartingTime"];
		$CurrentValue=$_SESSION["CurrentValue"];
		//Verifico se l'utente è già passato sia per il login e ha inserito i valori di partenza
		if (!$Username){
			header("Location: ?action=index");	}
		elseif (!$StartingValue){
			header("Location: ?action=startingValues");}
	break;
	case 'ajaxsimulator':
		//Variabile che mi serve per il primo non aggiornamento del grafico
		$FirstTime = $_SESSION["FirstTime"];
		$_SESSION["FirstTime"]=false;
		//Variabili per aggiornamento grafico
		$Percentage = $_SESSION["Percentage"];
		$Uptime = (time());
		$Uptime = date("H:i:s",$Uptime);
		$CurrentValue = $_SESSION["CurrentValue"];
		//Variabili per l'investimento
		$InvestimentTime = $_SESSION["InvestimentTime"];
		$NumberOfShares = $_SESSION["NumberOfShares"];
		$ValueOfShares = $_SESSION["ValueOfShares"];
		$buyOrSell= $_SESSION["buyOrSell"];
		//Gestione pagina, non si può accedere se non si è passati dalle pagine precedenti
		$Username=$_SESSION["Username"];
		if (!$Username){
			header("Location: ?action=index");	}
		elseif (!$Percentage){
			header("Location: ?action=startingValues");}
		//Gestione pulsanti
		$value=getPostValue('value', -1);
		if($value=='Buy'){
			$NumberOfShares=getPostValue('input', -1);
			$ValueOfShares = buy($NumberOfShares,$CurrentValue);
			$InvestimentTime = (time());
			$_SESSION["InvestimentTime"]=$InvestimentTime;
			$_SESSION["ValueOfShares"]=$ValueOfShares;
			$_SESSION["NumberOfShares"]=$NumberOfShares;
			$buyOrSell='buy';
			$_SESSION["buyOrSell"]=$buyOrSell;
			}
		if ($value=='Sell'){
			$NumberOfShares=getPostValue('input', -1);
			$ValueOfShares = sell($NumberOfShares,$CurrentValue);
			$InvestimentTime = (time());
			$_SESSION["InvestimentTime"]=(time());
			$_SESSION["ValueOfShares"]=$ValueOfShares;
			$_SESSION["NumberOfShares"]=$NumberOfShares;
			$buyOrSell='sell';
			$_SESSION["buyOrSell"]=$buyOrSell;
			}
		//Se sono appena entrato in pagina non fa l'aggiornamento
		if (!$FirstTime){	
			$CurrentValue=$CurrentValue+random($Percentage);
			If (MinValue($CurrentValue)){
				$CurrentValue=0;
				$_SESSION["Percentage"]=0;
				$ErrorMessage="Bankrupt company";
			}
			$_SESSION["CurrentValue"]=$CurrentValue;
			UpdateGraph ($CurrentValue, $Uptime);
		}	
		$EconomicResult = EconomicResult($NumberOfShares,$CurrentValue,$ValueOfShares,$buyOrSell);
		if ($InvestimentTime<>0){
			$InvestimentTime = date("H:i:s",$InvestimentTime);
		}
			//Non prendo in considerazione la grafica perchè lo fa già la pagina simulator
			include("templates/" . $template . ".php");
			die();
	break;
	default:
		header("Location: ?action=index");	
}
		
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Exchange</title>
    <meta name="description" content="Your Description Here">
    <meta name="keywords" content="bootstrap themes, portfolio, responsive theme">
    <meta name="author" content="ThemeForces.Com">
    <!-- Favicons
    ================================================== -->
    <!--<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">-->
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
    <!-- <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png"> -->

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css"  href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">

    <!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">

		<script type="text/javascript" src="js/modernizr.custom.js"></script>
		<script src="js/d3.v4.min.js"></script>

    <link href='css1.php' rel='stylesheet' type='text/css'>
    <link href='css2.php' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <div id="tf-home">
        <div class="overlay">
            <div id="sticky-anchor"></div>
            <nav id="tf-menu" class="navbar navbar-default">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand logo" href="index.php">Daniele Rosada</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav navbar-right">
                        <li><a href="#tf-home">Home</a></li>
                        <li><a href="#tf-service">Operations</a></li>
                      </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>

            <div class="container">
                <div class="content">
                    <h1>Welcome to my website</h1>
                    <h1>Trading simulator</h1>
                    <br>
					<h3><?php echo $TopMessage?></h3>
                </div>
            </div>
        </div>
    </div>

    <div id="tf-service">
        <div class="container">

			<h1>Stock Exchange <?php echo $Date?></h1>
			<?php include("templates/" . $template . ".php"); ?>
            
        </div>
    </div>

    <nav id="tf-footer">
        <div class="container">
             <div class="pull-left">
                <p>Thank you for attention!</p>
            </div>
            <div class="pull-right"> 
                <ul class="social-media list-inline">
                    <li><a href="https://www.facebook.com/"><span class="fa fa-facebook"></span></a></li>
                    <li><a href="https://twitter.com/?lang=en"><span class="fa fa-twitter"></span></a></li>
                </ul>
            </div>
        </div>
    </nav>
   

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="ajax.php"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="js/bootstrap.js"></script>

    <!-- Javascripts
    ================================================== -->
    <script type="text/javascript" src="js/main.js"></script>

  </body>
	<script>
		 $(document).ready(function(){
		<?php echo $jqcode ?>
	  });
	</script> 
</html>

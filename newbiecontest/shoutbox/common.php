<?php

include("globale.php");
include("config.php");
include("function.php");


$db=mysql_connect($host,$login_bdd,$pass_bdd) or die("Connexion à la base de données impossible");
mysql_select_db($bd,$db) or die("Sélection de la base de données impossible");


?>
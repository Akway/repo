<?php

//***fonction connect($path), argument : $path / returns réponse server :  $output
function connect($path)
{
	//sleep();
	//echo "\r\n\r\n* CONNEXION à : ".$path."\r\n";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $path);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_COOKIE,"SMFCookie89=a%3A4%3A%7Bi%3A0%3Bs%3A3%3A%22534%22%3Bi%3A1%3Bs%3A40%3A%22dc94d4515f17debbc6849568f03a6d9e8160d78b%22%3Bi%3A2%3Bi%3A1623275255%3Bi%3A3%3Bi%3A0%3B%7D;PHPSESSID=f79639076c21952f4de51ce482f75943;  admin=0") ;
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output=curl_exec($ch);
	curl_close($ch);
	//echo "\r\n\r\n* REPONSE DE : ".$path."\r\n\r\n";
	echo $output;
	//echo "\r\n\r\n* FIN DE REPONSE \r\n\r\n";
	return $output;
}
//***fin de la fonction connect***

// connexion a progcrawlme.php
$path="https://www.newbiecontest.org/epreuves/prog/prog_crackmefast.php";
$init_connect=connect($path);
?>

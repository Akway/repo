<?php

//***fonction connect($path), argument : $path / returns réponse server :  $output
function connect($path)
{
	sleep(1);
	echo "\r\n\r\n* CONNEXION à : ".$path."\r\n";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $path);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_COOKIE,"SMFCookie89=a%3A4%3A%7Bi%3A0%3Bs%3A3%3A%22534%22%3Bi%3A1%3Bs%3A40%3A%22dc94d4515f17debbc6849568f03a6d9e8160d78b%22%3Bi%3A2%3Bi%3A1623275255%3Bi%3A3%3Bi%3A0%3B%7D;PHPSESSID=f79639076c21952f4de51ce482f75943;  admin=0") ;
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output=curl_exec($ch);
	curl_close($ch);
	echo "\r\n\r\n* REPONSE DE : ".$path."\r\n\r\n";
	echo $output;
	echo "\r\n\r\n* FIN DE REPONSE \r\n\r\n";
	return $output;
}
//***fin de la fonction connect

// connexion a progcrawlme.php
$path="http://www.newbiecontest.org/epreuves/prog/progcrawlme.php";
$init_connect=connect($path);

//recuperation du repertoire racine.
$decoup2=explode("a href=", $init_connect);
$decoup4=explode(">", $decoup2[1]);
$decoup5=substr($decoup4[0],2);
$decoup6=explode('"',$decoup5);
$decoup7=trim($decoup6[0]);

// sauvegarde des noms de dossiers N1 dans ./list_reps.txt
if( file_exists ( "list_reps.txt"))
{
     unlink( "list_reps.txt" ) ;
}
$fp = fopen ("list_reps.txt", "a+");
$rep_racine=explode("/",$decoup7);
fputs ($fp, $rep_racine[2]."\r\n");
fclose ($fp);

//connection au repertoire racine
$path="http://www.newbiecontest.org/epreuves/prog/".$decoup7;
$init_connect=connect($path);

echo "\r\n-EXTRACTION DES NOMS DE DOSSIERS POUR : \r\n".$path."\r\n";

$tp=trim($init_connect);
$preform=explode("Description",$tp);
$parse=explode("href=\"",$preform[1]);
$i=0;
foreach ($parse as $value)
{
	$parse2=explode("/",$value);
	$list_dossiers[$i]=trim($parse2[0]);
	$i++;
}
array_shift($list_dossiers);
print_r($list_dossiers);

// sauvegarde de l'arborescence racine
foreach ($list_dossiers as $value)
{
	$fp = fopen ("list_reps.txt", "a+");
	fputs ($fp, $value."\r\n");
	fclose ($fp);
}
// Connexion  recursive Niv. 2
foreach ($list_dossiers as $value2)
{
	
	//connection niv. 2
	$path="http://www.newbiecontest.org/epreuves/prog".$decoup7."/".$value2;
	$init_connect=connect($path);
	
	echo "\r\n-EXTRACTION DES NOMS DE DOSSIERS POUR : \r\n".$path."\r\n";

	

	// on execute seulement si on a trouvé des DIR dans la page retournée par le server 
	$pos = strpos(trim($init_connect), "DIR");
	if ($pos === false)
	{
		echo "\r\n !  Aucun répertoire\r\n"; 	
	}	
	else
	 {
		echo "\r\n !   Répertoire(s) trouvé(s) :\r\n";
		$tp=trim($init_connect);
		$preform=explode("Description",$tp);
		$parse=explode("href=\"",$preform[1]);
		$i=0;

		foreach ($parse as $value)
		{
			$parse2=explode("/",$value);
			$list_s1_dossiers[$i]=trim($parse2[0]); 
			$i++;
		}
		array_shift($list_s1_dossiers);
		print_r($list_s1_dossiers);
		// sauvegarde de l'arbo
		foreach ($list_s1_dossiers as $value)
		{
			$fp = fopen ("list_reps.txt", "a+");
			fputs ($fp, $value."\r\n");
			fclose ($fp);
		}
		// Connexion recursive Niv.2		
		foreach ($list_s1_dossiers as $value3)
		{
			// connection niv. 3
			$path="http://www.newbiecontest.org/epreuves/prog".$decoup7."/".$value2."/".$value3;
			$init_connect=connect($path);
	
			echo "\r\n-EXTRACTION DES NOMS DE DOSSIERS POUR : \r\n".$path."\r\n";
			$pos = strpos($init_connect, "DIR");
	
			// on execute seulement si on a trouvé des DIR
			if ($pos === false)
			{
				echo "\r\n !  Aucun répertoire trouvé\r\n"; 	
			}	
			else
		 	{
				$tp=trim($init_connect);
				$preform=explode("Description",$tp);
				$parse=explode("href=\"",$preform[1]);
				$k=0;
	
				foreach ($parse as $value)
				{
					//echo "\r\n".$value;
					$parse3=explode("/",$value);
					$list_s2_dossiers[$k]=trim($parse3[0]); 
					$k++;
				}
				array_shift($list_s2_dossiers);
				print_r($list_s2_dossiers);
				// sauvegarde de l'arbo
				foreach ($list_s2_dossiers as $value)
				{
					$fp = fopen ("list_reps.txt", "a+");
					fputs ($fp, $value."\r\n");
					fclose ($fp);
				}
			
				foreach ($list_s2_dossiers as $value4)
				{
						
					//connection niv. 4
					$path="http://www.newbiecontest.org/epreuves/prog".$decoup7."/".$value2."/".$value3."/".$value4;
					$init_connect=connect($path);
	
	
					echo "\r\n-EXTRACTION DES NOMS DE DOSSIERS POUR : \r\n".$path."\r\n";
					$pos = strpos($init_connect, "DIR");
	
					// on execute seulement si on a trouvé des DIR
					if ($pos === false)
					{
						echo "\r\n !  Aucun repertoire trouvé dans : ".$path." !\r\n"; 	 	
					}	
					else
					 {
						$tp=trim($init_connect);
						$preform=explode("Description",$tp);
						$parse=explode("href=\"",$preform[1]);
						$l=0;
						foreach ($parse as $value)
						{
							//echo "\r\n".$value;
							$parse4=explode("/",$value);
							$list_s3_dossiers[$l]=trim($parse4[0]); 
							$l++;
						}
						array_shift($list_s3_dossiers);
						print_r($list_s3_dossiers);
						// sauvegarde de l'arbo
						foreach ($list_s3_dossiers as $value)
						{
							$fp = fopen ("list_reps.txt", "a+");
							fputs ($fp, $value."\r\n");
							fclose ($fp);
						}
					}
				}
			}
		}			
	}			
}

echo "\r\n\r\n\r\n*DONNEES COMPLETES, FORMATAGE DE LA REQUETE DE REPONSE\r\n\r\n";

//Ouverture du fichier en lecture seule*/
$handle = fopen('list_reps.txt', 'r');
$i=0;
/*Si on a réussi à ouvrir le fichier*/
if ($handle)
{
	/*Tant que l'on est pas à la fin du fichier*/
	 while($ligne = fgets($handle))
	{
		$liste[$i] = trim($ligne);
		$i++;
	}
	sort($liste);
	$i=0;
	while(isset($liste[$i]))
	{
		$i++;
	}
	print_r($liste);
	fclose($handle);
}
$concat= implode($liste);
echo  "\r\nliste concatenee : ".$concat."\r\n";
$hash_md5=md5($concat);
echo  "\r\nHash md5 de la chaine : ".$hash_md5."\r\n";

echo "\r\n\r\n========ENVOI DE LA REPONSE FINALE AU SERVER :================= \r\n\r\n";

//envoi de la réponse a verifprogcrawlme.php
$path="http://www.newbiecontest.org/epreuves/prog/verifprogcrawlme.php?md5=".$hash_md5;
$init_connect=connect($path);


?>


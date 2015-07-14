<?php

error_reporting(E_ALL); # Devel





class sudoku {
	// valeur est un tableau de 9 tableaux (les lignes) de 9 valeurs
	// (croisement ligne/colonne)
	// possible est le même tableau, mais pour cahque croisement ligne/colonne
	// on a un tableau avec les valeurs possibles.
	var $valeur, $possible;
	
	function sudoku($jeu=null) {
		$this->nouveau($jeu);
		return true;
	}

	// Crée un tableau avec clé=valeur de 1 à 9.
	function _range19() {
		$tab = array();
		for ($n=1; $n<10; $n++) $tab[$n] = $n;
		return $tab;
	}

	// Crée un jeu à partir des valeurs indiquées
	// dans le tableau de tableau en paramètre $jeu
	function nouveau($jeu) {
		$this->valeur = array();
		$this->possible = array();
		// on fait un jeu vierge
		for ($i=1; $i<10; $i++) {
			$this->valeur[$i] = array();
			$this->possible[$i] = array();
			for ($j=1; $j<10; $j++) {
				// valeur nulle
				$this->valeur[$i][$j] = null;
				// tout est possible dans chaque case
				$this->possible[$i][$j] = $this->_range19();
			}
		}
		// On indique les valeurs depuis $jeu
		if (is_array($jeu)) {
			for ($l=1; $l<10; $l++) {
				for ($c=1; $c<10; $c++) {
					if ($jeu[$l-1][$c-1]>0) {
						$this->definis($l, $c, $jeu[$l-1][$c-1]);
					}
				}
			}
		}
		
		return true;
	}
	
	// Fixe la valeur d'une case et réduit les possibilités pour les
	// cases de la même ligne, de la même colonne et du même carré
	function definis($ligne, $colonne, $valeur) {
		$this->valeur[$ligne][$colonne] = $valeur;
		$this->possible[$ligne][$colonne] = array($valeur=>$valeur);
		for ($l=1; $l<10; $l++) {
			if ($l != $ligne) unset($this->possible[$l][$colonne][$valeur]);
		}
		for ($c=1; $c<10; $c++) {
			if ($c != $colonne) unset($this->possible[$ligne][$c][$valeur]);
		}
		$l0 = floor(($ligne-1)/3)*3;
		$c0 = floor(($colonne-1)/3)*3;
		for ($l=1; $l<4; $l++) {
			for ($c=1; $c<4; $c++) {
				if ($c+$c0!=$colonne || $l+$l0!=$ligne) {
					unset($this->possible[$l0+$l][$c0+$c][$valeur]);
				}
			}
		}
		return $valeur;
	}
	
	// Raisonnement 1 : quand il n'y a qu'une possibilité dans une case, c'est la valeur
	function oblige() {
		for ($ligne=1; $ligne<10; $ligne++) {
			for ($colonne=1; $colonne<10; $colonne++) {
				if ($this->valeur[$ligne][$colonne]==null && count($this->possible[$ligne][$colonne])==1) {
					reset($this->possible[$ligne][$colonne]);
					$this->definis($ligne, $colonne, current($this->possible[$ligne][$colonne]));
					return array($ligne, $colonne);
				}
			}
		}
		return false;
	} // end function oblige;
	
	// Raisonnement 2a : quand dans une ligne, un chiffre a une seule position possible, c'est celle là
	function dernierLigne() {
		// Par ligne
		for ($ligne=1; $ligne<10; $ligne++) {
			for ($valeur=1; $valeur<10; $valeur++) {
				$posValeur = array();
				for ($colonne=1; $colonne<10; $colonne++) {
					if ($this->valeur[$ligne][$colonne]==$valeur) {
						break;
					}
					if (in_array($valeur, $this->possible[$ligne][$colonne])) {
						$posValeur[$colonne]=$colonne;
					}
				} // end for colonne
				if (count($posValeur)==1) {
					reset($posValeur);
					$this->definis($ligne, current($posValeur), $valeur);
					return array($ligne, current($posValeur));
				}
				unset($posValeur);
			} // end for valeur
		} // end for ligne
		return false;
	}

	// Raisonnement 2b : quand dans une colonne, un chiffre a une seule position possible, c'est celle là
	function dernierColonne() {
		// par colonne
		for ($colonne=1; $colonne<10; $colonne++) {
			for ($valeur=1; $valeur<10; $valeur++) {
				$posValeur = array();
				for ($ligne=1; $ligne<10; $ligne++) {
					if ($this->valeur[$ligne][$colonne]==$valeur) {
						break;
					}
					if (in_array($valeur, $this->possible[$ligne][$colonne])) {
						$posValeur[$ligne]=$ligne;
					}
				} // end for colonne
				if (count($posValeur)==1) {
					reset($posValeur);
					$this->definis(current($posValeur), $colonne, $valeur);
					return array(current($posValeur), $colonne);
				}
				unset($posValeur);
			} // end for valeur
		} // end for ligne
		return false;
	}

	// Raisonnement 2c : quand dans un carré, un chiffre a une seule position possible, c'est celle là
	function dernierCarre() {
		// par carré
		for ($cube=1; $cube<10; $cube++) {
			for ($valeur=1; $valeur<10; $valeur++) {
				$posValeur = array();
				for ($case=1; $case<10; $case++) {
					$ligne = 1 + floor(($case-1)/3) + 3*floor(($cube-1)/3);
					$colonne = 1 + ($case-1)%3 + 3*(($cube-1)%3);
					//echo "<p>cube $cube, case $case : ligne $ligne, colonne $colonne</p>";
					if ($this->valeur[$ligne][$colonne]==$valeur) {
						break;
					}
					if (in_array($valeur, $this->possible[$ligne][$colonne])) {
						$posValeur[]=array($ligne, $colonne);
					}
				} // end for case
				if (count($posValeur)==1) {
					$this->definis($posValeur[0][0], $posValeur[0][1], $valeur);
					return array($posValeur[0][0], $posValeur[0][1]);
				}
				unset($posValeur);
			} // end for valeur
		} // end for cube
		return false;
	}
	
	// Enchaîne les raisonnements purement logiques
	function resoudreLogique() {
	global $n;
		while (true) {
			$n++;
			if ($this->probleme()) {
				return false;
			}
			if ($quel = $this->oblige()) {
				echo "<hr/>($n) Oblige\n";
			}
			elseif ($quel = $this->dernierColonne()) {
				echo "<hr/>($n) Dernier colonne\n";
			}
			elseif ($quel = $this->dernierLigne()) {
				echo "<hr/>($n) Dernier ligne\n";
			}
			elseif ($quel = $this->dernierCarre()) {
				echo "<hr/>($n) Dernier carr&eacute;\n";
			}
			if ($quel) {
				$this->affiche(false, $quel);
				continue;				
			}
			break;
		}
		return true;
	}

	// On fait une hypothèse. C'est une solution facile quand on ne sait pas
	// comment s'en sortir
	function hypothese($cas) {
	global $n;
		echo "<hr/>($n) Hypoth&egrave;se\n";
		// recherche de la case ayant le moins de possibilites
		$this->affiche(true);
		$plusPetit=10;
		for ($ligne=1; $ligne<10; $ligne++) {
			for ($colonne=1; $colonne<10; $colonne++) {
				if (($nbPos=count($this->possible[$ligne][$colonne])) > 1) {
					if ($nbPos<$plusPetit) {
						$plusPetit = $nbPos;
						$posPlusPetit = array($ligne, $colonne);
					}
				}
			}
		}
		// quand on a trouvé, on prend la valeur possible suivante (ou la 
		// première si c'est la première hypothèse qu'on fait
		reset($this->possible[$posPlusPetit[0]][$posPlusPetit[1]]);
		for ($m=0; $m<$cas; $m++) next($this->possible[$posPlusPetit[0]][$posPlusPetit[1]]);
		$this->definis($posPlusPetit[0], $posPlusPetit[1], current($this->possible[$posPlusPetit[0]][$posPlusPetit[1]]));
		$n++;
		$this->affiche(false, $posPlusPetit);
		return true;
	}

	// Le jeu est-il terminé ?
	function termine() {
		for ($ligne=1; $ligne<10; $ligne++) {
			for ($colonne=1; $colonne<10; $colonne++) {
				if ($this->valeur[$ligne][$colonne]==null) {
					return false;
				}
			}
		}
		return true;
	}
	
	// Le jeu est-il devenu impossible ?
	// C'est à dire qu'on a fait une mauvaise hypothèse
	// Un problème est détecté par une case non trouvée pour laquelle
	// il n'y aurait aucune possibilité
	function probleme() {
		for ($ligne=1; $ligne<10; $ligne++) {
			for ($colonne=1; $colonne<10; $colonne++) {
				if (count($this->possible[$ligne][$colonne])==0) {
					echo "<h1>Probl&egrave;me</h1>";
					$this->affiche(true);
					return true;
				}
			}
		}
		return false;
	}
	
	// Affichage du jeu dans un tableau en html.
	// si le premier paramètre est à true, on verra les possibilités pour
	// chaque case
	// si le second paramètre est indiqué (paire ligne/colonne) on mettra
	// la case en valeur : sert pour indiquer la dernière case trouvé
	function affiche($possibles=false, $focus=null) {
		echo "<table class='general'>\n";
		for ($ligne=1; $ligne<10; $ligne+=3) {
			echo " <tr>";
			for ($colonne=1; $colonne<10; $colonne+=3) {
				echo "  <td>\n";
				echo "   <table class='inter'>\n";
				for ($ligne2=0; $ligne2<3; $ligne2++) {
					echo "    <tr>\n";
					for ($colonne2=0; $colonne2<3; $colonne2++) {
						echo "     <td".
						 ($focus!=null && $ligne+$ligne2==$focus[0] && $colonne+$colonne2==$focus[1] ? " bgcolor='red'" : "").
						 ">";
						if (! $possibles) {
							echo ($this->valeur[$ligne+$ligne2][$colonne+$colonne2]===null ? "." : $this->valeur[$ligne+$ligne2][$colonne+$colonne2]);
						} else {
							$val = $this->valeur[$ligne+$ligne2][$colonne+$colonne2];
							$possible = $this->possible[$ligne+$ligne2][$colonne+$colonne2];
							$pb = (count($possible)==0);
							echo "<table". ($pb ? " bgcolor='red'" : ""). ">";
							for ($i=0; $i<3; $i++) {
								echo "<tr>";
								for ($j=0; $j<3; $j++) {
									$vala = 1 + $i*3 + $j;
									echo "<td>";
									if (in_array($vala, $possible)) 
										echo sprintf(($vala == $val ? "<strong>%s</strong>" : "%s"), $vala); else echo "&nbsp;";
									echo "</td>";
								}
								echo "</tr>";
							}
							echo "</table>";
						} // end if
						echo "</td>\n";
					} // end for colonne2
					echo "    </tr>\n";
				} // end for ligne2
				echo "   </table>\n";
				echo "  </td>\n";
			} // end for colonne
			echo " </tr>\n";
		} // end for ligne
		echo "</table>\n";
		return true;
	} // end function affiche

} // end class sudoku


// Sortie de l'entête html et d'un peu de style
echo "<html>\n";
echo <<<HEAD
<head>
 <style type="text/css" media="all">
  td { text-align:center; }
  .general table { border-style:outset; border-width:3px; border-color:black; }
  .inter table { border-style:outset; border-width:1px; border-color:gray; }
  .inter { font-family:monospace; }
 </style>
</head>
<body>
HEAD;

// Quelques parties
$partieVide = array( 
	array(0,0,0, 0,0,0, 0,0,0),
	array(0,0,0, 0,0,0, 0,0,0),
	array(0,0,0, 0,0,0, 0,0,0),

	array(0,0,0, 0,0,0, 0,0,0),
	array(0,0,0, 0,0,0, 0,0,0),
	array(0,0,0, 0,0,0, 0,0,0),

	array(0,0,0, 0,0,0, 0,0,0),
	array(0,0,0, 0,0,0, 0,0,0),
	array(0,0,0, 0,0,0, 0,0,0),
	);

//CONNEXION A NC POUR RECUPERATION DU SUDOKU

//***fonction connect($path), argument : $path / returns réponse server :  $output
function connect($path)
{
	
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
//***fin de la fonction connect***

// connexion 
$path="https://www.newbiecontest.org/epreuves/prog/progsudoku.php";
$init_connect=connect($path);

//extraction de la grille de sudoku
$split=explode("</td>",$init_connect);

//print_r($split);
$i=0;
foreach ($split as $value)
{
	$numero[$i]=substr("$value",-1);
	if (is_numeric($numero[$i])) 
	{
		$i++;
	}
	
}
array_pop($numero);
print_r($numero);



//Formatage bien degueu de la grille 

$ligne0=$numero[0].$numero[1].$numero[2].$numero[9].$numero[10].$numero[11].$numero[18].$numero[19].$numero[20];
$ligne1=$numero[3].$numero[4].$numero[5].$numero[12].$numero[13].$numero[14].$numero[21].$numero[22].$numero[23];
$ligne2=$numero[6].$numero[7].$numero[8].$numero[15].$numero[16].$numero[17].$numero[24].$numero[25].$numero[26];
$ligne3=$numero[27].$numero[28].$numero[29].$numero[36].$numero[37].$numero[38].$numero[45].$numero[46].$numero[47];
$ligne4=$numero[30].$numero[31].$numero[32].$numero[39].$numero[40].$numero[41].$numero[48].$numero[49].$numero[50];
$ligne5=$numero[33].$numero[34].$numero[35].$numero[42].$numero[43].$numero[44].$numero[51].$numero[52].$numero[53];
$ligne6=$numero[54].$numero[55].$numero[56].$numero[63].$numero[64].$numero[65].$numero[72].$numero[73].$numero[74];
$ligne7=$numero[57].$numero[58].$numero[59].$numero[66].$numero[67].$numero[68].$numero[75].$numero[76].$numero[77];
$ligne8=$numero[60].$numero[61].$numero[62].$numero[69].$numero[70].$numero[71].$numero[78].$numero[79].$numero[80];

echo "\r\n\r\ndebug ligne0 = ".$ligne0."\r\n\r\n";
echo "\r\n\r\ndebug ligne1 = ".$ligne1."\r\n\r\n";
echo "\r\n\r\ndebug ligne2 = ".$ligne2."\r\n\r\n";
echo "\r\n\r\ndebug ligne3 = ".$ligne3."\r\n\r\n";
echo "\r\n\r\ndebug ligne4 = ".$ligne4."\r\n\r\n";


$concat=trim($ligne0).trim($ligne1).trim($ligne2).trim($ligne3).trim($ligne4).trim($ligne5).trim($ligne6).trim($ligne7).trim($ligne8);
$grille=str_split($concat);

print_r($grille);


// injection de la grille formatee dans la $partieVide
$arr_i=0;
$arr_j=0;
foreach ($grille as $value)
{
	$partieVide[$arr_i][$arr_j]=$value;
	$arr_j++;
	if ($arr_j == 9)
	{
		$arr_j = 0;
		$arr_i++; 
	}
	
}
// *** end of Bullshit ***






// difficulté moyenne
$partie1 = array( 
	array(1,0,3, 0,0,5, 7,0,0),
	array(0,7,5, 0,9,0, 1,3,0),
	array(0,0,8, 7,1,0, 5,0,9),

	array(3,0,0, 0,0,8, 0,0,0),
	array(0,0,2, 0,0,0, 4,0,0),
	array(0,0,0, 6,0,0, 0,0,2),

	array(2,0,4, 0,6,9, 8,0,0),
	array(0,3,6, 0,4,0, 2,9,0),
	array(0,0,7, 1,0,0, 3,0,4),
	);

// difficile
$partie2 = array( 
	array(0,9,0, 0,0,3, 0,1,0),
	array(2,0,8, 0,0,7, 3,0,0),
	array(4,0,0, 0,0,0, 5,8,0),

	array(0,0,4, 5,0,8, 0,0,0),
	array(0,8,0, 9,0,1, 0,7,0),
	array(0,0,0, 7,0,2, 4,0,0),

	array(0,4,6, 0,0,0, 0,0,2),
	array(0,0,9, 6,0,0, 8,0,1),
	array(0,2,0, 3,0,0, 0,5,0),
	);

// difficile
$partie3 = array( 
	array(0,0,0, 0,0,0, 0,7,1),
	array(0,0,0, 5,6,0, 3,4,8),
	array(0,0,8, 0,3,0, 6,5,0),

	array(0,6,0, 0,0,4, 0,0,3),
	array(0,8,2, 0,0,0, 1,6,0),
	array(3,0,0, 6,0,0, 0,2,0),

	array(0,4,3, 0,9,0, 2,0,0),
	array(1,9,7, 0,2,6, 0,0,0),
	array(8,2,0, 0,0,0, 0,0,0),
	);
$partie4 = array( 
	array(2,0,0, 4,0,6, 0,7,0),
	array(0,0,4, 0,0,2, 0,0,0),
	array(1,5,0, 9,7,0, 0,0,0),

	array(0,3,0, 0,6,0, 4,0,0),
	array(7,0,1, 0,4,0, 9,0,3),
	array(0,0,8, 0,9,0, 0,1,0),

	array(0,0,0, 0,5,4, 0,2,9),
	array(0,0,0, 7,0,0, 8,0,0),
	array(0,2,0, 6,0,9, 0,0,1),
	);
	
// speciale anti-algorythm
$partie5 = array( 
	array(9,0,0, 1,0,4, 0,0,2),
	array(0,8,0, 0,6,0, 0,7,0),
	array(0,0,0, 0,0,0, 0,0,0),

	array(4,0,0, 0,0,0, 0,0,1),
	array(0,7,0, 0,0,0, 0,3,0),
	array(3,0,0, 0,0,0, 0,0,7),

	array(0,0,0, 0,0,0, 0,0,0),
	array(0,3,0, 0,7,0, 0,8,0),
	array(1,0,0, 2,0,9, 0,0,4),
	);
$partie6 = array( 
	array(0,0,0, 0,2,5, 0,0,0),
	array(0,0,0, 0,0,7, 3,0,0),
	array(0,0,0, 0,0,0, 4,8,0),

	array(0,0,0, 0,0,0, 0,5,9),
	array(7,0,0, 0,0,0, 0,0,2),
	array(3,8,0, 0,0,0, 0,0,0),

	array(0,9,5, 0,0,0, 0,0,0),
	array(0,0,1, 6,0,0, 0,0,0),
	array(0,0,0, 8,3,0, 0,0,0),
	);

$partie = new sudoku();
// Changer le nom de la variable dans la ligne suivante pour essayer un 
// autre jeu.
$partie->nouveau($partieVide);
$partie->affiche(false);

$n=0;
$temps = microtime(true);

// On enregistre la situation sûre.
$situationSure = array($partie->valeur, $partie->possible);
$i=0;
$nbHypothese=$nbProbleme=0;
while (true) {
	// On commence par ne rechercher que les case calculés par pur raisonnement
	$ok = $partie->resoudreLogique();

	// si c'est terminé, c'est gagné
	if ($partie->termine()) break;

	// ah, un problème, il va falloir revenir en arrière
	if (! $ok) {
		echo "<hr/><h3>On repart dans une situation sure</h3>";
		// On revient à la dernière situation sûre
		$partie->valeur = $situationSure[0];
		$partie->possible = $situationSure[1];
		$nbProbleme++;
		$i++;
	} else {
		$i=0;
	}
	// On enregistre la dernière situation sûre
	$situationSure = array($partie->valeur, $partie->possible);
	// On fait une hypothèse
	$quel = $partie->hypothese($i);
	$nbHypothese++;
} // end while true

$temps = microtime(true) - $temps;

// Affiche des résultats
if ($partie->termine()) {
	echo "<h3>J'ai trouv&eacute; en ". ($temps*1000). " ms !</h3>";
	echo "<p>en faisant $nbHypothese hypoth&egrave;se(s).</p>";
	echo "<p>en rencontrant $nbProbleme probl&egrave;me(s) obligeant &agrave; revenir en arri&egrave;re.</p>";
    $partie->affiche();
} else {
	echo "<h3>Je n'ai pas trouv&eacute;... en ". ($temps*1000). " ms</h3>";
	$partie->affiche(true);
}

echo "<p>Copyright Lo&iuml;c Dayot, (janvier 2010) - licence CeCILL</p>";
echo "</body>\n</html>\n";
?>

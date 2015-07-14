<?php
if( file_exists ( "out2.txt"))
{
	$var = file_get_contents("out2.txt");
}
$tri=explode("en rencontrant",$var);
echo "\r\nDEBUG : valeur de tri[1] : ".$tri[1];

$tri2=explode("</td>",$tri[1]);

echo "\r\nDEBUG : valeur de tri2[1] : ".$tri2[1];

$i=0;
foreach ( $tri2 as $value)
{
	$numero[$i]=trim(substr("$value", -1));
	$i++;
}
$clean=array_values(array_filter($numero));

print_r($clean);


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


$reponse=trim($ligne0)."-".trim($ligne1)."-".trim($ligne2)."-".trim($ligne3)."-".trim($ligne4)."-".trim($ligne5)."-".trim($ligne6)."-".trim($ligne7)."-".trim($ligne8);

echo "\r\nreponse :  ".$reponse;
?>

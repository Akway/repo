<?php

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><title>NC sh00tb0x</title>
 	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head><body>';
	  
include("common.php");

if(!empty($message) && !empty($pseudo))
{
	$pseudo=nettoie($pseudo);
	$message=nettoie($message);
	if(!verif_mail($email))
	{
		$email="";
	}
	$heure=date("h:i");

	if(verif_flood())
	{
		echo "Vous ne pouvez pas poster de message avant un délais de 30 secondes<br><a href='javascript:history.go(-1);'>Retour</a>";
	}
	else
	{
		$sql="INSERT INTO shootbox VALUES ('','$pseudo','$email','$message','$heure')";
		mysql_query($sql) or die("Erreur SQL");
		echo "       Message posté avec succès !<br>                        <a href='javascript:window.close();'>Retour</a>";
	}
}
else
{
	echo'<form action="" method="post"><table width="234" border="0"><tr><td width="60">Pseudo : </td><td width="158"><input name="pseudo" type="text" id="pseudo" size="25"></td></tr>
  		<tr><td>Email : </td><td><input name="email" type="text" id="email" size="25"></td></tr><tr><td colspan="2"><div align="center">Message:</div></td></tr><tr><td height="142" colspan="2"><textarea name="message" cols="28" rows="10" id="message"></textarea></td>
  		</tr></table><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" value="Envoyer"></form></p>';
}
mysql_close();
echo '</body></html>';
?>


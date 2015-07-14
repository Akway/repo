<?php

function nettoyage($nb_messages)
{
	$query=mysql_query("SELECT id FROM shootbox ORDER BY id DESC LIMIT 0,1") or die("Erreur SQL");
	$data=mysql_fetch_array($query);
	$last_id=$data['id'];
	$query=mysql_query("DELETE FROM shootbox WHERE id < ".intval($last_id-($nb_messages-1))) or die("Erreur SQL");
}

function nettoie($var)
{
	$var=trim(htmlspecialchars($var));
	if(!get_magic_quotes_gpc())
	{
		$var=addslashes($var);
	}
	return $var;
}

function verif_mail($var)
{
	if(!eregi("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $var))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function vraie_ip()
{
	if($_SERVER['HTTP_X_FORWARDED_FOR']!="")
	{
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} 
	else 
	{
		$ip = getenv("REMOTE_ADDR");
	}
	
return $ip;
}

function verif_flood()
{
	$now=time();
	$end_flood=time()+30;
	$ip=vraie_ip();
	$req=mysql_query("DELETE FROM shootbox_flood WHERE end_flood < $now") or die("Erreur SQL merci de le signaler aux administrateurs");
	$req=mysql_query("SELECT * FROM shootbox_flood WHERE ip= '".$ip."' AND end_flood > $now") or die("Erreur SQL merci de le signaler aux administrateurs");
	$test=mysql_num_rows($req);
	if($test==1) 
	{
		return true;
	}
	else
	{
		$req=mysql_query("INSERT INTO shootbox_flood VALUES ('$now','$end_flood','$ip')") or die("Erreur SQL merci de le signaler aux administrateurs");
		return false;
	}
}

function verif_id($id)
{
	if(is_numeric($id))
	{
		$query=mysql_query("SELECT id FROM shootbox WHERE id = '".$id."'");
		if(mysql_num_rows($query)==1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
function admin()
{
	echo"<center>Hello  super admin :)<br></center>";
	$sql="SELECT * FROM shootbox ORDER BY id DESC";
	$query=mysql_query($sql) or die("Erreur SQL");
	while($data=mysql_fetch_array($query))
	{	
		echo '<center><table width="200" border="1" style="border-collapse:collapse;"><tr><td width="80"><a href="mailto:'.$data['email'].'">'.ucfirst($data['pseudo']).'</a></td>
      	      <td>'.$data['heure'].'</td></tr><tr><td colspan="2">'.stripslashes($data['message']).'</td></tr><tr><td colspan="2"><div align="center"><a href="admin.php?action=supprimer&id='.$data['id'].'">Supprimer</a></div></td></tr></table></center>';
	}

	
	echo '<div align="center"><form action="admin.php?action=ajouter_admin" method="post"><p>Ajouter un Administrateur/Mod&eacute;rateur</p><table width="321" border="0"><tr><td width="165">Pseudo : </td><td width="146"><input name="login" type="text" id="login"></td></tr><tr><td>Password : </td><td><input name="password" type="password" id="password"></td></tr></table><table width="323" border="0"><tr>
         <td width="192">Type :&nbsp;&nbsp;<input type="radio" name="rank2" value="modo">Mod&eacute;rateur</td><td width="121"><input type="radio" name="rank2" value="admin">Admin</td></tr></table><p> <input type="submit" name="Submit" value="Ajouter"></p></form><form action="admin.php?action=supprimer_admin" method="post"><p>Supprimer un Administrateur/Mod&eacute;rateur</p>
         <table width="322" border="0"><tr><td width="168">Pseudo : </td><td width="144"><input type="text" name="login"></td></tr></table><p><input type="submit" name="Submit2" value="Supprimer"></p></form><p>&nbsp; </p><p>&nbsp;  </p></div>';
	
}

function modo()
{
	echo"<center>Hello petit modo :)<br></center>";
	$sql="SELECT * FROM shootbox ORDER BY id DESC";
	$query=mysql_query($sql) or die("Erreur SQL");
	while($data=mysql_fetch_array($query))
	{	
		echo '<center><table width="200" border="1" style="border-collapse:collapse;"><tr><td width="80"><a href="mailto:'.$data['email'].'">'.ucfirst($data['pseudo']).'</a></td>
      	      <td>'.$data['heure'].'</td></tr><tr><td colspan="2">'.stripslashes($data['message']).'</td></tr><tr><td colspan="2"><div align="center"><a href="admin.php?action=supprimer&id='.$data['id'].'">Supprimer</a></div></td></tr></table></center>';
	}
	
}
?>

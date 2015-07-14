<?php

echo'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><title>NC Sh00tbox</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head><body><div align="center">
     <p><strong>NC Sh00tb0x </strong></p>';
  
  
include("common.php");
 
nettoyage($nb_messages);
 
$sql="SELECT * FROM shootbox ORDER BY id DESC";
$query=mysql_query($sql) or die("Erreur SQL");

echo '<SCRIPT language="javascript">
      function popup(page) 
	  {
      window.open(page,"Poster un message","toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=270,height=350");
      }
      </SCRIPT>';
	  
while($data=mysql_fetch_array($query))
{
	echo '<table width="200" border="1" style="border-collapse:collapse;"><tr><td width="80"><a href="mailto:'.$data['email'].'">'.ucfirst($data['pseudo']).'</a></td>
          <td>'.$data['heure'].'</td></tr><tr><td colspan="2">'.stripslashes($data['message']).'</td></tr></table>';
}
mysql_close($db);

echo '<p><input type="submit" name="Submit" value="Poster" onClick="javascript:popup(\'poster.php\');"></p></div></body></html>';
?>


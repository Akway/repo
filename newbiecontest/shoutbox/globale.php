<?php

 foreach ($_GET as $key => $value) 
 {
$$key = $value;
echo "\r\nGET : ".$$key."=".$value;
 }
 
  foreach ($_POST as $key => $value) 
 {
   $$key = $value;
echo "\r\nPOST : ".$$key."=".$value;
 }
?>

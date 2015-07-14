
<?php
// Définit l'image
$im = imagecreatefrompng('./laby.png');

$colors   = Array();
$colors[] = imagecolorexact($im, 255, 0, 0);
$colors[] = imagecolorexact($im, 0, 0, 0);
$colors[] = imagecolorexact($im, 255, 255, 255);
$colors[] = imagecolorexact($im, 100, 255, 52);

print_r($colors);

// Libération de la mémoire
imagedestroy($im);
?>


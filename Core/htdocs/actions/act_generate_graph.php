<?php
// Add values to the graph
$graphValues=array(50,100,75,25,150,65,120,50);

// Define .PNG image

header("Content-type: image/png");

$imgWidth=250;

$imgHeight=250;

// Create image and define colors

$image=imagecreate($imgWidth, $imgHeight);

$colorWhite=imagecolorallocate($image, 255, 255, 255);

$colorGrey=imagecolorallocate($image, 192, 192, 192);

$colorDarkBlue=imagecolorallocate($image, 104, 157, 228);

$colorLightBlue=imagecolorallocate($image, 184, 212, 250);

// Create border around image

imageline($image, 0, 0, 0, 250, $colorGrey);

imageline($image, 0, 0, 250, 0, $colorGrey);

imageline($image, 249, 0, 249, 249, $colorGrey);

imageline($image, 0, 249, 249, 249, $colorGrey);

// Create grid

for ($i=1; $i<11; $i++){

imageline($image, $i*25, 0, $i*25, 255, $colorGrey);

imageline($image, 0, $i*25, 255, $i*25, $colorGrey);

}

// Create bar charts

for ($i=0; $i<7; $i++){

imagefilledrectangle($image, $i*25, (250-$graphValues[$i]), ($i+1)*25, 250, $colorDarkBlue);

imagefilledrectangle($image, ($i*25)+1, (250-$graphValues[$i])+1, (($i+1)*25)-5, 248, $colorLightBlue);

}

// Output graph and clear image from memory

imagepng($image);

imagedestroy($image);

?>
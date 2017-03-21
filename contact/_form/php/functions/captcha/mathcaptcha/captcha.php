<?php

    include '../../../functions/sessions/sessions.php';
 
    secure_session($sessionname);
	
	if(!isset($_SESSION['math']))
        
        $math = 'ERROR';
        
    else

		$math = $_SESSION['math'];
		
		$number1 = rand(1,20) * rand(1,3);
		$number2 = rand(1,20) * rand(1,3);
		$math = $number1 + $number2;  

		$total = ''.$number1.' + '.$number2.'';  

		$_SESSION['math'] = $math;

		$font = 'fonts/Arial.ttf';

		//Change the numbers to adjust image
        $image = imagecreatetruecolor(75,15);
        $black = imagecolorallocate($image,0,0,0);
        $color = imagecolorallocate($image,136,136,136);
        $white = imagecolorallocate($image,255,255,255);

		imagefilledrectangle($image,0,0,399,99,$white);
		imagettftext($image,10,0,18,13,$color,$font,$total);

		header("Cache-Control:no-cache, no-store, must-revalidate");
		header("Pragma:no-cache");
		header("Expires:0");
		header('Content-Type:image/png');
		
		imagepng($image);
		imagedestroy($image);

?>
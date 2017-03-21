<?php

    include '../../../functions/sessions/sessions.php';
 
    secure_session($sessionname);
			
    if(!isset($_SESSION['alpha']))
        
        $alpha = 'ERROR';
        
    else
        
        $alpha = $_SESSION['alpha'];

        $characteres = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'),0,4));

        $alpha = rand(1,7) . rand(1,7) . $characteres;

        $_SESSION['alpha'] = $alpha;

        $font = 'fonts/Arial.ttf';

		//Change the numbers to adjust image
        $image = imagecreatetruecolor(75,15);
        $black = imagecolorallocate($image,0,0,0);
        $color = imagecolorallocate($image,136,136,136);
        $white = imagecolorallocate($image,255,255,255);

        imagefilledrectangle($image,0,0,399,99,$white);
        imagettftext($image,10,0,10,13,$color,$font,$alpha);
		
		header("Cache-Control:no-cache, no-store, must-revalidate");
		header("Pragma:no-cache");
		header("Expires:0");
		header('Content-Type:image/png');
		
		imagepng($image);
		imagedestroy($image);

?>
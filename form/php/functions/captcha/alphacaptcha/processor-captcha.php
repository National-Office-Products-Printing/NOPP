<?php

    include '../../../functions/sessions/sessions.php';
 
    secure_session($sessionname);
   
	if(strtoupper($_REQUEST['alphacaptcha']) == $_SESSION['alpha']) {
		
		echo 'true';
		
	} else {
		
		echo 'false';
		
	}
	
?>
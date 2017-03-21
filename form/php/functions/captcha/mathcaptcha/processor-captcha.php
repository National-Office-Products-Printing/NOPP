<?php

	include '../../../functions/sessions/sessions.php';
 
    secure_session($sessionname);
   
	if(strtoupper($_REQUEST['mathcaptcha']) == $_SESSION['math']) {
		
		echo 'true';
		
	} else {
		
		echo 'false';
		
	}

?>
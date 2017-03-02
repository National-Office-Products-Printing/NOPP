<?php

	include '../../../functions/sessions/sessions.php';
 
    secure_session($sessionname);
	
	include '../../../libraries/captcha/autoload.php';
	include '../../../settings/settings.php';
	
	$finalrecaptcha = strip_tags(trim($_REQUEST["g-recaptcha-response"]));
	
	// CALL FOR VALIDATION OF RECAPTCHA!
	$question = new \ReCaptcha\ReCaptcha($secretkey);
	$response = $question->verify($finalrecaptcha,$finaluserip);
   
	if($response->isSuccess()) {
		
		echo 'true';
		
	} else {
		
		echo 'false';
		
	}

?>
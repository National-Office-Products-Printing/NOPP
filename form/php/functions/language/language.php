<?php

    if($multilanguage == true) {
		
		$requesturi = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
		$arraylanguage  = explode('/', $requesturi);
		
		switch ($arraylanguage){
			case 'en':
				$languages = 'en';
				break;
			case 'pt':
				$languages = 'pt';
				break;
			default:
				$languages = 'en';
		}
				
	} else {
		
		$languages = $language;
				
	}

?>
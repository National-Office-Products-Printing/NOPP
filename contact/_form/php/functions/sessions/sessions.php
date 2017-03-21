<?php

    $sessionname = 'SECURE_SID';

    function secure_session($sessionname) {
		
		ini_set('session.use_cookies',1);
		ini_set('session.use_only_cookies',1);
		ini_set('session.entropy_file','/dev/urandom');
		ini_set('session.hash_function','whirlpool'); 
		ini_set('session.entropy_length','512');
		ini_set('session.use_trans_sid',0);
        		
		session_name($sessionname);
		
		$expire = 0;
		$hostpath = '/';
		$hostname = $_SERVER['HTTP_HOST'];
		$secure = false;
		$httponly = true;
		 
		session_set_cookie_params(
			$expire, 
			$hostpath, 
			$hostname, 
			$secure, 
			$httponly
		);
		 
		session_start();

	}

?>
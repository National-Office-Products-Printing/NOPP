<?php

/*	--------------------------------------------------
	:: CONFIG SETTINGS
	-------------------------------------------------- */

	// Enter your Name here!
    $yourname = 'Jon Schuster';
	
	// Enter your Email here if you decide turn false according department!
    $youremail = 'aonmedia@live.com';

	// Send emails according with department
	$according_department = true;
	
	// Enter your Email Address here according to department!
  $admin_email_one = 'shannon@nopp.com';
	$admin_name_one = 'Shannon Veum';
	
  $admin_email_two = 'cvan@nopp.com';
	$admin_name_two = 'Curt Van';
	
  $admin_email_three = 'kevin@nopp.com';
	$admin_name_three = 'Kevin Pomeroy';

	$admin_email_four = 'officeproducts@nopp.com';
	$admin_name_four = 'Kaitlin Hill';

	$admin_email_five = 'graphics@nopp.com';
	$admin_name_five = 'Jon Schuster';
	
	// Turn true SMTP if you want don´t forget to turn false sendmail and mail 	
	$SMTP = false;
	
	$protocol = 'ssl';                       // Can be 'ssl' or 'tls' or ''
	$host = 'your-host-server';
	$port = 465;                             // Can be 465, 587, 25
	$username = 'your-host-username';        // Need to be equal to $youremail
	$password = 'your-host-password';
	
	$sendmail = false;
	$mail = true;
	
	// Enter your default time zone
	date_default_timezone_set('America/Detroit');
	
	$localtime = date("l jS \of F Y h:i:s A");	

	// Enter your URL here without http:// only domain!
	$url = 'nopp.com/';
	
	if ($_SERVER['SERVER_NAME'] == $url) {
	    // Enter your BASEURL here without WWW!
		$baseurl = 'http://nopp.com/contact/';
	}
	
	// Enter your Website here!
	$website = 'www.nopp.com';

	// Enter Company here!
	$company = 'National Office Products & Printing, Inc.';
	
	// If you want file upload turn this to true!
	$upload = false;
	
	// If you want captcha turn this to true!
	$captcha = true;
	
	// If you want autoresponder turn this to true!
	$automessage = true;
	
	// If you want use mysql database turn this to true if you don´t want duplicate emails!
	$duplicate_email = true;
	
	$mysql 			     = false;										       
    $mysqltable_name     = "your-table-name";

    $hostname_Connection = "your-host-name";
    $database_Connection = "your-database-name";
    $username_Connection = "your-username";
    $password_Connection = "your-password";

    if ($mysql) {
        $connection = mysql_connect($hostname_Connection, $username_Connection, $password_Connection) or die('<div class="error-message"><i class="icon-close"></i>Failed to connect to MySQL '.mysql_error().'</div>'); 
        $database = mysql_select_db ($database_Connection, $connection) or die ('<div class="error-message"><i class="icon-close"></i>Failed to connect to MySQL '.mysql_error().'</div>');
    }

?>
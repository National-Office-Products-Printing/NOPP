<?php 

    if($mail == true || $smtpauth == true || $smtpnoauth == true){
		$automail = new PHPMailer();
		if($smtpauth == true){
			$automail->isSMTP();
			$automail->SMTPAuth = true;
			$automail->SMTPSecure = $protocol;
			$automail->Host = $host;
			$automail->Port = $port;
			$automail->Username = $username;
			$automail->Password = $password;
			$automail->SMTPDebug = $smtpdebug;
			$automail->Debugoutput = $debugoutput;
		}
		if($smtpnoauth == true){
			$automail->isSMTP();
			$automail->SMTPAuth = false;
			$automail->SMTPSecure = $protocol;
			$automail->Host = $host;
			$automail->Port = $port;
			$automail->SMTPDebug = $smtpdebug;
			$automail->Debugoutput = $debugoutput;
		}
		$automail->isHTML(true);
		$automail->setFrom($youremail, $yourname);
		$automail->CharSet = $mailcharset;
		$automail->Priority = $mailpriority;
		$automail->Encoding = "base64";
		$automail->Timeout = 200;
		$automail->ContentType = "text/html";
		$automail->addAddress($finalemail, $finalfirstname . ' ' . $finallastname);
		if($merchantsupport == true){	
		    $automail->Subject = $lang['form-automessage-support-subject'];
			$automail->msgHTML($automessagesupport);
		} elseif($merchantservice == true){
			$automail->Subject = $lang['form-automessage-service-subject'];
			$automail->msgHTML($automessageservice);
		} elseif($merchantpayment == true){
			if($onetimepayment == true){
				$automail->Subject = $lang['form-automessage-payment-subject'];
				$automail->msgHTML($automessagepayment);
			} else {
				$automail->Subject = $lang['form-automessage-recurring-subject'];
				$automail->msgHTML($automessagerecurring);
			}
		} 
    }
	
?>
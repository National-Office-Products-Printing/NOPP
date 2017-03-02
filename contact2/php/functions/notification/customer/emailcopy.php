<?php

    if($mail == true || $smtpauth == true || $smtpnoauth == true){
		$emailcopy = new PHPMailer();
		if($smtpauth == true){
			$emailcopy->isSMTP();
			$emailcopy->SMTPAuth = true;
			$emailcopy->SMTPSecure = $protocol;
			$emailcopy->Host = $host;
			$emailcopy->Port = $port;
			$emailcopy->Username = $username;
			$emailcopy->Password = $password;
			$emailcopy->SMTPDebug = $smtpdebug;
			$emailcopy->Debugoutput = $debugoutput;
		}
		if($smtpnoauth == true){
			$emailcopy->isSMTP();
			$emailcopy->SMTPAuth = false;
			$emailcopy->SMTPSecure = $protocol;
			$emailcopy->Host = $host;
			$emailcopy->Port = $port;
			$emailcopy->SMTPDebug = $smtpdebug;
			$emailcopy->Debugoutput = $debugoutput;
		}
		$emailcopy->isHTML(true);
		$emailcopy->setFrom($youremail, $yourname);
		$emailcopy->CharSet = $mailcharset;
		$emailcopy->Priority = $mailpriority;
		$emailcopy->Encoding = "base64";
		$emailcopy->Timeout = 200;
		$emailcopy->ContentType = "text/html";
		$emailcopy->addAddress($finalemail, $finalfirstname . ' ' . $finallastname);
		if($merchantsupport == true){
            $emailcopy->Subject = $lang['form-emailcopy-support-subject'];			
			$emailcopy->msgHTML($messagecopysupport);
		} elseif($merchantservice == true){
			$emailcopy->Subject = $lang['form-emailcopy-service-subject'];
			$emailcopy->msgHTML($messagecopyservice);
		} elseif($merchantpayment == true){
			if($onetimepayment == true){
				$emailcopy->Subject = $lang['form-emailcopy-payment-subject'];
				$emailcopy->msgHTML($messagecopypayment);
			} else {
				$emailcopy->Subject = $lang['form-emailcopy-recurring-subject'];
				$emailcopy->msgHTML($messagecopyrecurring);
			}
		}
    }
	
?>
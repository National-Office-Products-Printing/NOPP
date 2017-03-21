<?php

    if($mail == true || $smtpauth == true || $smtpnoauth == true){
		$adminemail = new PHPMailer();
		if($smtpauth == true){
			$adminemail->isSMTP();
			$adminemail->SMTPAuth = true;
			$adminemail->SMTPSecure = $protocol;
			$adminemail->Host = $host;
			$adminemail->Port = $port;
			$adminemail->Username = $username;
			$adminemail->Password = $password;
			$adminemail->SMTPDebug = $smtpdebug;
			$adminemail->Debugoutput = $debugoutput;
		}
		if($smtpnoauth == true){
			$adminemail->isSMTP();
			$adminemail->SMTPAuth = false;
			$adminemail->SMTPSecure = $protocol;
			$adminemail->Host = $host;
			$adminemail->Port = $port;
			$adminemail->SMTPDebug = $smtpdebug;
			$adminemail->Debugoutput = $debugoutput;
		}
		$adminemail->isHTML(true);
		$adminemail->setFrom($youremail, $yourname);
		$adminemail->CharSet = $mailcharset;
		$adminemail->Priority = $mailpriority;
		$adminemail->Encoding = "base64";
		$adminemail->Timeout = 200;
		$adminemail->ContentType = "text/html";
		$adminemail->addReplyTo($finalemail, $finalfirstname . ' ' . $finallastname);
		$adminemail->addAddress($youremail, $yourname);
		if ($administrators == true) {
			foreach ($multipleadministrators as $emails) {
				$multiple = $emails['email'];
				$adminemail->addAddress($multiple);
			}
		} elseif ($administratorscc == true) {
			foreach ($multipleadministrators as $emailscc) {
				$multiplecc = $emailscc['emailcc'];
				$adminemail->addCC($multiplecc);
			}
		} elseif ($administratorsbcc == true) {
			foreach ($multipleadministrators as $emailsbcc) {
				$multiplebcc = $emailsbcc['emailbcc'];
				$adminemail->addBCC($multiplebcc);
			}
		}
		if($merchantsupport == true){
			if($supportadministrators == true){
				foreach ($finalsupport as $support) {
					$finalsupportemail = $supportarray[$support];
					$adminemail->addAddress($finalsupportemail);
				}
			}
		} elseif($merchantservice == true){
			if($serviceadministrators == true){
				foreach ($finalservice as $service) {
					$finalserviceemail = $servicearray[$service]['email'];
					$adminemail->addAddress($finalserviceemail);
				}
			}
		} elseif ($merchantpayment == true) {
			if($onetimepayment == true){
				if($creditcard == true || $paypal == true){
					if ($paymentadministrators == true) {
						foreach ($finalpayment as $ccpppayment) {
							$finalccpppaymentemail = $onetimepaymentarray[$ccpppayment]['email'];
							$adminemail->addAddress($finalccpppaymentemail);
						}
					}
				} elseif($stripe == true){
					if ($paymentadministrators == true) {
						foreach ($finalpayment as $stpayment) {
							$finalstpaymentemail = $onetimepaymentarray[$stpayment]['email'];
							$adminemail->addAddress($finalstpaymentemail);
						}
					}
				}
		    } else {
				if($creditcard == true || $paypal == true){
					if ($recurringadministrators == true) {
						foreach ($finalrecurring as $ccpprecurring) {
							$finalccpprecurringemail = $recurringpaymentarray[$ccpprecurring]['email'];
							$adminemail->addAddress($finalccpprecurringemail);
						}
					}
				} elseif($stripe == true){
					if ($recurringadministrators == true) {
						foreach ($finalrecurring as $strecurring) {
							$finalstrecurringemail = $recurringpaymentarray[$strecurring]['email'];
							$adminemail->addAddress($finalstrecurringemail);
						}
					}
				}
			}
		}
		if ($upload == true){
			if ($multipleupload == true) {
				if($uploadattachments == true) {
					$adminemail->addAttachment('../upload/normal/multiple/upload-'.$finalnumber1.'-'.$finaltime1.'-'.$finalname1.'');
					$adminemail->addAttachment('../upload/normal/multiple/upload-'.$finalnumber1.'-'.$finaltime2.'-'.$finalname2.'');
					$adminemail->addAttachment('../upload/normal/multiple/upload-'.$finalnumber1.'-'.$finaltime3.'-'.$finalname3.'');
				}
			} elseif ($dropboxmultipleupload == true) {
				if($dropboxattachments == true) {
					$adminemail->addAttachment('../upload/dropbox/multiple/dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxname1.'');
					$adminemail->addAttachment('../upload/dropbox/multiple/dropbox-'.$finalnumber1.'-'.$finaltime2.'-'.$dropboxname2.'');
					$adminemail->addAttachment('../upload/dropbox/multiple/dropbox-'.$finalnumber1.'-'.$finaltime3.'-'.$dropboxname3.'');
				}
			} elseif ($amazonmultipleupload == true) {
				if($amazonattachments == true) {
					$adminemail->addAttachment('../upload/amazon/multiple/amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonname1.'');
					$adminemail->addAttachment('../upload/amazon/multiple/amazon-'.$finalnumber1.'-'.$finaltime2.'-'.$amazonname2.'');
					$adminemail->addAttachment('../upload/amazon/multiple/amazon-'.$finalnumber1.'-'.$finaltime3.'-'.$amazonname3.'');
				}
			} elseif ($singleupload == true) {
				if($uploadattachments == true) {
					$adminemail->addAttachment('../upload/normal/single/upload-'.$finalnumber1.'-'.$finaltime1.'-'.$finalname1.'');
				}
			} elseif ($dropboxsingleupload == true) {
				if($dropboxattachments == true) {
					$adminemail->addAttachment('../upload/dropbox/single/dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxname1.'');
				}
			} elseif ($amazonsingleupload == true) {
				if($amazonattachments == true) {
					$adminemail->addAttachment('../upload/amazon/single/amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonname1.'');
				}
			} elseif ($multipleuploadfiles == true) {
				if($uploadattachments == true) {
					foreach($_FILES["uploadmultiple1"]["name"] as $key => $finalname1){
						$adminemail->addAttachment('../upload/normal/multipleupload/upload-'.$finalnumber1.'-'.$finaltime1.'-'.$finalname1.'');
					}
				}
			} elseif ($dropboxmultipleuploadfiles == true) {
				if($dropboxattachments == true) {
					foreach($_FILES["uploadmultiple1"]["name"] as $key => $dropboxname1){
						$adminemail->addAttachment('../upload/dropbox/multipleupload/dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxname1.'');
					}
				}
			} elseif ($amazonmultipleuploadfiles == true) {
				if($amazonattachments == true) {
					foreach($_FILES["uploadmultiple1"]["name"] as $key => $amazonname1){
						$adminemail->addAttachment('../upload/amazon/multipleupload/amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonname1.'');
					}
				}
			}
		}
		if ($excelreports == true) {
			if($excelattachments == true) {
				$adminemail->addAttachment('../upload/reports/excel/excel-'.$finalnumber1.'.xls');
				$adminemail->addAttachment('../upload/reports/excel/excel-'.$finalnumber1.'.xlsx');
			}
		}
		if($merchantsupport == true) {	
			$adminemail->Subject = $lang['form-message-support-subject'];
			$adminemail->msgHTML($messagesupport);
		} elseif($merchantservice == true){
			$adminemail->Subject = $lang['form-message-service-subject'];
			$adminemail->msgHTML($messageservice);
		} elseif($merchantpayment == true){
			if($onetimepayment == true){
				$adminemail->Subject = $lang['form-message-payment-subject'];
				$adminemail->msgHTML($messagepayment);
			} else {
				$adminemail->Subject = $lang['form-message-recurring-subject'];
				$adminemail->msgHTML($messagerecurring);
			}
		}
	}

?>
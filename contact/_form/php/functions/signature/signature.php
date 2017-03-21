<?php	
	
	$config = new DocuSign\eSign\Configuration();
	$config->setHost($docsignhost);
	$config->addDefaultHeader("X-DocuSign-Authentication", "{\"Username\":\"".$docsignusername."\",\"Password\":\"".$docsignpassword."\",\"IntegratorKey\":\"".$docsignintegratorkey."\"}");

	$apiclient = new DocuSign\eSign\ApiClient($config);
	$authenticationapi = new DocuSign\eSign\Api\AuthenticationApi($apiclient);
	$options = new \DocuSign\eSign\Api\AuthenticationApi\LoginOptions();

	$logininformation = $authenticationapi->login($options);
	if(isset($logininformation) && count($logininformation) > 0){
		$loginaccount = $logininformation->getLoginAccounts()[0];
		if(isset($logininformation)){
			$accountid = $loginaccount->getAccountId();
		}
	}
											
	$envelopeapi = new DocuSign\eSign\Api\EnvelopesApi($apiclient);

	$document = new DocuSign\eSign\Model\Document();
	$document->setDocumentBase64(base64_encode(file_get_contents(dirname(__FILE__).$docsignfile)));
	$document->setName($docsigndocumentname);
	$document->setDocumentId('1');

	$signhere = new \DocuSign\eSign\Model\SignHere();
	$signhere->setXPosition('75');
	$signhere->setYPosition('660');
	$signhere->setDocumentId('1');
	$signhere->setPageNumber('1');
	$signhere->setRecipientId('1');

	$tabs = new DocuSign\eSign\Model\Tabs();
	$tabs->setSignHereTabs(array($signhere));

	$signer = new \DocuSign\eSign\Model\Signer();
	$signer->setEmail($finalemail);
	$signer->setName($finalfirstname . ' ' . $finallastname);
	$signer->setRecipientId('1');
	$signer->setTabs($tabs);

	$recipients = new DocuSign\eSign\Model\Recipients();
	$recipients->setSigners(array($signer));

	$envelopdefinition = new DocuSign\eSign\Model\EnvelopeDefinition();
	$envelopdefinition->setEmailSubject($docsignemaildescription);
	$envelopdefinition->setStatus($docsignstatus);
	$envelopdefinition->setRecipients($recipients);
	$envelopdefinition->setDocuments(array($document));

	$options = new \DocuSign\eSign\Api\EnvelopesApi\CreateEnvelopeOptions();
	$options->setCdseMode(null);
	$options->setMergeRolesOnDraft(null);

	$envelopsummary = $envelopeapi->createEnvelope($accountid, $envelopdefinition, $options);
		
?>
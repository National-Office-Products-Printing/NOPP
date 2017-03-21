<?php 

	use \DrewM\MailChimp\MailChimp;

	$subscribe = new MailChimp($mailchimpkey);
	$result = $subscribe->post(
	    'lists/'.$mailchimplistid.'/members', array(
			'email_address'     => $finalemail,
			'status'            => 'subscribed', // Add Status 'pending' for Confirmation Email
			'merge_fields'      => array( 
			    'FNAME'         => $finalfirstname, 
				'LNAME'         => $finallastname
			)
	    )   
	);
	
	$finalemailhash = $subscribe->subscriberHash($finalemail);
	
	$result = $subscribe->patch(
	    'lists/'.$mailchimplistid.'/members/'.$finalemailhash.'', array(
			'email_address'     => $finalemail,
			'status'            => 'subscribed', // Add Status 'pending' for Confirmation Email
			'merge_fields'      => array( 
			    'FNAME'         => $finalfirstname, 
				'LNAME'         => $finallastname
			)
	    )   
	);

?>
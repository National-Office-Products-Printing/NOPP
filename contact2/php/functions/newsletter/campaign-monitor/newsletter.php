<?php 

	$subscribe = new CS_REST_Subscribers($campaignmonitorlistid,$campaignmonitorkey);
	$result = $subscribe->add(
	    array (
			'EmailAddress' => $finalemail,
			'Name' => $finalfirstname . ' ' . $finallastname,
			'CustomFields' => array(),
			'Resubscribe' => true    // Add Status 'false' for Non Update on Resubscribe!
		)
	);

?>
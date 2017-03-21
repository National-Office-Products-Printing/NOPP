<?php
	
	$amazontmp1 = $_FILES["uploadsingle1"]["tmp_name"];
	
	$amazonbasename1 = $_FILES["uploadsingle1"]["name"];

	$amazonname1  = strtolower($amazonbasename1);

	$client = new S3($accesskey, $accesssecret);

	$client->putBucket($amazonfolder, S3::ACL_PRIVATE);
				
	$response = $client->putObjectFile($amazontmp1, $amazonfolder, 'amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonname1.'', S3::ACL_PRIVATE);

	move_uploaded_file($amazontmp1, '../upload/amazon/single/amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonname1.'');

?>
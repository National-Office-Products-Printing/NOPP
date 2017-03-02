<?php
	
	$amazontmp1 = $_FILES["uploadsingle1"]["tmp_name"];
	$amazontmp2 = $_FILES["uploadsingle2"]["tmp_name"];
	$amazontmp3 = $_FILES["uploadsingle3"]["tmp_name"];
	
	$amazonbasename1 = $_FILES["uploadsingle1"]["name"];
	$amazonbasename2 = $_FILES["uploadsingle2"]["name"];
	$amazonbasename3 = $_FILES["uploadsingle3"]["name"];

	$amazonname1  = strtolower($amazonbasename1);
	$amazonname2  = strtolower($amazonbasename2);
	$amazonname3  = strtolower($amazonbasename3);

	$client = new S3($accesskey, $accesssecret);

	$client->putBucket($amazonfolder, S3::ACL_PRIVATE);
				
	$response = $client->putObjectFile($amazontmp1, $amazonfolder, 'amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonname1.'', S3::ACL_PRIVATE);
	$response = $client->putObjectFile($amazontmp2, $amazonfolder, 'amazon-'.$finalnumber1.'-'.$finaltime2.'-'.$amazonname2.'', S3::ACL_PRIVATE);
	$response = $client->putObjectFile($amazontmp3, $amazonfolder, 'amazon-'.$finalnumber1.'-'.$finaltime3.'-'.$amazonname3.'', S3::ACL_PRIVATE);

	move_uploaded_file($amazontmp1, '../upload/amazon/multiple/amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonname1.'');
	move_uploaded_file($amazontmp2, '../upload/amazon/multiple/amazon-'.$finalnumber1.'-'.$finaltime2.'-'.$amazonname2.'');
	move_uploaded_file($amazontmp3, '../upload/amazon/multiple/amazon-'.$finalnumber1.'-'.$finaltime3.'-'.$amazonname3.'');

?>
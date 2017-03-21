<?php
	
	$normaltmp1 = $_FILES["uploadsingle1"]["tmp_name"];

	$filebasename1 = $_FILES["uploadsingle1"]["name"];

	$finalname1  = strtolower($filebasename1);

	move_uploaded_file($normaltmp1, '../upload/normal/single/upload-'.$finalnumber1.'-'.$finaltime1.'-'.$finalname1.'');
	
?>
<?php
	
	$normaltmp1 = $_FILES["uploadsingle1"]["tmp_name"];
	$normaltmp2 = $_FILES["uploadsingle2"]["tmp_name"];
	$normaltmp3 = $_FILES["uploadsingle3"]["tmp_name"];

	$filebasename1 = $_FILES["uploadsingle1"]["name"];
	$filebasename2 = $_FILES["uploadsingle2"]["name"];
	$filebasename3 = $_FILES["uploadsingle3"]["name"];

	$finalname1  = strtolower($filebasename1);
	$finalname2  = strtolower($filebasename2);
	$finalname3  = strtolower($filebasename3);

	move_uploaded_file($normaltmp1, '../upload/normal/multiple/upload-'.$finalnumber1.'-'.$finaltime1.'-'.$finalname1.'');
	move_uploaded_file($normaltmp2, '../upload/normal/multiple/upload-'.$finalnumber1.'-'.$finaltime2.'-'.$finalname2.'');
	move_uploaded_file($normaltmp3, '../upload/normal/multiple/upload-'.$finalnumber1.'-'.$finaltime3.'-'.$finalname3.'');
		
?>
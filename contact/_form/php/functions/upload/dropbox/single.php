<?php		
	
	$dropbox = new dropboxupload($dropboxtoken);

	$dropboxtmp1 = $_FILES["uploadsingle1"]["tmp_name"];
	
	$dropboxbasename1 = $_FILES["uploadsingle1"]["name"];

	$dropboxname1  = strtolower($dropboxbasename1);
		
	$uploader = $dropbox->upload($dropboxtmp1,'dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxname1.'',$dropboxfolder);
	
	move_uploaded_file($dropboxtmp1, '../upload/dropbox/single/dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxname1.'');
			
?>
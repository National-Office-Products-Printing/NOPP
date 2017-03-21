<?php
	
	$dropbox = new dropboxupload($dropboxtoken);

	$dropboxtmp1 = $_FILES["uploadsingle1"]["tmp_name"];
	$dropboxtmp2 = $_FILES["uploadsingle2"]["tmp_name"];
	$dropboxtmp3 = $_FILES["uploadsingle3"]["tmp_name"];
	
	$dropboxbasename1 = $_FILES["uploadsingle1"]["name"];
	$dropboxbasename2 = $_FILES["uploadsingle2"]["name"];
	$dropboxbasename3 = $_FILES["uploadsingle3"]["name"];

	$dropboxname1  = strtolower($dropboxbasename1);
	$dropboxname2  = strtolower($dropboxbasename2);
	$dropboxname3  = strtolower($dropboxbasename3);
			
	$uploader = $dropbox->upload($dropboxtmp1,'dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxname1.'',$dropboxfolder);
	$uploader = $dropbox->upload($dropboxtmp2,'dropbox-'.$finalnumber1.'-'.$finaltime2.'-'.$dropboxname2.'',$dropboxfolder);
	$uploader = $dropbox->upload($dropboxtmp3,'dropbox-'.$finalnumber1.'-'.$finaltime3.'-'.$dropboxname3.'',$dropboxfolder);
	
	move_uploaded_file($dropboxtmp1, '../upload/dropbox/multiple/dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxname1.'');
	move_uploaded_file($dropboxtmp2, '../upload/dropbox/multiple/dropbox-'.$finalnumber1.'-'.$finaltime2.'-'.$dropboxname2.'');
	move_uploaded_file($dropboxtmp3, '../upload/dropbox/multiple/dropbox-'.$finalnumber1.'-'.$finaltime3.'-'.$dropboxname3.'');

?>
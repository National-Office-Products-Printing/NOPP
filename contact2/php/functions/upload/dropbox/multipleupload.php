<?php
	
	foreach($_FILES["uploadmultiple1"]["name"] as $key => $dropboxname1){
		
		$dropbox = new dropboxupload($dropboxtoken);
		
		$dropboxtmpname1 = $_FILES["uploadmultiple1"]["tmp_name"][$key];
		$dropboxname1    = $_FILES["uploadmultiple1"]["name"][$key];
		$dropboxsize1    = $_FILES["uploadmultiple1"]["size"][$key];
		$dropboxerror1   = $_FILES["uploadmultiple1"]["error"][$key];
		
		$dropboxmultipleuploadtype1 = finfo_open(FILEINFO_MIME_TYPE);
		$dropboxmultipletype1 = finfo_file($dropboxmultipleuploadtype1, $dropboxtmpname1);
		
		$dropboxuploadname1 = strtolower($dropboxname1);
		
		if($upload == true && $dropboxmultipleuploadfiles == true && $uploadmultiplefield == true && $dropboxerror1 === UPLOAD_ERR_NO_FILE){
			echo $lang['form-empty-multiple-upload1'];
		} elseif($upload == true && $dropboxmultipleuploadfiles == true && $uploadmultiplefield == true && $dropboxerror1 === UPLOAD_ERR_INI_SIZE){
			echo $lang['form-max-phpini-upload1'];
		} elseif($upload == true && $dropboxmultipleuploadfiles == true && $uploadmultiplefield == true && $dropboxerror1 === UPLOAD_ERR_NO_TMP_DIR){
			echo $lang['form-empty-tmp-folder-upload1'];
		} elseif($upload == true && $dropboxmultipleuploadfiles == true && $uploadmultiplefield == true && $dropboxerror1 === UPLOAD_ERR_CANT_WRITE){
			echo $lang['form-wrong-write-upload1'];
		} elseif($upload == true && $dropboxmultipleuploadfiles == true && $uploadmultiplefield == true && in_array($dropboxmultipletype1, $uploadservertypes) === false){
			echo $lang['form-wrong-mimetype-multiple-upload1'];
		} elseif($upload == true && $dropboxmultipleuploadfiles == true && $uploadmultiplefield == true && $dropboxsize1 > $uploadserversize){
			echo $lang['form-wrong-filesize-mutiple-upload1'];
		} else {
		
		    $uploader = $dropbox->upload($dropboxtmpname1,'dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxuploadname1.'',$dropboxfolder);
			$uploadmove = move_uploaded_file($dropboxtmpname1,'../upload/dropbox/multipleupload/dropbox-'.$finalnumber1.'-'.$finaltime1.'-'.$dropboxuploadname1.'');
			
		}
		
	}

?>
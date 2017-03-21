<?php
	
	foreach($_FILES["uploadmultiple1"]["name"] as $key => $finalname1){
		
		$finaltmpname1 = $_FILES["uploadmultiple1"]["tmp_name"][$key];
		$finalname1    = $_FILES["uploadmultiple1"]["name"][$key];
		$finalsize1    = $_FILES["uploadmultiple1"]["size"][$key];
		$finalerror1   = $_FILES["uploadmultiple1"]["error"][$key];
		
		$finalmultipleuploadtype1 = finfo_open(FILEINFO_MIME_TYPE);
		$finalmultipletype1 = finfo_file($finalmultipleuploadtype1, $finaltmpname1);
		
		$finaluploadname1 = strtolower($finalname1);
		
		if($upload == true && $multipleuploadfiles == true && $uploadmultiplefield == true && $finalerror1 === UPLOAD_ERR_NO_FILE){
			echo $lang['form-empty-multiple-upload1'];
		} elseif($upload == true && $multipleuploadfiles == true && $uploadmultiplefield == true && $finalerror1 === UPLOAD_ERR_INI_SIZE){
			echo $lang['form-max-phpini-upload1'];
		} elseif($upload == true && $multipleuploadfiles == true && $uploadmultiplefield == true && $finalerror1 === UPLOAD_ERR_NO_TMP_DIR){
			echo $lang['form-empty-tmp-folder-upload1'];
		} elseif($upload == true && $multipleuploadfiles == true && $uploadmultiplefield == true && $finalerror1 === UPLOAD_ERR_CANT_WRITE){
			echo $lang['form-wrong-write-upload1'];
		} elseif($upload == true && $multipleuploadfiles == true && $uploadmultiplefield == true && in_array($finalmultipletype1, $uploadservertypes) === false){
			echo $lang['form-wrong-mimetype-multiple-upload1'];
		} elseif($upload == true && $multipleuploadfiles == true && $uploadmultiplefield == true && $finalsize1 > $uploadserversize){
			echo $lang['form-wrong-filesize-mutiple-upload1'];
		} else {										
			$uploadmove = move_uploaded_file($finaltmpname1,'../upload/normal/multipleupload/upload-'.$finalnumber1.'-'.$finaltime1.'-'.$finaluploadname1.'');
		}
		
	}

?>
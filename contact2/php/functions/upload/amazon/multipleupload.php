<?php
	
	foreach($_FILES["uploadmultiple1"]["name"] as $key => $amazonname1){
		
		$amazontmpname1 = $_FILES["uploadmultiple1"]["tmp_name"][$key];
		$amazonname1    = $_FILES["uploadmultiple1"]["name"][$key];
		$amazonsize1    = $_FILES["uploadmultiple1"]["size"][$key];
		$amazonerror1   = $_FILES["uploadmultiple1"]["error"][$key];
		
		$amazonmultipleuploadtype1 = finfo_open(FILEINFO_MIME_TYPE);
		$amazonmultipletype1 = finfo_file($amazonmultipleuploadtype1, $amazontmpname1);
		
		$amazonuploadname1 = strtolower($amazonname1);
		
		if($upload == true && $amazonmultipleuploadfiles == true && $uploadmultiplefield == true && $amazonerror1 === UPLOAD_ERR_NO_FILE){
			echo $lang['form-empty-multiple-upload1'];
		} elseif($upload == true && $amazonmultipleuploadfiles == true && $uploadmultiplefield == true && $amazonerror1 === UPLOAD_ERR_INI_SIZE){
			echo $lang['form-max-phpini-upload1'];
		} elseif($upload == true && $amazonmultipleuploadfiles == true && $uploadmultiplefield == true && $amazonerror1 === UPLOAD_ERR_NO_TMP_DIR){
			echo $lang['form-empty-tmp-folder-upload1'];
		} elseif($upload == true && $amazonmultipleuploadfiles == true && $uploadmultiplefield == true && $amazonerror1 === UPLOAD_ERR_CANT_WRITE){
			echo $lang['form-wrong-write-upload1'];
		} elseif($upload == true && $amazonmultipleuploadfiles == true && $uploadmultiplefield == true && in_array($amazonmultipletype1, $uploadservertypes) === false){
			echo $lang['form-wrong-mimetype-multiple-upload1'];
		} elseif($upload == true && $amazonmultipleuploadfiles == true && $uploadmultiplefield == true && $amazonsize1 > $uploadserversize){
			echo $lang['form-wrong-filesize-mutiple-upload1'];
		} else {

            $client = new S3($accesskey, $accesssecret);
			$client->putBucket($amazonfolder, S3::ACL_PRIVATE);
						
			$response = $client->putObjectFile($amazontmpname1, $amazonfolder, 'amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonuploadname1.'', S3::ACL_PRIVATE);
			$uploadmove = move_uploaded_file($amazontmpname1,'../upload/amazon/multipleupload/amazon-'.$finalnumber1.'-'.$finaltime1.'-'.$amazonuploadname1.'');
			
		}
		
	}

?>
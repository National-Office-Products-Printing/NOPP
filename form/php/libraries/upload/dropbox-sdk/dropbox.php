<?php

    use \Dropbox as dbx;
	
	class dropboxupload {

		var $dbxClient;
		
		public function __construct($accesstoken){

			if(isset($accesstoken)) {

				$this->dbxClient = new dbx\Client($accesstoken, "PHP-Example/1.0");

			} else {

				throw new Exception('Missing Access Token');

			}

		}
	
		public function upload($localtmp,$uploadname,$dropboxpath){

			if(file_exists($localtmp)){
				
				try {

					$search = fopen($localtmp, "rb");

					$upload = $this->dbxClient->uploadFile($dropboxpath.'/'.$uploadname.'', dbx\WriteMode::add(), $search);

					fclose($search);
					
				} catch(Expection $e){

					return null;
					
				}

			} else {

				return null;

			}

		}
		
	}
?>
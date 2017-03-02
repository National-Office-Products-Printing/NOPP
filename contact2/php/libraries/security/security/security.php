<?php 

	class Security {
		
		private $accessKey;
		private $tokenValue;
		private $currentTime;
		//private $expireTime = 30;
		
		/* CONSTRUCT */
		public function __construct($accessKey){
			$this->setAccessKey($accessKey);
			$this->setCurrentTime(time());
		}
		
		/* SAVE TOKEN IN SESSION */
		private function storeInSession() {
			$_SESSION['token'][$this->getAccessKey()] = serialize($this);
		}
		
		/* REMOVE TOKEN FROM SESSION */
		private function unsetSession() {
			unset($_SESSION['token'][$this->getAccessKey()]);
		}
		
		/* GET TOKEN FROM SESSION */
		private function getFromSession() {
			if (isset($_SESSION['token'][$this->getAccessKey()])) {
				
				return unserialize($_SESSION['token'][$this->getAccessKey()]);
				
			}
			return false; 
		}
		
		
		/* VERIFY IF TOKEN IS EXPIRE */
		private function isTokenExpire(Security $secure){
			if ($_SERVER['REQUEST_TIME'] - $secure->getCurrentTime() < 7200){
				
				return false;
				
			}
			return true;
		}

		/* GENERATE A NEW ACCESS KEY */
		private function generateNewAccessKey(){
			$browser = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : 'NOBROWSER';
            $this->setAccessKey(base64_encode(md5($browser.getenv('REMOTE_ADDR').$this->getAccessKey())));
		}
		
		/* GENERATE A TOKEN KEY WITH ENCRYPT CODE */
		private function generateToken() {
			if (function_exists("hash_algos") && in_array("sha512",hash_algos())) {
				
				$token = hash("sha512",mt_rand(0,mt_getrandmax()));
				
			} else {
				
				$token = '';
				for ($variablei = 0; $variablei < 128; ++$variablei) {
					$variabler = mt_rand(0,35);
					if ($variabler < 26) {
						$variablec = chr(ord('a') + $variabler);
					} else {
						$variablec = chr(ord('0') + $variabler - 26);
					} 
					$token.= $variablec;
				}
				
			}
			$this->setTokenValue($token);
		}
		
		/* VALIDATE A TOKEN KEY */
		public function validateToken() {
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['securetoken']) && $_POST['securetoken']){
				
				$this->generateNewAccessKey();
				$this->setTokenValue($_POST['securetoken']);
				
				$secure = $this->getFromSession();
				if ($secure && is_object($secure) && !$this->isTokenExpire($secure) && $this->getTokenValue() == $secure->getTokenValue()){
					$this->unsetSession();
					return true;
				}
			
			}
			return false;
		}
		
		/* GENERATE A HIDDEN INPUT */
		public function generateHiddenInput(){
			if (!$this->getTokenValue()){
				
				$this->generateSecurityToken();
				
			}
			return "<input type='hidden' name='securetoken' value='{$this->getTokenValue()}'>";
		}
		
		/* GENERATE A TOKEN KEY AND SAVE IT ON SESSION */
		public function generateSecurityToken(){
			$this->generateNewAccessKey();
			$this->generateToken();
			$this->storeInSession();
		}
		
		public function getAccessKey() {
			return $this->accessKey;
		}
		
		public function setAccessKey($accessKey) {
			$this->accessKey = $accessKey;
		}
		
		public function getTokenValue() {
			return $this->tokenValue;
		}
		
		public function setTokenValue($tokenValue) {
			$this->tokenValue = $tokenValue;
		}
		
		public function getCurrentTime() {
			return $this->currentTime;
		}
		
		public function setCurrentTime($currentTime) {
			$this->currentTime = $currentTime;
		}
		
	}
	
?>
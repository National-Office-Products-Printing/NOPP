<?php

    // TURN E_ALL INSTEAD OF 0 FOR DEBUG!
    error_reporting(0);

	include dirname(__FILE__).'/functions/sessions/sessions.php';
 
    // SESSION START!
    secure_session($sessionname);
		
	// INCLUDE ALL NECESSARY FILES!
	include dirname(__FILE__).'/libraries/security/security/security.php';
	include dirname(__FILE__).'/libraries/upload/dropbox-sdk/dropbox/lib/Dropbox/autoload.php';
	include dirname(__FILE__).'/libraries/upload/dropbox-sdk/dropbox.php';
	include dirname(__FILE__).'/libraries/upload/amazon-sdk/amazon/amazon.php';
	include dirname(__FILE__).'/libraries/sms/textmagic-sms-class/TextMagicAPI.php';
	include dirname(__FILE__).'/libraries/newsletter/mailchimp/MailChimp.php';
	include dirname(__FILE__).'/libraries/newsletter/campaign-monitor/csrest_subscribers.php';
	include dirname(__FILE__).'/libraries/reports/excel/PHPExcel.php';
	include dirname(__FILE__).'/libraries/payment/braintree-sdk/lib/autoload.php';
	include dirname(__FILE__).'/libraries/payment/stripe-sdk/loader.php';
	include dirname(__FILE__).'/libraries/signature/docsign-sdk/autoload.php';
	include dirname(__FILE__).'/settings/settings.php';
	include dirname(__FILE__).'/functions/language/language.php';
	include dirname(__FILE__).'/functions/database/database.php';
	include dirname(__FILE__).'/functions/redirect/redirect.php';
	include dirname(__FILE__).'/functions/payment/creditcard.php';
			
	// POST VARIABLES ADD HERE MORE AFTER YOUR ADD MORE INPUTS!
	$firstname = strip_tags(trim($_POST["firstname"]));
	$lastname = strip_tags(trim($_POST["lastname"]));
	$email = strip_tags(trim($_POST["email"]));
	$subject = strip_tags(trim($_POST["subject"]));
	$message = strip_tags(trim($_POST["message"]));
	$creditcardname = strip_tags(trim($_POST["card-name"]));
	$creditcardnumber = strip_tags(trim($_POST["card-number"]));
	$creditcarddate = strip_tags(trim($_POST["card-date"]));
	$creditcardcvv = strip_tags(trim($_POST["card-cvv"]));
	$stripecardname = strip_tags(trim($_POST["stripe-name"]));
	$stripecardnumber = strip_tags(trim($_POST["stripe-number"]));
	$stripecardmonth = strip_tags(trim($_POST["stripe-month"]));
	$stripecardyear = strip_tags(trim($_POST["stripe-year"]));
	$stripecardcvc = strip_tags(trim($_POST["stripe-cvc"]));
	$nonce = strip_tags(trim($_POST["payment_method_nonce"]));
	
	// ESCAPE ALL POST VARIABLES ALSO IF YOU ADD MORE POST INPUTS ADD HERE YOUR ESCAPE!
	$finalfirstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
	$finallastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
	$finalemail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
	$finalsubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
	$finalmessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
	$finalcreditcardname = htmlspecialchars($creditcardname, ENT_QUOTES, 'UTF-8');
	$finalcreditcardnumber = htmlspecialchars($creditcardnumber, ENT_QUOTES, 'UTF-8');
	$finalcreditcarddate = htmlspecialchars($creditcarddate, ENT_QUOTES, 'UTF-8');
	$finalcreditcardcvv = htmlspecialchars($creditcardcvv, ENT_QUOTES, 'UTF-8');
	$finalstripecardname = htmlspecialchars($stripecardname, ENT_QUOTES, 'UTF-8');
	$finalstripecardnumber = htmlspecialchars($stripecardnumber, ENT_QUOTES, 'UTF-8');
	$finalstripecardmonth = htmlspecialchars($stripecardmonth, ENT_QUOTES, 'UTF-8');
	$finalstripecardyear = htmlspecialchars($stripecardyear, ENT_QUOTES, 'UTF-8');
	$finalstripecardcvc = htmlspecialchars($stripecardcvc, ENT_QUOTES, 'UTF-8');
	$finalnonce = htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8');
	
	// POST VARIABLES ADD FOR CHECKBOES / RADIO & SELECT!
	$finalmethod = $_POST["method"];
	$finalsupport = $_POST["support"];
	$finalservice = $_POST["service"];
	$finalpayment = $_POST["payment"];
	$finalrecurring = $_POST["recurring"];
	$finalnewsletter = $_POST["newsletter"];
	$finalsendtome = $_POST["sendtome"];
	
	// ADD HERE YOUR CALL FOR MULTILANGUAGE!
	if($multilanguage == true) {
		if (isset($arraylanguage[1])){
			include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
		} else {
			include dirname(__FILE__).'/languages/en.php';
		}
	} else {
		include dirname(__FILE__).'/languages/'.$language.'.php';
	}
	
	// CALL FOR TEXTMAGIC CREDENTIALS!
	if($sendmobile == true){
		$mobile = new TextMagicAPI(array(
			'username' => $textmagicusername,
			'password' => $textmagicpassword
		));	
	}
	
	// CALL FOR BRAINTREE KEYS!
	if($merchantpayment == true && $creditcard == true || $merchantpayment == true && $paypal == true){
		$braintreeenvironment = Braintree_Configuration::environment($merchantenrironment);
		$braintreemerchantid = Braintree_Configuration::merchantId($merchantid);
		$braintreepublickey = Braintree_Configuration::publicKey($merchantpublickey);
		$braintreeprivatekey = Braintree_Configuration::privateKey($merchantprivatekey);
	}
	
	// CALL FOR STRIPE KEY!
	if($merchantpayment == true && $stripe == true){	
		$stripekeys = array(
			'secret_key' => $stripesecretkey,
		);
		$stripekey = Stripe\Stripe::setApiKey($stripekeys['secret_key']);
	}
	
	// CALL SECURITY CSRF CLASS INSTANCE!	
	if($security == true){
		$secure = new Security($secret);
	}
	
	// BEGINNING OF VALIDATION OF CREDIT CARD & STRIPE NUMBER!
	$finalcreditcardnumbervalidate = CreditCard::validateCard($finalcreditcardnumber);
	$finalstripecardnumbervalidate = CreditCard::validateCard($finalstripecardnumber);
	
	// BEGINNING OF VALIDATION OF UPLOAD TYPE!
	$finalsingleuploadtype1 = finfo_open(FILEINFO_MIME_TYPE);
	$finalsingletype1 = finfo_file($finalsingleuploadtype1, $_FILES["uploadsingle1"]["tmp_name"]);
	$finalsingleuploadtype2 = finfo_open(FILEINFO_MIME_TYPE);
	$finalsingletype2 = finfo_file($finalsingleuploadtype2, $_FILES["uploadsingle2"]["tmp_name"]);
	$finalsingleuploadtype3 = finfo_open(FILEINFO_MIME_TYPE);
	$finalsingletype3 = finfo_file($finalsingleuploadtype3, $_FILES["uploadsingle3"]["tmp_name"]);
	
	// SERVER SIDE VALIDATION TO ALL FEATURES!
	if($mail == true && $youremail === ''){		
		echo $lang['form-empty-youremail'];
	} elseif($mail == true && $smtpauth == true && $smtpnoauth == true){	
		echo $lang['form-disable-email-service'];
	} elseif($mail == false && $smtpauth == false && $smtpnoauth == false){
		echo $lang['form-enable-email-service'];
	} elseif($smtpauth == true && $youremail !== $username){			
		echo $lang['form-wrong-smtp-username'];
	} elseif($messagetop == true && $messagebottom == true){
		echo $lang['form-disable-messages'];
	} elseif($messagetop == false && $messagebottom == false){
		echo $lang['form-enable-messages'];
	} elseif($themedefault == true && $themeflat == true && $thememinimal == true){
		echo $lang['form-disable-theme'];
	} elseif($themedefault == false && $themeflat == false && $thememinimal == false){
		echo $lang['form-enable-theme'];
	} elseif($security == true && $secret === ''){
		echo $lang['form-empty-security'];
	} elseif($security == true && !$secure->validateToken()){
		echo $lang['form-wrong-token'];
	} elseif($upload == true && $singleupload == true && $multipleupload == true && $dropboxsingleupload == true && $dropboxmultipleupload == true && $amazonsingleupload == true && $amazonmultipleupload == true && $multipleuploadfiles == true && $dropboxmultipleuploadfiles == true && $amazonmultipleuploadfiles == true){
		echo $lang['form-disable-uploadtype'];
	} elseif($upload == true && $singleupload == false && $multipleupload == false && $dropboxsingleupload == false && $dropboxmultipleupload == false && $amazonsingleupload == false && $amazonmultipleupload == false && $multipleuploadfiles == false && $dropboxmultipleuploadfiles == false && $amazonmultipleuploadfiles == false){
		echo $lang['form-enable-uploadtype'];
	} elseif($upload == true && $dropboxmultipleupload == true && $dropboxtoken === '' && $dropboxfolder === ''){
		echo $lang['form-empty-dropbox'];
	} elseif($upload == true && $amazonmultipleupload == true && $accesskey === '' && $accesssecret === '' && $amazonfolder === ''){
		echo $lang['form-empty-amazon'];
	} elseif($merchantsupport == true && $merchantservice == true && $merchantpayment == true){
		echo $lang['form-disable-merchant'];
	} elseif($merchantsupport == false && $merchantservice == false && $merchantpayment == false){
		echo $lang['form-enable-merchant'];
	} elseif($merchantpayment == true && $paymentcurrency === ''){
		echo $lang['form-empty-payment-currency'];
	} elseif($merchantpayment == true && $paymentadministrators == true && $recurringadministrators == true){
		echo $lang['form-disable-email'];
	} elseif($merchantpayment == true && $onetimepayment == true && $recurringpayment == true){
		echo $lang['form-disable-service'];
	} elseif($merchantpayment == true && $onetimepayment == false && $recurringpayment == false){
		echo $lang['form-enable-service'];
	} elseif($signature == true && $docsignusername == '' && $docsignpassword == '' && $docsignintegratorkey == ''){
		echo $lang['form-empty-docsign-credentials'];
	} elseif($newsletter == true && $mailchimp == true && $campaignmonitor == true){
		echo $lang['form-disable-newsletter'];
	} elseif($newsletter == true && $mailchimp == false && $campaignmonitor == false){
		echo $lang['form-enable-newsletter'];
	} elseif($newsletter == true && $mailchimp == true && $mailchimpkey === '' && $mailchimplistid === ''){
		echo $lang['form-empty-mc-newsletter'];
	} elseif($newsletter == true && $campaignmonitor == true && $campaignmonitorkey === '' && $campaignmonitorlistid === ''){
		echo $lang['form-empty-cm-newsletter'];
	} elseif($captcha == true && $alphacaptcha == true && $mathcaptcha == true && $recaptcha == true){
		echo $lang['form-disable-captcha'];
	} elseif($captcha == true && $alphacaptcha == false && $mathcaptcha == false && $recaptcha == false){
		echo $lang['form-enable-captcha'];
	} elseif($captcha == true && $recaptcha == true && $sitekey === '' && $secretkey === '' && $recaptchatheme === ''){
		echo $lang['form-empty-recaptcha'];
	} elseif($sendmobile == true && $textmagicusername === '' && $textmagicpassword === '' && $phone === '' && $notification === ''){
		echo $lang['form-empty-mobile'];
	} elseif($mysql == true && $connection === false){				
		echo $lang['form-wrong-mysql'];	
	} elseif($mysql == true && $merchantsupport == true && $preventdoubleemail == true && db_select_support($tablenamesupport,$selectsupportemail,$finalemail)){ 				
		echo $lang['form-duplicate-email'];				
	} elseif($mysql == true && $merchantservice == true && $preventdoubleemail == true && db_select_service($tablenameservice,$selectserviceemail,$finalemail)){ 				
		echo $lang['form-duplicate-email'];				
	} elseif($mysql == true && $merchantpayment == true && $onetimepayment == true && $preventdoubleemail == true && db_select_payment($tablenamepayment,$selectpaymentemail,$finalemail)){ 				
		echo $lang['form-duplicate-email'];					
	} elseif($mysql == true && $merchantpayment == true && $recurringpayment == true && $preventdoubleemail == true && db_select_recurring($tablenamerecurring,$selectrecurringemail,$finalemail)){ 				
		echo $lang['form-duplicate-email'];					
	} elseif($firstnamefield == true && empty($finalfirstname)) {
		echo $lang['form-empty-firstname'];
	} elseif($firstnameerrorfield == true && !preg_match("/^[a-zA-Z\s]+$/i",$finalfirstname)){
		echo $lang['form-wrong-firstname'];
	} elseif($lastnamefield == true && empty($finallastname)) {
		echo $lang['form-empty-lastname'];
	} elseif($lastnameerrorfield == true && !preg_match("/^[a-zA-Z\s]+$/i",$finallastname)){
		echo $lang['form-wrong-lastname'];
	} elseif($emailfield == true && empty($finalemail)) {
		echo $lang['form-empty-email'];
	} elseif($emailerrorfield == true && !preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$finalemail)){
		echo $lang['form-wrong-email'];
	} elseif($subjectfield == true && empty($finalsubject)) {
		echo $lang['form-empty-subject'];
	} elseif($messagefield == true && empty($finalmessage)) {
		echo $lang['form-empty-message'];
	} elseif($upload == true && $multipleupload == true && $upload1field == true && empty($_FILES['uploadsingle1']['name'])){
		echo $lang['form-empty-upload1'];
	} elseif($upload == true && $multipleupload == true && $upload1field == true && in_array($finalsingletype1, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload1'];
	} elseif($upload == true && $multipleupload == true && $upload1field == true && $_FILES['uploadsingle1']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload1'];
	} elseif($upload == true && $multipleupload == true && $upload2field == true && empty($_FILES['uploadsingle2']['name'])){
		echo $lang['form-empty-upload2'];
	} elseif($upload == true && $multipleupload == true && $upload2field == true && in_array($finalsingletype2, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload2'];
	} elseif($upload == true && $multipleupload == true && $upload2field == true && $_FILES['uploadsingle2']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload2'];
	} elseif($upload == true && $multipleupload == true && $upload3field == true && empty($_FILES['uploadsingle3']['name'])){
		echo $lang['form-empty-upload3'];
	} elseif($upload == true && $multipleupload == true && $upload3field == true && in_array($finalsingletype3, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload3'];
	} elseif($upload == true && $multipleupload == true && $upload3field == true && $_FILES['uploadsingle3']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload3'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload1field == true && empty($_FILES['uploadsingle1']['name'])){
		echo $lang['form-empty-upload1'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload1field == true && in_array($finalsingletype1, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload1'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload1field == true && $_FILES['uploadsingle1']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload1'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload2field == true && empty($_FILES['uploadsingle2']['name'])){
		echo $lang['form-empty-upload2'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload2field == true && in_array($finalsingletype2, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload2'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload2field == true && $_FILES['uploadsingle2']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload2'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload3field == true && empty($_FILES['uploadsingle3']['name'])){
		echo $lang['form-empty-upload3'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload3field == true && in_array($finalsingletype3, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload3'];
	} elseif($upload == true && $dropboxmultipleupload == true && $upload3field == true && $_FILES['uploadsingle3']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload3'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload1field == true && empty($_FILES['uploadsingle1']['name'])){
		echo $lang['form-empty-upload1'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload1field == true && in_array($finalsingletype1, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload1'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload1field == true && $_FILES['uploadsingle1']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload1'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload2field == true && empty($_FILES['uploadsingle2']['name'])){
		echo $lang['form-empty-upload2'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload2field == true && in_array($finalsingletype2, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload2'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload2field == true && $_FILES['uploadsingle2']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload2'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload3field == true && empty($_FILES['uploadsingle3']['name'])){
		echo $lang['form-empty-upload3'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload3field == true && in_array($finalsingletype3, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload3'];
	} elseif($upload == true && $amazonmultipleupload == true && $upload3field == true && $_FILES['uploadsingle3']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload3'];
	} elseif($upload == true && $singleupload == true && $upload1field == true && empty($_FILES['uploadsingle1']['name'])){
		echo $lang['form-empty-upload1'];
	} elseif($upload == true && $singleupload == true && $upload1field == true && in_array($finalsingletype1, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload1'];
	} elseif($upload == true && $singleupload == true && $upload1field == true && $_FILES['uploadsingle1']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload1'];
	} elseif($upload == true && $dropboxsingleupload == true && $upload1field == true && empty($_FILES['uploadsingle1']['name'])){
		echo $lang['form-empty-upload1'];
	} elseif($upload == true && $dropboxsingleupload == true && $upload1field == true && in_array($finalsingletype1, $uploadservertypes) === false) {
		echo $lang['form-wrong-extension-upload1'];
	} elseif($upload == true && $dropboxsingleupload == true && $upload1field == true && $_FILES['uploadsingle1']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload1'];
	} elseif($upload == true && $amazonsingleupload == true && $upload1field == true && empty($_FILES['uploadsingle1']['name'])){
		echo $lang['form-empty-upload1'];
	} elseif($upload == true && $amazonsingleupload == true && $upload1field == true && in_array($finalsingletype1, $uploadservertypes) === false){
		echo $lang['form-wrong-extension-upload1'];
	} elseif($upload == true && $amazonsingleupload == true && $upload1field == true && $_FILES['uploadsingle1']['size'] > $uploadserversize) {
		echo $lang['form-wrong-filesize-upload1'];
	} elseif($merchantsupport == true && $supportselect == true && $supportfield == true && empty($finalsupport)) {
		echo $lang['form-empty-support'];
	} elseif($merchantservice == true && $servicefield == true && empty($finalservice)) {
		echo $lang['form-empty-service'];
	} elseif($merchantpayment == true && $onetimepayment == true && $paymentfield == true && empty($finalpayment)) {
		echo $lang['form-empty-service'];
	} elseif($merchantpayment == true && $recurringpayment == true && $recurringfield == true && empty($finalrecurring)) {
		echo $lang['form-empty-service'];
	} elseif($merchantpayment == true && $creditcard == true && $cardnamefield == true && $finalmethod === $lang['form-payment-method-1'] && empty($finalcreditcardname)) {
		echo $lang['form-empty-creditcardname'];
	} elseif($merchantpayment == true && $creditcard == true && $cardnameerrorfield == true && $finalmethod === $lang['form-payment-method-1'] && !preg_match("/^[a-zA-Z\s]+$/i",$finalcreditcardname)){
		echo $lang['form-wrong-creditcardname'];
	} elseif($merchantpayment == true && $creditcard == true && $cardnumberfield == true && $finalmethod === $lang['form-payment-method-1'] && empty($finalcreditcardnumber)) {
		echo $lang['form-empty-creditcard'];
	} elseif($merchantpayment == true && $creditcard == true && $cardnumbererrorfield == true && $finalmethod === $lang['form-payment-method-1'] && !$finalcreditcardnumbervalidate) {
		echo $lang['form-wrong-creditcard'];
	} elseif($merchantpayment == true && $stripe == true && $cardnamefield == true && $finalmethod === $lang['form-payment-method-3'] && empty($finalstripecardname)) {
		echo $lang['form-empty-creditcardname'];
	} elseif($merchantpayment == true && $stripe == true && $cardnameerrorfield == true && $finalmethod === $lang['form-payment-method-3'] && !preg_match("/^[a-zA-Z\s]+$/i",$finalstripecardname)){
		echo $lang['form-wrong-creditcardname'];
	} elseif($merchantpayment == true && $stripe == true && $cardnumberfield == true && $finalmethod === $lang['form-payment-method-3'] && empty($finalstripecardnumber)) {
		echo $lang['form-empty-creditcard'];
	} elseif($merchantpayment == true && $stripe == true && $cardnumbererrorfield == true && $finalmethod === $lang['form-payment-method-3'] && !$finalstripecardnumbervalidate) {
		echo $lang['form-wrong-creditcard'];
	} elseif($newsletter == true && $newsletterfield == true && empty($finalnewsletter)) {
		echo $lang['form-empty-newsletter'];
	} elseif($sendcopytome == true && $sendtomefield == true && empty($finalsendtome)) {
		echo $lang['form-empty-send-to-me'];
	} else {
		
		// AFTER SERVER SIDE VALIDATION LETS GO TO CHECK WHAT ARE FEATURES THAT ARE ENABLE IN SETTINGS AND SEND EMAIL ACCORDING!
		if($merchantsupport == true){
									
			// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
			$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
			
			// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
			$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
			
			// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
			$finaltime1 = time() + 10;
			$finaltime2 = time() + 20;
			$finaltime3 = time() + 30;

			if($excelreports == true) {
				
				if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
				if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
										
				include dirname(__FILE__).'/functions/reports/excel/reports.php';

				if($upload == true){
				
					if($multipleupload == true){
													
						include dirname(__FILE__).'/functions/upload/normal/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}

							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
							
					} elseif ($singleupload == true) {
																			
						include dirname(__FILE__).'/functions/upload/normal/single.php';
						
						if($newsletter == true) {
							
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
									
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($dropboxmultipleupload == true){
												
						include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($dropboxsingleupload == true) {
																			
						include dirname(__FILE__).'/functions/upload/dropbox/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($amazonmultipleupload == true) {
																										
						include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($amazonsingleupload == true) {
																																	
						include dirname(__FILE__).'/functions/upload/amazon/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
	
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($multipleuploadfiles == true) {
																																	
						include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
						
						if($uploadmove === true){
						
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
						}
						
					} elseif($dropboxmultipleuploadfiles == true) {
																																	
						include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
						
						if($uploadmove === true){
						
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
						}
						
					} elseif($amazonmultipleuploadfiles == true) {
																																	
						include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
						
						if($uploadmove === true){
							
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
						}
						
					} else {
						
						echo $lang['form-upload-method-message'];
						
					}

				} else {
					
					if($newsletter == true) {
					
						if($mailchimp == true){
													
							if($finalnewsletter === $lang['form-newsletter-option-1']){
						
								include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}

						} elseif($campaignmonitor == true) {
																						
							if($finalnewsletter === $lang['form-newsletter-option-1']){
							
								include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
							
						} else {
							
							echo $lang['form-newsletter-method-message'];
							
						}
															
					} else {
															
						include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
						if($multilanguage == true) {
							if (isset($arraylanguage[1])){
								include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
							} else {
								include dirname(__FILE__).'/languages/en.php';
							}
						} else {
							include dirname(__FILE__).'/languages/'.$language.'.php';
						}
						include dirname(__FILE__).'/settings/settings.php';
						include dirname(__FILE__).'/messages/administrator/message-support.php';
						include dirname(__FILE__).'/functions/notification/administrator/message.php';
						include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
					
					}
				}
				
			} else {
								
				if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
				if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
										
				if($upload == true){
				
					if($multipleupload == true){
													
						include dirname(__FILE__).'/functions/upload/normal/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}

							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
							
					} elseif ($singleupload == true) {
																			
						include dirname(__FILE__).'/functions/upload/normal/single.php';
						
						if($newsletter == true) {
							
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
									
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($dropboxmultipleupload == true){
												
						include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($dropboxsingleupload == true) {
																			
						include dirname(__FILE__).'/functions/upload/dropbox/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($amazonmultipleupload == true) {
																										
						include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($amazonsingleupload == true) {
																																	
						include dirname(__FILE__).'/functions/upload/amazon/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
	
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-support.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-support.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
						
						}
						
					} elseif($multipleuploadfiles == true){
																																	
						include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
						
						if($uploadmove === true){
						
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
						}
						
					} elseif($dropboxmultipleuploadfiles == true) {
																																	
						include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
						
						if($uploadmove === true){
						
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
						}
						
					} elseif($amazonmultipleuploadfiles == true){
																																	
						include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
						
						if($uploadmove === true){
							
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-support.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
						}
						
					} else {
						
						echo $lang['form-upload-method-message'];
						
					} 

				} else {
					
					if($newsletter == true) {
					
						if($mailchimp == true){
													
							if($finalnewsletter === $lang['form-newsletter-option-1']){
						
								include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';	
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}

						} elseif($campaignmonitor == true) {
																						
							if($finalnewsletter === $lang['form-newsletter-option-1']){
							
								include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-support.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
							
							}
							
						} else {
							
							echo $lang['form-newsletter-method-message'];
							
						}
															
					} else {
															
						include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
						if($multilanguage == true) {
							if (isset($arraylanguage[1])){
								include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
							} else {
								include dirname(__FILE__).'/languages/en.php';
							}
						} else {
							include dirname(__FILE__).'/languages/'.$language.'.php';
						}
						include dirname(__FILE__).'/settings/settings.php';
						include dirname(__FILE__).'/messages/administrator/message-support.php';
						include dirname(__FILE__).'/functions/notification/administrator/message.php';
						include dirname(__FILE__).'/functions/notification/customer/notification-support.php';
					
					}
				}
			}
			
		} elseif($merchantservice == true) {
						
			include dirname(__FILE__).'/settings/settings.php';
					
			$finalservices = implode(', ', $finalservice);
					
			foreach($finalservice as $service) {
				$finalserviceprice += $servicearray[$service]['price'];
			}
				
			// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
			$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
			
			// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
			$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
			
			// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
			$finaltime1 = time() + 10;
			$finaltime2 = time() + 20;
			$finaltime3 = time() + 30;

			if($excelreports == true) {
				
				if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
				if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
										
				include dirname(__FILE__).'/functions/reports/excel/reports.php';

				if($upload == true){
				
					if($multipleupload == true){
													
						include dirname(__FILE__).'/functions/upload/normal/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}

							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
							
					} elseif ($singleupload == true) {
																			
						include dirname(__FILE__).'/functions/upload/normal/single.php';
						
						if($newsletter == true) {
							
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
									
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($dropboxmultipleupload == true){
												
						include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($dropboxsingleupload == true) {
																			
						include dirname(__FILE__).'/functions/upload/dropbox/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($amazonmultipleupload == true) {
																										
						include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($amazonsingleupload == true) {
																																	
						include dirname(__FILE__).'/functions/upload/amazon/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
	
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($multipleuploadfiles == true){
																																	
						include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
						
						if($uploadmove === true){
							
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
						}
						
					} elseif($dropboxmultipleuploadfiles == true) {
																																	
						include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
						
						if($uploadmove === true){
						
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
						}
						
					} elseif($amazonmultipleuploadfiles == true) {
																																	
						include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
						
						if($uploadmove === true){
							
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
						}
						
					} else {
						
						echo $lang['form-upload-method-message'];
						
					}

				} else {
					
					if($newsletter == true){
					
						if($mailchimp == true){
													
							if($finalnewsletter === $lang['form-newsletter-option-1']){
						
								include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}

						} elseif($campaignmonitor == true) {
																						
							if($finalnewsletter === $lang['form-newsletter-option-1']){
							
								include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
							
						} else {
							
							echo $lang['form-newsletter-method-message'];
							
						}
															
					} else {
															
						include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
						if($multilanguage == true) {
							if (isset($arraylanguage[1])){
								include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
							} else {
								include dirname(__FILE__).'/languages/en.php';
							}
						} else {
							include dirname(__FILE__).'/languages/'.$language.'.php';
						}
						include dirname(__FILE__).'/settings/settings.php';
						include dirname(__FILE__).'/messages/administrator/message-service.php';
						include dirname(__FILE__).'/functions/notification/administrator/message.php';
						include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
					
					}
				}
				
			} else {
								
				if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
				if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
										
				if($upload == true){
				
					if($multipleupload == true){
													
						include dirname(__FILE__).'/functions/upload/normal/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}

							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
							
					} elseif ($singleupload == true) {
																			
						include dirname(__FILE__).'/functions/upload/normal/single.php';
						
						if($newsletter == true) {
							
							if($mailchimp == true){
															
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
									
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($dropboxmultipleupload == true){
												
						include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($dropboxsingleupload == true){
																			
						include dirname(__FILE__).'/functions/upload/dropbox/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																								
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($amazonmultipleupload == true){
																										
						include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($amazonsingleupload == true){
																																	
						include dirname(__FILE__).'/functions/upload/amazon/single.php';
						
						if($newsletter == true) {
						
							if($mailchimp == true){
																
								if($finalnewsletter === $lang['form-newsletter-option-1']){
							
									include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
	
							} elseif($campaignmonitor == true) {
																									
								if($finalnewsletter === $lang['form-newsletter-option-1']){
								
									include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								} else {
																				
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-service.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
								
								}
								
							} else {
								
								echo $lang['form-newsletter-method-message'];
								
							}
																
						} else {
																
							include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
							if($multilanguage == true) {
								if (isset($arraylanguage[1])){
									include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
								} else {
									include dirname(__FILE__).'/languages/en.php';
								}
							} else {
								include dirname(__FILE__).'/languages/'.$language.'.php';
							}
							include dirname(__FILE__).'/settings/settings.php';
							include dirname(__FILE__).'/messages/administrator/message-service.php';
							include dirname(__FILE__).'/functions/notification/administrator/message.php';
							include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
						
						}
						
					} elseif($multipleuploadfiles == true){
																																	
						include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
						
						if($uploadmove === true){
							
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
						}
						
					} elseif($dropboxmultipleuploadfiles == true){
																																	
						include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
						
						if($uploadmove === true){
							
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
						}
						
					} elseif($amazonmultipleuploadfiles == true){
																																	
						include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
						
						if($uploadmove === true){
							
							if($newsletter == true) {
							
								if($mailchimp == true){
																	
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
		
								} elseif($campaignmonitor == true) {
																										
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-service.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
						}
						
					} else {
						
						echo $lang['form-upload-method-message'];
						
					}

				} else {
					
					if($newsletter == true) {
					
						if($mailchimp == true){
													
							if($finalnewsletter === $lang['form-newsletter-option-1']){
						
								include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';	
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}

						} elseif($campaignmonitor == true) {
																						
							if($finalnewsletter === $lang['form-newsletter-option-1']){
							
								include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							} else {
																			
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-service.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
							
							}
							
						} else {
							
							echo $lang['form-newsletter-method-message'];
							
						}
															
					} else {
															
						include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
						if($multilanguage == true) {
							if (isset($arraylanguage[1])){
								include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
							} else {
								include dirname(__FILE__).'/languages/en.php';
							}
						} else {
							include dirname(__FILE__).'/languages/'.$language.'.php';
						}
						include dirname(__FILE__).'/settings/settings.php';
						include dirname(__FILE__).'/messages/administrator/message-service.php';
						include dirname(__FILE__).'/functions/notification/administrator/message.php';
						include dirname(__FILE__).'/functions/notification/customer/notification-service.php';
					
					}
				}
			}
			
		} elseif($merchantpayment == true){
			
			if($onetimepayment == true){
				
				if($creditcard == true && $finalmethod === $lang['form-payment-method-1']){
																				
					include dirname(__FILE__).'/settings/settings.php';
									
					$finalpayments = implode(', ', $finalpayment);
																
					foreach($finalpayment as $payment) {
						$finalccpaymentprice += $onetimepaymentarray[$payment]['price'];
						$finalpaymentprice = $finalccpaymentprice;
					}
					
					// GENERATE HERE YOUR CUSTOMER ID!
					$finalcustomerid = strtoupper(substr(md5(rand(1,999999)),0,10));
					
					// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
					$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
					$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
					$finaltime1 = time() + 10;
					$finaltime2 = time() + 20;
					$finaltime3 = time() + 30;

					if($excelreports == true) {
						
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						include dirname(__FILE__).'/functions/reports/excel/reports.php';

						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($amazonmultipleupload == true) {
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($amazonsingleupload == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($multipleuploadfiles == true){
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							} 

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-payment.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
							
							}
						}
						
					} else {
										
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($amazonmultipleupload == true){
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($amazonsingleupload == true){
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
								
								}
								
							} elseif($multipleuploadfiles == true){
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true){
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true){
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-payment.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-payment-creditcard.php';
							
							}
						}
					}
					
				} elseif($paypal == true && $finalmethod === $lang['form-payment-method-2']){
																							
					include dirname(__FILE__).'/settings/settings.php';
					
					$finalpayments = implode(', ', $finalpayment);
																
					foreach($finalpayment as $payment) {
						$finalpppaymentprice += $onetimepaymentarray[$payment]['price'];
						$finalpaymentprice = $finalpppaymentprice;
					}
					
					// GENERATE HERE YOUR CUSTOMER ID!
					$finalcustomerid = strtoupper(substr(md5(rand(1,999999)),0,10));
					
					// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
					$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
					$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
					$finaltime1 = time() + 10;
					$finaltime2 = time() + 20;
					$finaltime3 = time() + 30;

					if($excelreports == true) {
						
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						include dirname(__FILE__).'/functions/reports/excel/reports.php';

						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($amazonmultipleupload == true) {
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($amazonsingleupload == true){
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($multipleuploadfiles == true){
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-payment.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
							
							}
						}
						
					} else {
										
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($dropboxsingleupload == true){
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($amazonmultipleupload == true){
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($amazonsingleupload == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
								
								}
								
							} elseif($multipleuploadfiles == true) {
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true){
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-payment.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-payment-paypal.php';
							
							}
						}
					}
					
				} elseif($stripe == true && $finalmethod === $lang['form-payment-method-3']){
																								
					include dirname(__FILE__).'/settings/settings.php';
									
					$finalpayments = implode(', ', $finalpayment);
																
					foreach($finalpayment as $payment) {
						$finalstpaymentprice += floatval($onetimepaymentarray[$payment]['price'])*100;
						$finalstripeprice = $finalstpaymentprice;
					}
					
					// GENERATE CORRECT VALUE FOR MESSAGE!
					$finalpaymentprice = $finalstripeprice/100;
					
					// GENERATE HERE YOUR CUSTOMER ID!
					$finalcustomerid = strtoupper(substr(md5(rand(1,999999)),0,10));
					
					// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
					$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
					$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
					$finaltime1 = time() + 10;
					$finaltime2 = time() + 20;
					$finaltime3 = time() + 30;

					if($excelreports == true) {
						
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						include dirname(__FILE__).'/functions/reports/excel/reports.php';

						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($dropboxsingleupload == true){
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($amazonmultipleupload == true){
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($amazonsingleupload == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($multipleuploadfiles == true){
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true){
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-payment.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
							
							}
						}
						
					} else {
										
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($dropboxsingleupload == true){
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($amazonmultipleupload == true) {
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($amazonsingleupload == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-payment.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-payment.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
								
								}
								
							} elseif($multipleuploadfiles == true){
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-payment.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-payment.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-payment.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-payment-stripe.php';
							
							}
						}
					}
					
				} else {
					
					echo $lang['form-payment-method-message'];
					
				}
				
			} else {
												
				if($creditcard == true && $finalmethod === $lang['form-payment-method-1']){
																				
					include dirname(__FILE__).'/settings/settings.php';
									
					$finalrecurrings = implode(', ', $finalrecurring);
																
					foreach($finalrecurring as $recurring) {
						$finalccrecurringprice += $recurringpaymentarray[$recurring]['price'];
						$finalrecurringprice = $finalccrecurringprice;
						$finalrecurringplan = $recurringpaymentarray[$recurring]['plan'];
					}
					
					// GENERATE HERE YOUR CUSTOMER ID!
					$finalcustomerid = strtoupper(substr(md5(rand(1,999999)),0,10));
					
					// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
					$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
					$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
					$finaltime1 = time() + 10;
					$finaltime2 = time() + 20;
					$finaltime3 = time() + 30;

					if($excelreports == true) {
						
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						include dirname(__FILE__).'/functions/reports/excel/reports.php';

						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($amazonmultipleupload == true){
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($amazonsingleupload == true){
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($multipleuploadfiles == true){
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							} 

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-recurring.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
							
							}
						}
						
					} else {
										
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
									
							} elseif ($singleupload == true){
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($amazonmultipleupload == true){
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($amazonsingleupload == true){
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
								
								}
								
							} elseif($multipleuploadfiles == true){
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-recurring.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-recurring-creditcard.php';
							
							}
						}
					}
					
				} elseif($paypal == true && $finalmethod === $lang['form-payment-method-2']){
																							
					include dirname(__FILE__).'/settings/settings.php';
									
					$finalrecurrings = implode(', ', $finalrecurring);
																
					foreach($finalrecurring as $recurring) {
						$finalpprecurringprice += $recurringpaymentarray[$recurring]['price'];
						$finalrecurringprice = $finalpprecurringprice;
						$finalrecurringplan = $recurringpaymentarray[$recurring]['plan'];
					}
					
					// GENERATE HERE YOUR CUSTOMER ID!
					$finalcustomerid = strtoupper(substr(md5(rand(1,999999)),0,10));
					
					// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
					$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
					$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
					$finaltime1 = time() + 10;
					$finaltime2 = time() + 20;
					$finaltime3 = time() + 30;

					if($excelreports == true) {
						
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						include dirname(__FILE__).'/functions/reports/excel/reports.php';

						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($dropboxsingleupload == true){
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($amazonmultipleupload == true){
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($amazonsingleupload == true){
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($multipleuploadfiles == true) {
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-recurring.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
							
							}
						}
						
					} else {
										
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($amazonmultipleupload == true) {
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($amazonsingleupload == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
								
								}
								
							} elseif($multipleuploadfiles == true) {
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-recurring.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-recurring-paypal.php';
							
							}
						}
					}
					
				} elseif($stripe == true && $finalmethod === $lang['form-payment-method-3']){
																								
					include dirname(__FILE__).'/settings/settings.php';
									
					$finalrecurrings = implode(', ', $finalrecurring);
																
					foreach($finalrecurring as $recurring) {
						$finalstrecurringprice += floatval($recurringpaymentarray[$recurring]['price'])*100;
						$finalstripeprice = $finalstrecurringprice;
						$finalrecurringplan = $recurringpaymentarray[$recurring]['plan'];
					}
					
					// GENERATE CORRECT VALUE FOR MESSAGE!
					$finalrecurringprice = $finalstripeprice/100;
					
					// GENERATE HERE YOUR CUSTOMER ID!
					$finalcustomerid = strtoupper(substr(md5(rand(1,999999)),0,10));
					
					// TICKET NUMBER FOR EASY CONTROL OF SUPPORT REQUESTS!
					$finalticket = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD & EXCEL ATTACHMENTS REQUESTS!
					$finalnumber1 = strtoupper(substr(md5(rand(0,999999)),0,6));
					
					// NUMBER FOR EASY CONTROL OF UPLOAD ATTACHMENTS REQUESTS!
					$finaltime1 = time() + 10;
					$finaltime2 = time() + 20;
					$finaltime3 = time() + 30;

					if($excelreports == true) {
						
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						include dirname(__FILE__).'/functions/reports/excel/reports.php';

						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($amazonmultipleupload == true) {
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($amazonsingleupload == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($multipleuploadfiles == true) {
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-recurring.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
							
							}
						}
						
					} else {
										
						if($finalnewsletter){ $finalnewsletter = $lang['form-newsletter-option-1']; } else { $finalnewsletter = $lang['form-newsletter-option-2']; }
						if($finalsendtome){ $finalsendtome = $lang['form-send-to-me-option-1']; } else { $finalsendtome = $lang['form-send-to-me-option-2']; }
												
						if($upload == true){
						
							if($multipleupload == true){
															
								include dirname(__FILE__).'/functions/upload/normal/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}

									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
									
							} elseif ($singleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/normal/single.php';
								
								if($newsletter == true) {
									
									if($mailchimp == true){
																	
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
											
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($dropboxmultipleupload == true){
														
								include dirname(__FILE__).'/functions/upload/dropbox/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($dropboxsingleupload == true) {
																					
								include dirname(__FILE__).'/functions/upload/dropbox/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																										
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($amazonmultipleupload == true) {
																												
								include dirname(__FILE__).'/functions/upload/amazon/multiple.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($amazonsingleupload == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/single.php';
								
								if($newsletter == true) {
								
									if($mailchimp == true){
																		
										if($finalnewsletter === $lang['form-newsletter-option-1']){
									
											include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
			
									} elseif($campaignmonitor == true) {
																											
										if($finalnewsletter === $lang['form-newsletter-option-1']){
										
											include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										} else {
																						
											include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
											if($multilanguage == true) {
												if (isset($arraylanguage[1])){
													include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
												} else {
													include dirname(__FILE__).'/languages/en.php';
												}
											} else {
												include dirname(__FILE__).'/languages/'.$language.'.php';
											}
											include dirname(__FILE__).'/settings/settings.php';
											include dirname(__FILE__).'/messages/administrator/message-recurring.php';
											include dirname(__FILE__).'/functions/notification/administrator/message.php';
											include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
										
										}
										
									} else {
										
										echo $lang['form-newsletter-method-message'];
										
									}
																		
								} else {
																		
									include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
									if($multilanguage == true) {
										if (isset($arraylanguage[1])){
											include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
										} else {
											include dirname(__FILE__).'/languages/en.php';
										}
									} else {
										include dirname(__FILE__).'/languages/'.$language.'.php';
									}
									include dirname(__FILE__).'/settings/settings.php';
									include dirname(__FILE__).'/messages/administrator/message-recurring.php';
									include dirname(__FILE__).'/functions/notification/administrator/message.php';
									include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
								
								}
								
							} elseif($multipleuploadfiles == true) {
																																	
								include dirname(__FILE__).'/functions/upload/normal/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
								}
								
							} elseif($dropboxmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/dropbox/multipleupload.php';
								
								if($uploadmove === true){
								
									if($newsletter == true){
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
								}
								
							} elseif($amazonmultipleuploadfiles == true) {
																																			
								include dirname(__FILE__).'/functions/upload/amazon/multipleupload.php';
								
								if($uploadmove === true){
									
									if($newsletter == true) {
									
										if($mailchimp == true){
																			
											if($finalnewsletter === $lang['form-newsletter-option-1']){
										
												include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
				
										} elseif($campaignmonitor == true) {
																												
											if($finalnewsletter === $lang['form-newsletter-option-1']){
											
												include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											} else {
																							
												include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
												if($multilanguage == true) {
													if (isset($arraylanguage[1])){
														include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
													} else {
														include dirname(__FILE__).'/languages/en.php';
													}
												} else {
													include dirname(__FILE__).'/languages/'.$language.'.php';
												}
												include dirname(__FILE__).'/settings/settings.php';
												include dirname(__FILE__).'/messages/administrator/message-recurring.php';
												include dirname(__FILE__).'/functions/notification/administrator/message.php';
												include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
											
											}
											
										} else {
											
											echo $lang['form-newsletter-method-message'];
											
										}
																			
									} else {
																			
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
								}
								
							} else {
								
								echo $lang['form-upload-method-message'];
								
							}

						} else {
							
							if($newsletter == true) {
							
								if($mailchimp == true){
															
									if($finalnewsletter === $lang['form-newsletter-option-1']){
								
										include dirname(__FILE__).'/functions/newsletter/mailchimp/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';	
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';	
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}

								} elseif($campaignmonitor == true) {
																								
									if($finalnewsletter === $lang['form-newsletter-option-1']){
									
										include dirname(__FILE__).'/functions/newsletter/campaign-monitor/newsletter.php';										
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									} else {
																					
										include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
										if($multilanguage == true) {
											if (isset($arraylanguage[1])){
												include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
											} else {
												include dirname(__FILE__).'/languages/en.php';
											}
										} else {
											include dirname(__FILE__).'/languages/'.$language.'.php';
										}
										include dirname(__FILE__).'/settings/settings.php';
										include dirname(__FILE__).'/messages/administrator/message-recurring.php';
										include dirname(__FILE__).'/functions/notification/administrator/message.php';
										include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
									
									}
									
								} else {
									
									echo $lang['form-newsletter-method-message'];
									
								}
																	
							} else {
																	
								include dirname(__FILE__).'/libraries/email/phpmailer/PHPMailerAutoload.php';
								if($multilanguage == true) {
									if (isset($arraylanguage[1])){
										include dirname(__FILE__).'/languages/'.$arraylanguage[1].'.php';
									} else {
										include dirname(__FILE__).'/languages/en.php';
									}
								} else {
									include dirname(__FILE__).'/languages/'.$language.'.php';
								}
								include dirname(__FILE__).'/settings/settings.php';
								include dirname(__FILE__).'/messages/administrator/message-recurring.php';
								include dirname(__FILE__).'/functions/notification/administrator/message.php';
								include dirname(__FILE__).'/functions/notification/customer/notification-recurring-stripe.php';
							
							}
						}
					}
					
				} else {
					
					echo $lang['form-payment-method-message'];
					
				}
				
			}
			
		} else {
			
			echo $lang['form-merchant-method-message'];
			
		}
	}	

?>	
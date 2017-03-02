<?php

/*	--------------------------------------------------
	:: PERSONAL SETTINGS
	-------------------------------------------------- */

	// ENTER YOUR NAME HERE!
  $yourname = 'Jon Schuster';

	// ENTER YOUR EMAIL HERE!
  $youremail = 'aonmedia@live.com';

	// ENTER YOUR COMPANY HERE!
	$company = 'National Office Products and Printing';

	// ENTER EMAIL CHARSET HERE | UTF-8 | DEFAULT ARE ISO-8859-1!
	$mailcharset = 'UTF-8';

	// ENTER EMAIL PRIORITY HERE | 1 ARE HIGH | DEFAULT ARE 3 | 5 ARE LOW!
	$mailpriority = '3';

	// ENTER YOUR DEFAULT TIMEZONE!
	// CHECK HERE YOUR TIMEZONE http://php.net/manual/en/timezones.php
  $timezone = date_default_timezone_set('America/Detroit');

	// IP OF CUSTOMER THAT SEND YOU A MESSAGE!
	$finaluserip = $_SERVER['REMOTE_ADDR'];

	// YOUR DATE TO ADD IN ADMIN MESSAGE!
	$localtime = date('l jS \of F Y h:i:s A');

/*	--------------------------------------------------
	:: MAIL SERVER SETTINGS
	-------------------------------------------------- */

	// IF YOU TURN TRUE MAIL TURN FALSE SMTP!
	$mail = true;

/*	--------------------------------------------------
	:: SMTP SERVER SETTINGS
	-------------------------------------------------- */

    // IF YOU TURN TRUE SMTP TURN FALSE MAIL!
	// CHOOSE SMTP WITH AUTHENTICATION!
	$smtpauth = false;

	// IF YOU TURN TRUE SMTP TURN FALSE MAIL!
	// CHOOSE SMTP WITHOUT AUTHENTICATION!
	$smtpnoauth = false;

	// PROTOCOL CAN BE ssl OR tls OR ''!
	// PORT CAN BE 465 OR 587 OR 25!
	$protocol = 'your-smtp-protocol';
	$host     = 'your-smtp-host';
	$port     = 'your-smtp-port';
	$username = 'your-smtp-username';
	$password = 'your-smtp-password';

	// JUT PUT IN DEBUG MODE TO CHECK ERRORS - JUST ADD 3 IN SMTPDEBUG!
	// 0 NO OUTPUT!
    // 1 COMMANDS!
    // 2 DATA AND COMMANDS!
    // 3 AS 2 PLUS CONNECTION STATUS!
    // 4 LOW LEVEL DATA OUTPUT!
	$smtpdebug = 0;

	// CAN BE echo OR html OR error_log!
	$debugoutput = 'error_log';

/*	--------------------------------------------------
	:: BASEURL SETTINGS
	-------------------------------------------------- */

	// ADD ANY FOLDER YOU NEED TO YOUR SCRIPT!
	$baseurl = '//'.$_SERVER['SERVER_NAME'].'/nopp/contact2/';

/*	--------------------------------------------------
	:: MULTIPLE ADMINISTRATORS SETTINGS
	-------------------------------------------------- */

	// TURN TRUE OR FALSE TO SEND MAIL TO MORE ADMINISTRATORS!
	// TURN TRUE ONE OF THIS THREE OPTIONS AVAILABLE!
	$administrators = false;
	$adminemailone   = 'aonmedia@live.com';
	$adminemailtwo   = 'aonmedia@live.com';
	$adminemailthree = 'aonmedia@live.com';
  $adminemailfour  = 'aonmedia@live.com';
  $adminemailfive  = 'aonmedia@live.com';

	$administratorscc = false;
  $adminccemailone   = 'aonmedia@live.com';
	$adminccemailtwo   = 'aonmedia@live.com';
	$adminccemailthree = 'aonmedia@live.com';
  $adminccemailfour  = 'aonmedia@live.com';
  $adminccemailfive  = 'aonmedia@live.com';

	$administratorsbcc = false;
  $adminbccemailone   = 'aonmedia@live.com';
	$adminbccemailtwo   = 'aonmedia@live.com';
	$adminbccemailthree = 'aonmedia@live.com';
  $adminbccemailfour  = 'aonmedia@live.com';
  $adminbccemailfive  = 'aonmedia@live.com';

	// ADD MORE ADMINISTRATORS IF YOU NEED IT!
    $multipleadministrators = array (
	    array('email' => $adminemailone, 'emailcc' => $adminccemailone, 'emailbcc' => $adminbccemailone),
		  array('email' => $adminemailtwo, 'emailcc' => $adminccemailtwo, 'emailbcc' => $adminbccemailtwo),
		  array('email' => $adminemailthree, 'emailcc' => $adminccemailthree, 'emailbcc' => $adminbccemailthree),
      array('email' => $adminemailfour, 'emailcc' => $adminccemailfour, 'emailbcc' => $adminbccemailfour),
      array('email' => $adminemailfive, 'emailcc' => $adminccemailfive, 'emailbcc' => $adminbccemailfive)
	  );

/*	--------------------------------------------------
	:: REDIRECT MESSAGES SETTINGS
	-------------------------------------------------- */

	// TURN TRUE IF YOU WANT REDIRECT MESSAGES!
	$redirect = true;

	// DONT NEED TO CHANGE NOTHING HERE!
	$supportsuccess       = 'php/redirect/support/success.php/';
	$servicesuccess       = 'php/redirect/service/success.php/';
	$paymentsuccess       = 'php/redirect/payment/success.php/';
	$subscriptionsuccess  = 'php/redirect/recurring/success.php/';
	$mailerror            = 'php/redirect/mail/error.php/';
	$emailcopyerror       = 'php/redirect/emailcopy/error.php/';
	$customererror        = 'php/redirect/customer/error.php/';
	$transactionerror     = 'php/redirect/transaction/error.php/';
	$automailerror        = 'php/redirect/automail/error.php/';
	$subscriptionerror    = 'php/redirect/subscription/error.php/';

	// DONT NEED TO CHANGE NOTHING HERE!
  $supportsuccess1      = $baseurl.$supportsuccess;
	$servicesuccess1      = $baseurl.$servicesuccess;
	$paymentsuccess1      = $baseurl.$paymentsuccess;
	$subscriptionsuccess1 = $baseurl.$subscriptionsuccess;
	$mailerror1           = $baseurl.$mailerror;
	$emailcopyerror1      = $baseurl.$emailcopyerror;
	$customererror1       = $baseurl.$customererror;
	$transactionerror1    = $baseurl.$transactionerror;
	$automailerror1       = $baseurl.$automailerror;
	$subscriptionerror1   = $baseurl.$subscriptionerror;

	// DONT NEED TO CHANGE NOTHING HERE!
	$supportsuccess2      = $baseurl.$supportsuccess.$arraylanguage[1];
	$servicesuccess2      = $baseurl.$servicesuccess.$arraylanguage[1];
	$paymentsuccess2      = $baseurl.$paymentsuccess.$arraylanguage[1];
	$subscriptionsuccess2 = $baseurl.$subscriptionsuccess.$arraylanguage[1];
	$mailerror2           = $baseurl.$mailerror.$arraylanguage[1];
	$emailcopyerror2      = $baseurl.$emailcopyerror.$arraylanguage[1];
	$customererror2       = $baseurl.$customererror.$arraylanguage[1];
	$transactionerror2    = $baseurl.$transactionerror.$arraylanguage[1];
	$automailerror2       = $baseurl.$automailerror.$arraylanguage[1];
	$subscriptionerror2   = $baseurl.$subscriptionerror.$arraylanguage[1];

/*	--------------------------------------------------
	:: SEND SMS NOTIFICATIONS SETTINGS
	-------------------------------------------------- */

	// YOU CAN REGISTER HERE AT https://www.textmagic.com/!
	// THIS IS ONLY FOR YOU RECEIVE NOTIFICATIONS MESSAGES ON YOUR MOBILE PHONE!
	// TURN TRUE OR FALSE TO RECEIVE SMS!
	$sendmobile = false;

	// ENTER HERE YOUR CREDENTIALS FROM TEXTMAGIC!
	$textmagicusername = 'your-text-magic-user-name';
	$textmagicpassword = 'your-text-magic-pass-word';

	// ADD HERE YOUR ADMINISTRATOR PHONE!
	// EXAMPLE :: ADD INDICATOR CODE OF YOUR COUNTRY + PHONE NUMBER!
	// CHECK HERE YOUR COUNTRY PHONE CODES http://en.wikipedia.org/wiki/List_of_country_calling_codes
	$phone = array(19066323095);

	// IF YOU USE UNICODE CHARACTER IN YOUR LANGUAGEf LIKE ARABIC, JAPANESE, RUSSIAN, CHINESE IN YOUR $notification TURN TRUE!
	$unicode = false;

	// EDIT HERE YOUR SMS MESSAGE!
	$notification = 'Hello '.$company.' you have receive a new message in your MailBox from your '.$support.' contact form';

/*	--------------------------------------------------
	:: SUPPORT SETTINGS
	-------------------------------------------------- */

	// TURN TRUE OR FALSE TO ONLY USE THIS FORM TO SUPPORT ONLY!
	// REMEMBER THAT IF YOU TURN TRUE SUPPORT TURN FALSE MERCHANTSERVICES & MERCHANTPAYMENT!
	$merchantsupport = true;

	// TURN TRUE OR FALSE IF YOU NEED MERCHANT SUPPORT WITH SELECT FIELD!
	// IF YOU TURN FALSE SUPPORT SELECT TURN FALSE SUPPORT ADMINISTRATORS!
	$supportselect = true;

	// DEFINE HERE IF YOU WANT SEND MAILS ACCORDING SUPPORT TO DIFFERENT DEPARTMENTS!
	$supportadministrators = true;

	// DEFINE HERE YOUR EMAILS!
	$emailsupportone   = 'pnshoostar@yahoo.com'; // EMAIL RELATED TO DEPARTMENT1!
	$emailsupporttwo   = 'aonmedia@live.com'; // EMAIL RELATED TO DEPARTMENT2!
	$emailsupportthree = 'aonmedia@live.com'; // EMAIL RELATED TO DEPARTMENT3!
	$emailsupportfour  = 'aonmedia@live.com'; // EMAIL RELATED TO DEPARTMENT4!
  $emailsupportfive  = 'aonmedia@live.com'; // EMAIL RELATED TO DEPARTMENT5!

	// DEFINE HERE YOUR SUPPORT EMAILS!
	// FOR SUPPORT NAMES JUST GO TO EN.PHP OR PT.PHP!
	$supportarray = array (
	  $lang['form-support-2'] => $emailsupportone,
	  $lang['form-support-3'] => $emailsupporttwo,
		$lang['form-support-4'] => $emailsupportthree,
		$lang['form-support-5'] => $emailsupportfour,
    $lang['form-support-6'] => $emailsupportfive
	);

/*	--------------------------------------------------
	:: SELL SERVICES WITHOUT PAYMENT SETTINGS
	-------------------------------------------------- */

	// TURN TRUE OR FALSE TO ACCEPT SERVICES!
	// REMEMBER THAT IF YOU TURN TRUE MERCHANTSERVICES TURN FALSE MERCHANTPAYMENT & MERCHANTSUPPORT!
	$merchantservice = false;

	$serviceone    = 200; // PRICE RELATED TO SERVICE1!
	$servicetwo    = 250; // PRICE RELATED TO SERVICE2!
	$servicethree  = 100; // PRICE RELATED TO SERVICE3!
	$servicefour   = 150; // PRICE RELATED TO SERVICE4!
	$servicefive   = 100; // PRICE RELATED TO SERVICE5!
	$servicesix    = 100; // PRICE RELATED TO SERVICE6!
	$serviceseven  = 150; // PRICE RELATED TO SERVICE7!

	// DEFINE HERE YOUR SERVICE PRODUCT CURRENCY!
	$servicecurrency = 'USD';

	// DEFINE HERE IF YOU WANT SEND MAILS ACCORDING SERVICE TO DIFFERENT DEPARTMENTS!
	$serviceadministrators = false;

	// DEFINE HERE YOUR EMAILS ACCORDING DEPARTMENTS!
	$emailserviceone    = 'example@example.com'; // EMAIL RELATED TO SERVICE1!
	$emailservicetwo    = 'example@example.com'; // EMAIL RELATED TO SERVICE2!
	$emailservicethree  = 'example@example.com'; // EMAIL RELATED TO SERVICE3!
	$emailservicefour   = 'example@example.com'; // ENAIL RELATED TO SERVICE4!
	$emailservicefive   = 'example@example.com'; // EMAIL RELATED TO SERVICE5!
	$emailservicesix    = 'example@example.com'; // EMAIL RELATED TO SERVICE6!
	$emailserviceseven  = 'example@example.com'; // ENAIL RELATED TO SERVICE7!

	// DEFINE HERE YOUR PRODUCT PRICES!
	// FOR PRODUCTS NAMES JUST GO TO EN.PHP OR PT.PHP!
	$servicearray = array (
	  $lang['form-value-service-1'] => array('price' => $serviceone, 'email' => $emailserviceone),
	  $lang['form-value-service-2'] => array('price' => $servicetwo, 'email' => $emailservicetwo),
		$lang['form-value-service-3'] => array('price' => $servicethree, 'email' => $emailservicethree),
		$lang['form-value-service-4'] => array('price' => $servicefour, 'email' => $emailservicefour),
		$lang['form-value-service-5'] => array('price' => $servicefive, 'email' => $emailservicefive),
		$lang['form-value-service-6'] => array('price' => $servicesix, 'email' => $emailservicesix),
		$lang['form-value-service-7'] => array('price' => $serviceseven, 'email' => $emailserviceseven)
	);

/*	--------------------------------------------------
	:: SELL SERVICES WITH PAYMENT
	-------------------------------------------------- */

	// TURN TRUE OR FALSE TO ACCEPT PAYMENT SERVICES!
	// REMEMBER THAT IF YOU TURN TRUE MERCHANTPAYMENT TURN FALSE MERCHANTSERVICES & MERCHANTSUPPORT!
	// TURN TRUE OR FALSE TO ACCEPT PAYMENTS SERVICES WITH PAYPAL AND/OR CREDIT CARD AND/OR STRIPE!
	// JUST SEE WHAT COUNTRIES BRAINTREE ACCEPT! https://www.braintreepayments.com/landing/international
	// JUST SEE WHAT COUNTRIES STRIPE ACCEPT! https://stripe.com/global
	$merchantpayment = false;

	// TURN TRUE OR FALSE TO ACCEPT PAYMENT METHODS!
	// YOU CAN ALSO ADD THE THREE PAYMENT METHODS AT SAME TIME!
	$creditcard = false;
	$paypal = false;
	$stripe = false;

	// DEFINE HERE YOUR PAYMENT PRODUCT CURRENCY!
	// THIS NEED TO BE EQUAL TO CURRENCY YOU DEFINE IN YOUR BRAINTREE OR STRIPE ACCOUNT!
	$paymentcurrency = 'USD';

/*	--------------------------------------------------
	:: ACTIVATE SELL SERVICES WITH ONE TIME PAYMENT
	-------------------------------------------------- */

	// TURN TRUE OR FALSE TO ACCEPT PAYMENT ONE TIME PAYMENT WITH BRAINTREE AND/OR STRIPE!
	// IF YOU TURN TRUE ONE TIME PAYMENT TURN FALSE RECURRING PAYMENT AND SO ON!
	$onetimepayment = false;

	// DEFINE HERE YOUR ONE TIME PRODUCT PRICES FOR BRAINTREE & STRIPE!
	$paymentone    = 200; // PRICE RELATED TO PAYMENT SERVICE1!
	$paymenttwo    = 250; // PRICE RELATED TO PAYMENT SERVICE2!
	$paymentthree  = 100; // PRICE RELATED TO PAYMENT SERVICE3!
	$paymentfour   = 150; // PRICE RELATED TO PAYMENT SERVICE4!
	$paymentfive   = 100; // PRICE RELATED TO PAYMENT SERVICE5!
	$paymentsix    = 100; // PRICE RELATED TO PAYMENT SERVICE6!
	$paymentseven  = 150; // PRICE RELATED TO PAYMENT SERVICE7!

	// DEFINE HERE IF YOU WANT SEND MAILS ACCORDING TO DIFFERENT DEPARTMENTS!
	$paymentadministrators = false;

	// DEFINE HERE YOUR EMAILS ACCORDING DIFFERENT DEPARTMENTS!
	$emailpaymentone    = 'example@example.com'; // EMAIL RELATED TO SERVICE1!
	$emailpaymenttwo    = 'example@example.com'; // EMAIL RELATED TO SERVICE2!
	$emailpaymentthree  = 'example@example.com'; // EMAIL RELATED TO SERVICE3!
	$emailpaymentfour   = 'example@example.com'; // ENAIL RELATED TO SERVICE4!
	$emailpaymentfive   = 'example@example.com'; // EMAIL RELATED TO SERVICE5!
	$emailpaymentsix    = 'example@example.com'; // EMAIL RELATED TO SERVICE6!
	$emailpaymentseven  = 'example@example.com'; // ENAIL RELATED TO SERVICE7!

	// DEFINE HERE YOUR DESCRIPTION FOR STRIPE PAYMENT PRODUCTS!
	// JUST GO TO EN.PHP OR PT.PHP!
	$onetimestripedesc = $lang['form-stripe-payment-description'];

	// DEFINE HERE YOUR PRODUCT PRICES & EMAILS FOR BRAINTREE & STRIPE ONE TIME PAYMENT!
	// FOR PRODUCTS NAMES JUST GO TO EN.PHP OR PT.PHP!
	$onetimepaymentarray = array (
	  $lang['form-value-service-1'] => array('price' => $paymentone, 'email' => $emailpaymentone),
	  $lang['form-value-service-2'] => array('price' => $paymenttwo, 'email' => $emailpaymenttwo),
		$lang['form-value-service-3'] => array('price' => $paymentthree, 'email' => $emailpaymentthree),
		$lang['form-value-service-4'] => array('price' => $paymentfour, 'email' => $emailpaymentfour),
		$lang['form-value-service-5'] => array('price' => $paymentfive, 'email' => $emailpaymentfive),
		$lang['form-value-service-6'] => array('price' => $paymentsix, 'email' => $emailpaymentsix),
		$lang['form-value-service-7'] => array('price' => $paymentseven, 'email' => $emailpaymentseven)
	);

/*	--------------------------------------------------
	:: ACTIVATE SELL SERVICES WITH RECURRNG PAYMENT
	-------------------------------------------------- */

	// TURN TRUE OR FALSE TO ACCEPT PAYMENT WITH RECURRING PAYMENT WITH BRAINTREE AND/OR STRIPE!
	// IF YOU TURN TRUE RECURRING PAYMENT TURN FALSE ONE TIME PAYMENT AND SO ON!
	$recurringpayment = false;

	// DEFINE HERE YOUR RECURRING PRODUCT PRICES FOR BRAINTREE & STRIPE!
	// AFTER DEFINE YOUR PRODUCTS PRICE GO TO YOUR BRAINTREE & STRIPE DASHBOARD AND ADD THIS PRICES IN PLANS THERE!
	$recurringone    = 100; // PRICE RELATED TO RECURRING PAYMENT SERVICE1!
	$recurringtwo    = 120; // PRICE RELATED TO RECURRING PAYMENT SERVICE2!
	$recurringthree  = 90;  // PRICE RELATED TO RECURRING PAYMENT SERVICE3!
	$recurringfour   = 80;  // PRICE RELATED TO RECURRING PAYMENT SERVICE4!
	$recurringfive   = 140; // PRICE RELATED TO RECURRING PAYMENT SERVICE5!
	$recurringsix    = 110; // PRICE RELATED TO RECURRING PAYMENT SERVICE6!
	$recurringseven  = 135; // PRICE RELATED TO RECURRING PAYMENT SERVICE7!

	// DEFINE HERE IF YOU WANT SEND MAILS ACCORDING TO DIFFERENT DEPARTMENTS!
	$recurringadministrators = false;

	// DEFINE HERE YOUR EMAILS ACCORDING DIFFERENT DEPARTMENTS!
	$emailrecurringone    = 'example@example.com'; // EMAIL RELATED TO SERVICE1!
	$emailrecurringtwo    = 'example@example.com'; // EMAIL RELATED TO SERVICE2!
	$emailrecurringthree  = 'example@example.com'; // EMAIL RELATED TO SERVICE3!
	$emailrecurringfour   = 'example@example.com'; // ENAIL RELATED TO SERVICE4!
	$emailrecurringfive   = 'example@example.com'; // EMAIL RELATED TO SERVICE5!
	$emailrecurringsix    = 'example@example.com'; // EMAIL RELATED TO SERVICE6!
	$emailrecurringseven  = 'example@example.com'; // ENAIL RELATED TO SERVICE7!

	// DEFINE HERE YOUR DESCRIPTION FOR STRIPE RECURRING PAYMENT PRODUCTS!
	// JUST GO TO EN.PHP OR PT.PHP!
	$recurringstripedesc = $lang['form-stripe-recurring-description'];

	// DEFINE HERE YOUR PLAN NAMES!
	// AFTER DEFINE YOUR PLAN NAMES GO TO YOUR BRAINTREE OR STRIPE DASHBOARD AND ADD THIS ON ID IN PLANS THERE!
	// ALSO IS BETTER DEFINE A SERVICE NAME IN EN.PHP OR PT.PHP EQUAL TO BELOW PLANS IN YOUR MAIN LANGUAGE!
	$recurringplanone    = 'Contact-Framework-PHP';       // NAME RELATED TO RECURRING PLAN SERVICE1!
	$recurringplantwo    = 'Contact-Framework-Pro-PHP';   // NAME RELATED TO RECURRING PLAN SERVICE2!
	$recurringplanthree  = 'Contact-Framework-CSS';       // NAME RELATED TO RECURRING PLAN SERVICE3!
	$recurringplanfour   = 'Contact-Framework-JS';        // NAME RELATED TO RECURRING PLAN SERVICE4!
	$recurringplanfive   = 'Menu-Framework-CSS';          // NAME RELATED TO RECURRING PLAN SERVICE5!
	$recurringplansix    = 'Accordion-Framework-CSS';     // NAME RELATED TO RECURRING PLAN SERVICE6!
	$recurringplanseven  = 'Tabs-Framework-CSS';          // NAME RELATED TO RECURRING PLAN SERVICE7!

	// DEFINE HERE YOUR PRODUCT PRICES & EMAILS & PLANS FOR BRAINTREE & STRIPE RECURRING!
	// FOR SERVICES NAMES JUST GO TO EN.PHP OR PT.PHP!
	$recurringpaymentarray = array (
	  $lang['form-value-service-1'] => array('price' => $recurringone, 'plan' => $recurringplanone, 'email' => $emailrecurringone),
	  $lang['form-value-service-2'] => array('price' => $recurringtwo, 'plan' => $recurringplantwo, 'email' => $emailrecurringtwo),
		$lang['form-value-service-3'] => array('price' => $recurringthree, 'plan' => $recurringplanthree, 'email' => $emailrecurringthree),
		$lang['form-value-service-4'] => array('price' => $recurringfour, 'plan' => $recurringplanfour, 'email' => $emailrecurringfour),
		$lang['form-value-service-5'] => array('price' => $recurringfive, 'plan' => $recurringplanfive, 'email' => $emailrecurringfive),
		$lang['form-value-service-6'] => array('price' => $recurringsix, 'plan' => $recurringplansix, 'email' => $emailrecurringsix),
		$lang['form-value-service-7'] => array('price' => $recurringseven, 'plan' => $recurringplanseven, 'email' => $emailrecurringseven)
	);

/*	--------------------------------------------------
	:: ADD HERE YOUR PROCESSOR CREDENTIALS BRAINTREE
	-------------------------------------------------- */

	// DEFINE HERE YOUR BRAINTREE CREDENTIALS!
	if($merchantpayment == true && $onetimepayment == true && $creditcard == true || $merchantpayment == true && $onetimepayment == true && $paypal == true || $merchantpayment == true && $recurringpayment == true && $creditcard == true || $merchantpayment == true && $recurringpayment == true && $paypal == true){
		$merchantenrironment = 'your-braintree-enrironment';  // CAN BE SANDBOX OR PRODUCTION IN LOWERCASE!
		$merchantid          = 'your-braintree-merchantid';   // YOUR BRAINTREE MERCHANT ID!
		$merchantpublickey   = 'your-braintree-publickey';    // YOUR BRAINTREE PUBLIC KEY!
		$merchantprivatekey  = 'your-braintree-privatekey';   // YOUR BRAINTREE PRIVATE KEY!

		// THIS IS FOR BRAINTREE PROCESS YOUR PAYMENTS TO YOUR BANK ACCOUNT!
		$submitforsettlement = true;

		// STORE CUSTOMER INFO INCLUDING CREDIT CARD IN BRAINTREE SERVERS!
		$storeinvault = true;
	}

/*	--------------------------------------------------
	:: ADD HERE YOUR PROCESSOR CREDENTIALS STRIPE
	-------------------------------------------------- */

	// DEFINE HERE YOUR STRIPE CREDENTIALS!
	if($merchantpayment == true && $stripe == true){
		$stripesecretkey = 'your-stripe-secret-key';
	}

/*	--------------------------------------------------
	:: ADD HERE DOCSIGN CREDENTIALS
	-------------------------------------------------- */

	// ACTIVATE HERE IF YOU NEED THIS FEATURE!
	$signature = false;

	// OPEN AN SANDBOX ACCOUNT TO TEST IT https://www.docusign.com/developer-center
	// THIS IS IF YOU NEED TO SEND SOME FILE TO YOUR CUSTOMER TO SIGN!
	// WHEN YOUR CUSTOMER PAY FOR YOUR SERVICES LIKE CONTRACT OR INVOICE!
	$docsignusername = 'your-docsign-username';
	$docsignpassword = 'your-docsign-password';
	$docsignintegratorkey = 'your-docsign-integrator-key';

	// ADD HERE PRODUCTION IN LOWERCASE TO REAL ENVIRONMENT!
	$docsignenvironment = 'sandbox';

	// ADD HERE YOUR DOCUMENT NAME!
	// GO TO FUNCTIONS SIGNATURE TO CHECK DOCUMENT!
	$docsignfile = '/web-design-service.pdf';
	$docsigndocumentname = 'web-design-service.pdf';
	$docsignemaildescription = ''.$company.' Design Services - Please read and sign ...';

	// ADD STATUS TO SENT TO SEND AUTOMATICALLY WHEN USER PAY FOR SERVICE!
	$docsignstatus = 'sent';

	// THIS IS YOUR ENVIRONMENT FOR TEST OR REAL!
	if($docsignenvironment === 'sandbox'){
	    $docsignhost = 'https://demo.docusign.net/restapi';
	} else {
		$docsignhost = 'https://www.docusign.net/restapi';
	}

/*	-------------------------------------------------------------
	:: FORM VALIDATION TRUE OR FALSE SERVER SIDE AND CLIENT SIDE
	------------------------------------------------------------- */

  $firstnamefield              = true;  // TURN TRUE OR FALSE!
	$firstnameerrorfield         = true;  // TURN TRUE OR FALSE!
	$lastnamefield               = true;  // TURN TRUE OR FALSE!
	$lastnameerrorfield          = true;  // TURN TRUE OR FALSE!
	$emailfield                  = true;  // TURN TRUE OR FALSE!
	$emailerrorfield             = true;  // TURN TRUE OR FALSE!
	$subjectfield                = false;  // TURN TRUE OR FALSE!
	$messagefield                = true;  // TURN TRUE OR FALSE!
	$supportfield                = true;  // TURN TRUE OR FALSE!
	$upload1field                = false;  // TURN TRUE OR FALSE!
	$upload2field                = false;  // TURN TRUE OR FALSE!
	$upload3field                = false;  // TURN TRUE OR FALSE!
	$uploadmultiplefield         = false;  // TURN TRUE OR FALSE!
	$servicefield                = true;  // TURN TRUE OR FALSE!
	$paymentfield                = false;  // TURN TRUE OR FALSE!
	$recurringfield              = false;  // TURN TRUE OR FALSE!
	$cardnamefield               = false;  // TURN TRUE OR FALSE!
	$cardnameerrorfield          = false;  // TURN TRUE OR FALSE!
	$cardnumberfield             = false;  // TURN TRUE OR FALSE!
	$cardnumbererrorfield        = false;  // TURN TRUE OR FALSE!
	$carddatefield               = false;  // TURN TRUE OR FALSE!
	$carddateerrorfield          = false;  // TURN TRUE OR FALSE!
	$cardmonthfield              = false;  // TURN TRUE OR FALSE!
	$cardmontherrorfield         = false;  // TURN TRUE OR FALSE!
	$cardyearfield               = false;  // TURN TRUE OR FALSE!
	$cardyearerrorfield          = false;  // TURN TRUE OR FALSE!
	$cardcvvfield                = false;  // TURN TRUE OR FALSE!
	$cardcvverrorfield           = false;  // TURN TRUE OR FALSE!
	$newsletterfield             = false;  // TURN TRUE OR FALSE!
	$sendtomefield               = false;  // TURN TRUE OR FALSE!
	$captchafield                = false;  // TURN TRUE OR FALSE!

/*	--------------------------------------------------
	:: SUCCESS OR ERROR FORM MESSAGE SETTINGS
	-------------------------------------------------- */

	// CHOOSE WHERE YOU WANT TO SEE SUCCESS OR ERROR MESSAGE IN TOP OR BOTTOM OF FORM!
	$messagetop = false;
	$messagebottom = true;

/*	--------------------------------------------------
	:: MULTI THEME SETTINGS
	-------------------------------------------------- */

	// CHOOSE ONE THEME FROM THIS THREE OPTIONS!
	$themedefault = true;
	$themeflat = false;
	$thememinimal = false;

/*	--------------------------------------------------
	:: LOADER FORM SETTINGS
	-------------------------------------------------- */

	// IF YOU NEED A LOADER TURN TRUE!
	$loader = false;

/*	--------------------------------------------------
	:: RESPONSIVE CODE SETTINGS
	-------------------------------------------------- */

	// IF YOU NEED RESPONSIVE CODE TURN TRUE!
	$responsive = true;

/*	--------------------------------------------------
	:: NEWSLETTER OPTION SETTINGS
	-------------------------------------------------- */

	// IF YOU WANT NEWSLETTER TURN TRUE!
	$newsletter = false;

	// TURN MAILCHIMP TRUE OR FALSE!
	$mailchimp = false;

	// GO TO YOUR ACCOUNT SETTINGS AND GET YOUR MAILCHIMP APIKEY & LISTID!
	$mailchimpkey = 'your-mailchimp-api-key';
	$mailchimplistid = 'your-mailchimp-list-id';

	// TURN CAMPAIGN MONITOR TRUE OR FALSE!
	$campaignmonitor = false;

	// GO TO YOUR ACCOUNT SETTINGS AND GET YOUR CAMPAIGN MONITOR APIKEY & LISTID!
	$campaignmonitorkey = 'your-campaignmonitor-key';
	$campaignmonitorlistid = 'your-campaignmonitor-list-id';

/*	--------------------------------------------------
	:: SEND TO ME EMAIL COPY OPTION SETTINGS
	-------------------------------------------------- */

	// TURN TRUE IF YOU WANT GIVE THAT OPTION TO CUSTOMERS!
	$sendcopytome = false;

/*	--------------------------------------------------
	:: SAVE FORM INFO IN EXCEL FILE SETTINGS
	-------------------------------------------------- */

	// IF YOU NEED EXCEL TO EXPORT FORM INFO TURN TRUE!
	$excelreports = false;

	// IF YOU NEED FORM EXCEL ATTACHMENTS TURN TRUE!
	// ALSO IF YOU LOST YOUR EXCEL FILES GO TO EXCEL FOLDER AND FILES ARE THERE!
    $excelattachments = false;

/*	--------------------------------------------------
	:: SECURITY CSRF SETTINGS
	-------------------------------------------------- */

	// IF YOU NEED TURN TRUE SECURITY CSRF!
	$security = true;

	// ADD HERE YOUR SECRET WORD FOR FORM!
	// IF YOU NEED MORE FORMS ON SAME PAGE ADD DIFFERENT WORDS!
	$secret = 'contact-form';

/*	--------------------------------------------------
	:: MULTILANGUAGE ON FORM SETTINGS
	-------------------------------------------------- */

	// IF YOU WANT TURN TRUE MULTILANGUAGE!
	$multilanguage = false;

/*	--------------------------------------------------
	:: MULTILANGUAGE OFF FORM SETTINGS
	-------------------------------------------------- */

	// IF YOU WANT TURN FALSE MULTILANGUAGE!
	// ADD HERE en OR pt!
	$language = 'en';

/*	--------------------------------------------------
	:: UPLOAD FORM SETTINGS
	-------------------------------------------------- */

	// IF YOU WANT UPLOAD FIELDS TURN TRUE!
	$upload = false;

	// IF YOU NEED UPLOAD ATTACHMENTS TURN TRUE!
	// IF YOU TURN FALSE ATTACHMENTS UPLOAD FILES GO TO UPLOAD FOLDER!
	// DONT FORGET TO ADD YOUR URL IN HTACCESS FILE TO DISABLE ACCESS FROM URL TO FILES!
    $uploadattachments = false;

	// IF YOU NEED UPLOAD ATTACHMENTS TURN TRUE!
	// IF YOU TURN FALSE ATTACHMENTS UPLOAD FILES GO TO DROPBOX FOLDER!
	// DONT FORGET TO ADD YOUR URL IN HTACCESS FILE TO DISABLE ACCESS FROM URL TO FILES!
    $dropboxattachments = false;

	// IF YOU NEED UPLOAD ATTACHMENTS TURN TRUE!
	// IF YOU TURN FALSE ATTACHMENTS UPLOAD FILES GO TO AMAZON FOLDER!
	// DONT FORGET TO ADD YOUR URL IN HTACCESS FILE TO DISABLE ACCESS FROM URL TO FILES!
    $amazonattachments = false;

	// IF YOU TURN TRUE UPLOAD CHOOSE ONE OF THIS OPTIONS!
	// THIS OPTINS IS FOR INPUT TO INPUT, FOR MULTIPLE FILES WITH ONE INPUT CHOOSE BELOW!
	$singleupload = false;
	$multipleupload = false;
	$dropboxsingleupload = false;
	$dropboxmultipleupload = false;
	$amazonsingleupload = false;
	$amazonmultipleupload = false;

	// IF YOU DON´T LIKE ADD INPUT A INPUT TURN ONE OF THIS THREE OPTIONS TO TRUE!
	// TO UPLOAD MULTIPLE FILES WITH ONE INPUT!
	$multipleuploadfiles = false;
	$dropboxmultipleuploadfiles = false;
	$amazonmultipleuploadfiles = false;

	// CREATE YOUR APP HERE https://www.dropbox.com/developers/apps
	// CHOOSE DROPBOX API APP!
	// YOU CAN CHOOSE LIMIT YOUR APP TO ITS FOLDER OR NOT!
	// CREATE A NAME FOR YOUR APP!
	// THEN GENERATE A TOKEN AND PASTE IT HERE!
	// JUST DONT FORGET THAT IN BEGGINING OF FOLDER NAME IT NEED TO HAVE A SLASH!
	// IF YOU DONT CREATE IN DROPBOX A FOLDER IT WILL CREATE ONE FOR YOU WITH BELOW NAME!
	$dropboxtoken = 'your-dropbox-token';
	$dropboxfolder = '/your-dropbox-folder';

	// GO TO AMAZON S3 SIGNUP PAGE https://aws.amazon.com/pt/s3/ AND CREATE YOUR ACCOUNT!
	// GO TO YOUR ACCOUNT SETTINGS AND GET CREDENTIALS!
	// IF YOU DONT CREATE IN AMAZON S3 A FOLDER IT WILL CREATE ONE FOR YOU WITH BELOW NAME!
	$accesskey = 'your-amazon-s3-access-key';
	$accesssecret = 'your-amazon-s3-access-secret';
	$amazonfolder = 'your-amazon-s3-folder';

/*	--------------------------------------------------
	:: FORM CLIENT SIDE VALIDATION UPLOAD
	-------------------------------------------------- */

	// ADD HERE YOUR UPLOAD EXTENSIONS!
	$uploadclienttypes = 'image/jpeg,image/pjpeg,image/png,image/gif,image/bmp,image/tiff,text/plain,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-word,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,application/zip,multipart/x-zip,application/x-zip-compressed,application/x-compressed';

	// ADD HERE YOUR UPLOAD SIZE!
	// GO HERE TO CONVERT YOUR FILE SIZE http://www.gbmb.org/mb-to-bytes
	$uploadclientsize = '10485760'; // THIS REPRESENDT 10 MEGABYTES IN BINARY MODE!

	// ADD HERE YOUR MIN UPLOAD FILES IF YOU ARE USING MULTIPLE FILES LIKE IN ONE UPLOAD FIELD!
	$uploadclientminfiles = '2';

	// ADD HERE YOUR MAX UPLOAD FILES IF YOU ARE USING MULTIPLE FILES LIKE IN ONE UPLOAD FIELD!
	$uploadclientmaxfiles = '5';

/*	--------------------------------------------------
	:: FORM SERVER SIDE VALIDATION UPLOAD
	-------------------------------------------------- */

	// IF YOU NEED MORE MIMETYPES TRY FIND HERE http://www.freeformatter.com/mime-types-list.html
	// ADD HERE YOUR UPLOAD FILE MIMETYPES!
    $uploadservertypes = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/tiff','text/plain','application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.ms-word','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/pdf','application/zip','multipart/x-zip','application/x-zip-compressed','application/x-compressed');

	// ADD HERE YOUR UPLOAD SIZE!
	// GO HERE TO CONVERT YOUR FILE SIZE http://www.gbmb.org/mb-to-bytes
	$uploadserversize = '10485760';  // THIS REPRESENDT 10 MEGABYTES IN BINARY MODE!

/*	--------------------------------------------------
	:: SECURTY CAPTCHA FORM SETTINGS
	-------------------------------------------------- */

	// IF YOU WANT CAPTCHA TURN TRUE!
	$captcha = true;

	// IF YOU TURN SECURITY TRUE CHOOSE ONE OF THIS OPTIONS!
	$alphacaptcha = false;
	$mathcaptcha = false;
	$recaptcha = true;

	// ADD HERE YOUR RECAPTCHA SECRET AND SITE KEY - REGISTER BELOW!
	// http://www.google.com/recaptcha/intro/
	$sitekey = '6Lf1whMUAAAAAAoQMmoy9oC0Xsi5_EEyBoYL9vQI';
	$secretkey = '6Lf1whMUAAAAAHoyNFRPQbfNxlxQX3LH1xIKeL6n';
	$recaptchatheme = 'light'; // light or dark!

/*	--------------------------------------------------
	:: AUTOREPLY MESSAGE SETTINGS
	-------------------------------------------------- */

	// IF YOU WANT AUTOMESSAGE TURN TRUE!
	$automessage = true;

	// ENTER YOR SUPPORT URL FOR AUTOMESSAGE!
	$supporturl = $baseurl.'support-folder/';

/*	--------------------------------------------------
	:: MYSQL DATABASE SETTINGS
	-------------------------------------------------- */

	// IF YOU WANT MYSQL DATABASE TURN TRUE!
	$mysql = false;

	$tablenamesupport	= 'contact_support';
	$tablenameservice   = 'contact_service';
	$tablenamepayment   = 'contact_payment';
	$tablenamerecurring = 'contact_recurring';

	// ADD HERE YOUR DATABASE INFO!
	$connecthostname  = 'your-database-hostname';
	$connectusername  = 'your-database-username';
	$connectpassword  = 'your-database-password';
	$connectdatabase  = 'your-database';

	if ($mysql){
		$connection = mysqli_connect($connecthostname,$connectusername,$connectpassword);
		$database = mysqli_select_db($connection,$connectdatabase);
	}

	// DONT CHANGE HERE IS FOR MYSQL ERROR!
	$mysqlerror = mysqli_connect_error();

/*	--------------------------------------------------
	:: DUPLICATE EMAILS IN DATABASE MYSQL ON SETTINGS
	-------------------------------------------------- */

	// TURN THIS TO TRUE FOR PREVENT DUPLICATE EMAILS!
	$preventdoubleemail = false;

	// DONT CHANGE THIS VARIABLE THIS IS FOR CHECK DUPLICATE EMAILS!
	$selectemailsupport    = 'contact_support_email';
	$selectserviceemail    = 'contact_service_email';
	$selectpaymentemail    = 'contact_payment_email';
	$selectrecurringemail  = 'contact_recurring_email';

/*	--------------------------------------------------
	:: MYSQL INSERT VALUES ON DATABASE SETTINGS
	-------------------------------------------------- */

	// ADD HERE YOUR DATABASE VALUES FOR SUPPORT FORM!
	$insertsupportvalues = array (
		'contact_support_date' => $localtime,
		'contact_support_firstname' => $finalfirstname,
		'contact_support_lastname' => $finallastname,
		'contact_support_email' => $finalemail,
		'contact_support_subject' => $finalsubject,
		'contact_support_message' => $finalmessage,
		'contact_support_ticket' => $finalticket,
		'contact_support_newsletter' => $finalnewsletter,
		'contact_support_sendtome' => $finalsendtome
	);

	// ADD HERE YOUR DATABASE VALUES FOR SERVICES FORM!
	$insertservicevalues = array (
		'contact_service_date' => $localtime,
		'contact_service_firstname' => $finalfirstname,
		'contact_service_lastname' => $finallastname,
		'contact_service_email' => $finalemail,
		'contact_service_subject' => $finalsubject,
		'contact_service_message' => $finalmessage,
		'contact_service_service' => $finalservices,
		'contact_service_price' => $finalserviceprice,
		'contact_service_ticket' => $finalticket,
		'contact_service_newsletter' => $finalnewsletter,
		'contact_service_sendtome' => $finalsendtome
	);

	// ADD HERE YOUR DATABASE VALUES FOR PAYMENT FORM!
	$insertpaymentvalues = array (
		'contact_payment_date' => $localtime,
		'contact_payment_firstname' => $finalfirstname,
		'contact_payment_lastname' => $finallastname,
		'contact_payment_email' => $finalemail,
		'contact_payment_subject' => $finalsubject,
		'contact_payment_message' => $finalmessage,
		'contact_payment_customerid' => $finalcustomerid,
		'contact_payment_service' => $finalpayments,
		'contact_payment_price' => $finalpaymentprice,
		'contact_payment_ticket' => $finalticket,
		'contact_payment_method' => $finalmethod,
		'contact_payment_newsletter' => $finalnewsletter,
		'contact_payment_sendtome' => $finalsendtome
	);

	// ADD HERE YOUR DATABASE VALUES FOR RECURRING FORM!
	$insertrecurringvalues = array (
		'contact_recurring_date' => $localtime,
		'contact_recurring_firstname' => $finalfirstname,
		'contact_recurring_lastname' => $finallastname,
		'contact_recurring_email' => $finalemail,
		'contact_recurring_subject' => $finalsubject,
		'contact_recurring_message' => $finalmessage,
		'contact_recurring_customerid' => $finalcustomerid,
		'contact_recurring_plan' => $finalrecurrings,
		'contact_recurring_price' => $finalrecurringprice,
		'contact_recurring_ticket' => $finalticket,
		'contact_recurring_method' => $finalmethod,
		'contact_recurring_newsletter' => $finalnewsletter,
		'contact_recurring_sendtome' => $finalsendtome
	);

?>

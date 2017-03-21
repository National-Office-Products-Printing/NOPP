<?php

    error_reporting(0);

    include dirname(__FILE__).'/php/functions/sessions/sessions.php';
 
    // SESSION START!
    secure_session($sessionname);
		
    // INCLUDE ALL NECESSARY FILES!
	include dirname(__FILE__).'/php/libraries/security/security/security.php';
	include dirname(__FILE__).'/php/libraries/payment/braintree-sdk/lib/autoload.php';
	include dirname(__FILE__).'/php/libraries/payment/stripe-sdk/loader.php';
	include dirname(__FILE__).'/php/libraries/sms/textmagic-sms-class/TextMagicAPI.php';
	include dirname(__FILE__).'/php/settings/settings.php';
	include dirname(__FILE__).'/php/functions/language/language.php';
	
	// CALL FOR BRAINTREE KEYS EDIT AT SETTINGS.PHP!
	if($merchantpayment == true && $creditcard == true || $merchantpayment == true && $paypal == true){
		$braintreeenvironment = Braintree_Configuration::environment($merchantenrironment);
		$braintreemerchantid = Braintree_Configuration::merchantId($merchantid);
		$braintreepublickey = Braintree_Configuration::publicKey($merchantpublickey);
		$braintreeprivatekey = Braintree_Configuration::privateKey($merchantprivatekey);
		
		// GENERATE A TOKEN TO FORM INFO - DONT REMOVE OR CHANGE!
		if($creditcard == true){
			$creditcardtoken = Braintree_ClientToken::generate();
		}
		
		// GENERATE A TOKEN TO FORM INFO - DONT REMOVE OR CHANGE!
		if($paypal == true){
			$paypaltoken = Braintree_ClientToken::generate();
		}
	}
	
	// CALL SECURITY CLASS INSTANCE!	
	if($security == true){
		$secure = new Security($secret);
	}
	
	// ADD HERE YOUR CALL FOR MULTILANGUAGE!
	if($multilanguage == true) {
		if (isset($arraylanguage[1])){
	        include dirname(__FILE__).'/php/languages/'.$arraylanguage[1].'.php';
		} else {
			include dirname(__FILE__).'/php/languages/en.php';
		}
	} else {
		include dirname(__FILE__).'/php/languages/'.$language.'.php';
	}
			
?> 
<!DOCTYPE html>
<html>
    <head>
	
        <meta charset="utf-8">
	    <meta name="author" content="<?php echo $lang['form-website-author'];?>">
		<meta name="description" content="<?php echo $lang['form-website-description'];?>">
		<meta name="keywords" content="<?php echo $lang['form-website-keywords'];?>">
        <title><?php echo $lang['form-website-title'];?></title>
		
		<!-- Viewport -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<!-- Favicon -->
        <link rel="shortcut icon" type="image/png" href="<?php echo $baseurl;?>images/favicon.png">
		
		<!-- Css Styles -->
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/structure.css">
		<?php if($themedefault == true){ ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/themes/default/settings.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/themes/default/theme.css">
		<?php } elseif($themeflat == true) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/themes/flat/settings.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/themes/flat/theme.css">
		<?php } else { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/themes/minimal/settings.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/themes/minimal/theme.css">
		<?php } ?>
		
		<?php if($responsive == true){ ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/responsive.css">
		<?php } ?>
		
		<!-- Font Link -->
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:400,700,900|Raleway:400,700|PT+Sans|Open+Sans">			
		
		<!-- Stripe Library -->
		<?php if($merchantpayment == true && $stripe == true){ ?>
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
		<?php } ?>
		
		<!-- Jquery Library -->
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		
		<!-- Js Files -->
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-customize.js"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-validate.js"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-methods.js"></script>
        <script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-form.js"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-placeholder.js"></script>
		<?php if($multilanguage == true && $arraylanguage[1] == 'en') { ?>
		<?php if($recaptcha == true){ ?>
		<script type="text/javascript" src='https://www.google.com/recaptcha/api.js?hl=en'></script>
		<?php } ?>
		<?php } elseif($multilanguage == true && $arraylanguage[1] == '') { ?>
		<?php if($recaptcha == true){ ?>
		<script type="text/javascript" src='https://www.google.com/recaptcha/api.js?hl=en'></script>
		<?php } ?>
		<?php } elseif($multilanguage == true && $arraylanguage[1] == 'pt') { ?>
		<?php if($recaptcha == true){ ?>
		<script type="text/javascript" src='https://www.google.com/recaptcha/api.js?hl=pt'></script>
		<?php } ?>
		<?php } elseif($multilanguage == false && $language == 'en') { ?>
		<?php if($recaptcha == true){ ?>
		<script type="text/javascript" src='https://www.google.com/recaptcha/api.js?hl=en'></script>
		<?php } ?>
		<?php } elseif($multilanguage == false && $language == 'pt') { ?>
		<?php if($recaptcha == true){ ?>
		<script type="text/javascript" src='https://www.google.com/recaptcha/api.js?hl=pt'></script>
		<?php } ?>
		<?php } ?>
		<?php if($merchantservice == true || $merchantpayment == true){ ?>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-services.js"></script>
		<?php } ?>
		<?php if($merchantpayment == true){ ?>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-payment.js"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-payment-custom.js"></script>
		<?php } ?>
		
    </head>
	
    <body>
	
	    <?php if($loader == true){ ?>
	    <!-- LOADER START -->
		<div class="loader-container loader-container-info">
			<div class="loader-center">
				<div class="loader-center-absolute">
					<div class="loader line1"></div>
					<div class="loader line2"></div>
					<div class="loader line3"></div>
					<div class="loader line4"></div>
				</div>
			</div>
		</div>
		<!-- LOADER END -->
	    <?php } ?>
		
		<noscript>
		    <div class="container container-fixed" id="noscript">
				<?php if($themeflat == true){ ?>
				<div class="form-bar">
					<div class="top-bar bar-green"></div>
					<div class="top-bar bar-orange"></div>
					<div class="top-bar bar-yellow"></div>
					<div class="top-bar bar-red"></div>
					<div class="top-bar bar-purple"></div>
					<div class="top-bar bar-pink"></div>
					<div class="top-bar bar-blue-dark"></div>
					<div class="top-bar bar-blue"></div>
				</div>
				<?php } ?>
				<?php if($themedefault == true){ ?>
				<div class="form form-default form-blue-default form-light-default">
				<?php } ?>
				<?php if($themeflat == true){ ?>
				<div class="form form-flat form-blue-flat form-light-flat">
				<?php } ?>
				<?php if($thememinimal == true){ ?>
				<div class="form form-minimal form-blue-minimal form-light-minimal">
				<?php } ?>
					<div class="pre-header"></div>
					<div class="pre-header-description">
						<div class="company-holder">
							<div class="company-border">							
								<div class="company-logo"></div>
							</div>
						</div>
						<div class="company-description">
							<h4><?php echo $lang['form-pre-header-title'];?></h4>
							<p><i class="icon-twitter"></i><?php echo $lang['form-pre-header-description-1'];?></p>
							<a href="#"><?php echo $lang['form-pre-header-description-2'];?></a>
						</div>
					</div>
					<?php if($themedefault == true){ ?>
					<div class="grid-container">
						<div class="column twelve">
							<div class="arrow"></div>
						</div>
					</div>
					<?php } ?>
					<div class="section-noscript-message">
						<?php echo $lang['form-noscript-message'];?>
					</div>	
                    <div class="footer">
                        <div class="grid-container">
                            <div class="column twelve">
                                <p><?php echo $lang['form-footer'];?></p>
							    <p class="copyright"><i class="icon-lock2"></i><?php echo $lang['form-footer-copyright'];?></p>
                            </div>
                        </div>
                    </div>
				<?php if($themedefault == true){ ?>
				</div>
				<?php } ?>
				<?php if($themeflat == true){ ?>
				</div>
				<?php } ?>
				<?php if($thememinimal == true){ ?>
				</div>
				<?php } ?>
			</div>
		</noscript>
		
        <div class="container container-fixed" id="wrapper">
		    <?php if($themeflat == true){ ?>
		    <div class="form-bar">
                <div class="top-bar bar-green"></div>
                <div class="top-bar bar-orange"></div>
                <div class="top-bar bar-yellow"></div>
			    <div class="top-bar bar-red"></div>
                <div class="top-bar bar-purple"></div>
                <div class="top-bar bar-pink"></div>
			    <div class="top-bar bar-blue-dark"></div>
                <div class="top-bar bar-blue"></div>
            </div>
			<?php } ?>
		    <?php if($themedefault == true){ ?>
			<div class="form form-default form-blue-default form-light-default">
			<?php } ?>
			<?php if($themeflat == true){ ?>
			<div class="form form-flat form-blue-flat form-light-flat">
			<?php } ?>
			<?php if($thememinimal == true){ ?>
			<div class="form form-minimal form-blue-minimal form-light-minimal">
			<?php } ?>
			    <div class="pre-header"></div>
				<div class="pre-header-description">
					<div class="company-holder">
						<div class="company-border">							
							<div class="company-logo"></div>
						</div>
					</div>
					<div class="company-description">
						<h4><?php echo $lang['form-pre-header-title'];?></h4>
						<p><i class="icon-twitter"></i><?php echo $lang['form-pre-header-description-1'];?></p>
						<a href="#"><?php echo $lang['form-pre-header-description-2'];?></a>
					</div>
				</div>
				<?php if($themedefault == true){ ?>
				<div class="grid-container">
					<div class="column twelve">
						<div class="arrow"></div>
					</div>
				</div>
				<?php } ?>
				<div class="header">
					<div class="grid-container">
				        <div class="column twelve">
							<h4><?php echo $lang['form-header-title'];?></h4>
							<p><?php echo $lang['form-header-description'];?></p>
						</div>
						<?php if($multilanguage == true) { ?>
						<div class="column twelve">
						    <div class="language">
								<a href="<?php echo $baseurl;?>index.php/pt/"><img src="<?php echo $baseurl;?>images/flags/Portugal.png"></a>
								<a href="<?php echo $baseurl;?>index.php/en/"><img src="<?php echo $baseurl;?>images/flags/United-Kingdom.png"></a>
							</div>
						</div>
						<?php } ?>
				    </div>
				</div>
				<div class="section">
					<?php if($multilanguage == true) { ?>
					<form method="post" action="<?php echo $baseurl;?>php/processor.php/<?php if($arraylanguage[1]) { echo $arraylanguage[1]; } else { echo $language = 'en'; };?>/" id="contact" accept-charset="utf-8" enctype="multipart/form-data">
					<?php } else { ?>
					<form method="post" action="<?php echo $baseurl;?>php/processor.php" id="contact" accept-charset="utf-8" enctype="multipart/form-data">
					<?php } ?>
						<?php if($messagetop == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="message-group">
									<div id="contact-message"></div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if($security == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="security-group">
									<?php echo $secure->generateHiddenInput();?>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if($merchantsupport == true || $merchantservice == true || $merchantpayment == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-name'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column six">
								<div class="input-group-right">
									<label for="firstname" class="group focus-group">
										<i class="icon-user3"></i>
										<input type="text" id="firstname" name="firstname" data-rule-required="<?php echo $firstnamefield;?>" data-rule-letterswithbasicpunc="<?php echo $firstnameerrorfield;?>" data-msg-required="<?php echo $lang['form-required-firstname'];?>" data-msg-letterswithbasicpunc="<?php echo $lang['form-error-firstname'];?>" autocomplete="off" maxlength="30" class="input-right marginb10" placeholder="<?php echo $lang['form-placeholder-firstname'];?>">
									</label>
								</div>
							</div>
							<div class="column six">
								<div class="input-group-right">
									<label for="lastname" class="group focus-group">
										<i class="icon-user3"></i>
										<input type="text" id="lastname" name="lastname" data-rule-required="<?php echo $lastnamefield;?>" data-rule-letterswithbasicpunc="<?php echo $lastnameerrorfield;?>" data-msg-required="<?php echo $lang['form-required-lastname'];?>" data-msg-letterswithbasicpunc="<?php echo $lang['form-error-lastname'];?>" autocomplete="off" maxlength="30" class="input-right marginb10" placeholder="<?php echo $lang['form-placeholder-lastname'];?>">
									</label>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-email'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="input-group-right">
									<label for="email" class="group focus-group">
										<i class="icon-envelop5"></i>
										<input type="email" id="email" name="email" data-rule-required="<?php echo $emailfield;?>" data-rule-email="<?php echo $emailerrorfield;?>" data-msg-required="<?php echo $lang['form-required-email'];?>" data-msg-email="<?php echo $lang['form-error-email'];?>" autocomplete="off" maxlength="70" class="input-right marginb10" placeholder="<?php echo $lang['form-placeholder-email'];?>">
									</label>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-subject'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="input-group-right">
									<label for="subject" class="group focus-group">
										<i class="icon-bubble-check"></i>
										<input type="text" id="subject" name="subject" data-rule-required="<?php echo $subjectfield;?>" data-msg-required="<?php echo $lang['form-required-subject'];?>" autocomplete="off" maxlength="50" class="input-right marginb10" placeholder="<?php echo $lang['form-placeholder-subject'];?>">
									</label>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-message'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="textarea-group-right">
									<label for="message" class="group focus-group">
										<i class="icon-file-text3"></i>
										<textarea rows="5" id="message" name="message" data-rule-required="<?php echo $messagefield;?>" data-msg-required="<?php echo $lang['form-required-message'];?>" maxlength="300" class="textarea-right resisable marginb10" placeholder="<?php echo $lang['form-placeholder-message'];?>"></textarea>
									</label>
								</div>	
							</div>
						</div>
						<?php } ?>
						<?php if($merchantsupport == true && $supportselect == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-select'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="select-group">
									<label for="support" class="group focus-group custom-select">
										<select id="support" name="support[]" data-rule-required="<?php echo $supportfield;?>" data-msg-required="<?php echo $lang['form-required-support'];?>" class="select marginb10">
											<option value=""><?php echo $lang['form-support-1'];?></option>
											<option value="<?php echo $lang['form-support-2'];?>"><?php echo $lang['form-support-2'];?></option>
											<option value="<?php echo $lang['form-support-3'];?>"><?php echo $lang['form-support-3'];?></option>
											<option value="<?php echo $lang['form-support-4'];?>"><?php echo $lang['form-support-4'];?></option>
											<option value="<?php echo $lang['form-support-5'];?>"><?php echo $lang['form-support-5'];?></option>
										</select>
									</label>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if($upload == true){ ?>
						<?php if($multipleupload == true || $dropboxmultipleupload == true || $amazonmultipleupload == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-multiple-upload'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="upload-group">
									<label for="uploadsingle1" class="group focus-group">
										<span class="upload-button"><?php echo $lang['form-placeholder-choose'];?></span>
										<input type="file" id="uploadsingle1" name="uploadsingle1" data-rule-required="<?php echo $upload1field;?>" data-msg-required="<?php echo $lang['form-required-upload1'];?>" data-rule-accept="<?php echo $uploadclienttypes;?>" data-msg-accept="<?php echo $lang['form-error-mimetype-upload1'];?>" data-rule-filesize="<?php echo $uploadclientsize;?>" data-msg-filesize="<?php echo $lang['form-error-filesize-upload1'];?>" autocomplete="off" class="select-upload marginb10">
										<i class="icon-file-upload2"></i>
										<input type="text" name="upload1" autocomplete="off" class="upload marginb10" placeholder="<?php echo $lang['form-placeholder-upload1'];?>">
									</label>
								</div>
							</div>
						</div>			
						<div class="grid-container">
							<div class="column twelve">
								<div class="upload-group">
									<label for="uploadsingle2" class="group focus-group">
										<span class="upload-button"><?php echo $lang['form-placeholder-choose'];?></span>
										<input type="file" id="uploadsingle2" name="uploadsingle2" data-rule-required="<?php echo $upload2field;?>" data-msg-required="<?php echo $lang['form-required-upload2'];?>" data-rule-accept="<?php echo $uploadclienttypes;?>" data-msg-accept="<?php echo $lang['form-error-mimetype-upload2'];?>" data-rule-filesize="<?php echo $uploadclientsize;?>" data-msg-filesize="<?php echo $lang['form-error-filesize-upload2'];?>" autocomplete="off" class="select-upload marginb10">
										<i class="icon-file-upload2"></i>
										<input type="text" name="upload2" autocomplete="off" class="upload margintb10" placeholder="<?php echo $lang['form-placeholder-upload2'];?>">
									</label>
								</div>
							</div>
						</div>	
						<div class="grid-container">
							<div class="column twelve">
								<div class="upload-group">
									<label for="uploadsingle3" class="group focus-group">
										<span class="upload-button"><?php echo $lang['form-placeholder-choose'];?></span>
										<input type="file" id="uploadsingle3" name="uploadsingle3" data-rule-required="<?php echo $upload3field;?>" data-msg-required="<?php echo $lang['form-required-upload3'];?>" data-rule-accept="<?php echo $uploadclienttypes;?>" data-msg-accept="<?php echo $lang['form-error-mimetype-upload3'];?>" data-rule-filesize="<?php echo $uploadclientsize;?>" data-msg-filesize="<?php echo $lang['form-error-filesize-upload3'];?>" autocomplete="off" class="select-upload marginb10">
										<i class="icon-file-upload2"></i>
										<input type="text" name="upload3" autocomplete="off" class="upload margintb10" placeholder="<?php echo $lang['form-placeholder-upload3'];?>">
									</label>
								</div>
							</div>
						</div>		
						<?php } elseif($singleupload == true || $dropboxsingleupload == true || $amazonsingleupload == true) { ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-upload'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="upload-group">
									<label for="uploadsingle1" class="group focus-group">
										<span class="upload-button"><?php echo $lang['form-placeholder-choose'];?></span>
										<input type="file" id="uploadsingle1" name="uploadsingle1" data-rule-required="<?php echo $upload1field;?>" data-msg-required="<?php echo $lang['form-required-upload1'];?>" data-rule-accept="<?php echo $uploadclienttypes;?>" data-msg-accept="<?php echo $lang['form-error-mimetype-upload1'];?>" data-rule-filesize="<?php echo $uploadclientsize;?>" data-msg-filesize="<?php echo $lang['form-error-filesize-upload1'];?>" autocomplete="off" class="select-upload marginb10">
										<i class="icon-file-upload2"></i>
										<input type="text" name="upload1" autocomplete="off" class="upload marginb10" placeholder="<?php echo $lang['form-placeholder-upload1'];?>">
									</label>
								</div>
							</div>
						</div>	
						<?php } elseif($multipleuploadfiles == true || $dropboxmultipleuploadfiles == true || $amazonmultipleuploadfiles == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-multiple-files'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="drop-upload-group">
									<label for="uploadmultiple1" class="group focus-group">
										<span class="drop-upload-button"><?php echo $lang['form-placeholder-drop-choose'];?></span>
										<input type="file" id="uploadmultiple1" name="uploadmultiple1[]" data-rule-required="<?php echo $uploadmultiplefield;?>" data-msg-required="<?php echo $lang['form-required-multiple-upload1'];?>" data-rule-accept="<?php echo $uploadclienttypes;?>" data-msg-accept="<?php echo $lang['form-error-mimetype-multiple-upload1'];?>" data-rule-filesize="<?php echo $uploadclientsize;?>" data-msg-filesize="<?php echo $lang['form-error-filesize-mutiple-upload1'];?>" data-rule-minupload="<?php echo $uploadclientminfiles;?>" data-msg-minupload="<?php echo $lang['form-error-minfiles-mutiple-upload1'];?>" data-rule-maxupload="<?php echo $uploadclientmaxfiles;?>" data-msg-maxupload="<?php echo $lang['form-error-maxfiles-mutiple-upload1'];?>" autocomplete="off" multiple="multiple" class="drop-select-upload marginb10">
										<i class="icon-file-upload2"></i>
										<input type="text" name="dropupload1" autocomplete="off" class="drop-upload marginb10" placeholder="<?php echo $lang['form-placeholder-multiple-upload1'];?>">
									</label>
								</div>
							</div>
						</div>	
						<?php } ?>
						<?php } ?>
						<?php if($merchantservice == true || $merchantpayment == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-services'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="box-services center marginb10">
									<?php if($merchantservice == true){ ?>
									<div class="box-header-services">
										<h4><?php echo $lang['form-service-header'];?></h4>
									</div>
									<?php } ?>
									<?php if($merchantpayment == true && $onetimepayment == true){ ?>
									<div class="box-header-services">
										<h4><?php echo $lang['form-service-header-payment'];?></h4>
									</div>
									<?php } ?>
									<?php if($merchantpayment == true && $recurringpayment == true){ ?>
									<div class="box-header-services">
										<h4><?php echo $lang['form-service-header-recurring'];?></h4>
									</div>
									<?php } ?>
									<div class="box-section-services">
										<i class="icon-basket"></i>
										<h4><?php echo $lang['form-service-description-1'];?></h4>
										<p><?php echo $lang['form-service-description-2'];?></p>
										<p><?php echo $lang['form-service-description-3'];?></p>
										<p><?php echo $lang['form-service-description-4'];?></p>
										<div class="totalservices"><?php echo $lang['form-service-description-5'];?></div>
									</div>
									<?php if($merchantservice == true){ ?>
									<div class="box-sub-footer-services">
										<p><?php echo $lang['form-description-service'];?></p>
									</div>
									<?php } ?>
									<?php if($merchantpayment == true && $onetimepayment == true){ ?>
									<div class="box-sub-footer-services">
										<p><?php echo $lang['form-description-payment'];?></p>
									</div>
									<?php } ?>
									<?php if($merchantpayment == true && $recurringpayment == true){ ?>
									<div class="box-sub-footer-services">
										<p><?php echo $lang['form-description-recurring'];?></p>
									</div>
									<?php } ?>
									<div class="box-footer-services group">
										<div id="carousel" class="owl-carousel">
											<div class="service-item item1">
												<div class="overlay">
													<div class="description">
														<a href="https://twitter.com/PSBM_MU"><i class="icon-twitter"></i></a>
														<p><?php echo $lang['form-service-1'];?></p>
														<?php if($merchantservice == true){ ?>
														<div class="checkbox-slick-group">
															<label for="service1" class="slick-hover-group">
																<input type="checkbox" name="service[]" data-rule-required="<?php echo $servicefield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-1'];?>" rel="<?php echo $serviceone;?>" id="service1">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-1'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $onetimepayment == true){ ?>
														<div class="checkbox-slick-group">
															<label for="payment1" class="slick-hover-group">
																<input type="checkbox" name="payment[]" data-rule-required="<?php echo $paymentfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-1'];?>" rel="<?php echo $paymentone;?>" id="payment1">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-1'];?>"></div>
															</label>
														</div>
														<?php } ?>
                                                        <?php if($merchantpayment == true && $recurringpayment == true){ ?>
                                                        <div class="radio-slick-group">
															<label for="recurring1" class="slick-hover-group">
																<input type="radio" name="recurring[]" data-rule-required="<?php echo $recurringfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="radio-slick" value="<?php echo $lang['form-value-service-1'];?>" rel="<?php echo $recurringone;?>" id="recurring1">
																<span class="slicklabel noblock" data-radio-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-1'];?>"></div>
															</label>
														</div>	
                                                        <?php } ?>														
													</div>
												</div>
											</div>
											<div class="service-item item2">
												<div class="overlay">
													<div class="description">
														<a href="https://twitter.com/PSBM_MU"><i class="icon-twitter"></i></a>
														<p><?php echo $lang['form-service-2'];?></p>
														<?php if($merchantservice == true){ ?>
														<div class="checkbox-slick-group">
															<label for="service2" class="slick-hover-group">
																<input type="checkbox" name="service[]" data-rule-required="<?php echo $servicefield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-2'];?>" rel="<?php echo $servicetwo;?>" id="service2">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-2'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $onetimepayment == true){ ?>
														<div class="checkbox-slick-group">
															<label for="payment2" class="slick-hover-group">
																<input type="checkbox" name="payment[]" data-rule-required="<?php echo $paymentfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-2'];?>" rel="<?php echo $paymenttwo;?>" id="payment2">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-2'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $recurringpayment == true){ ?>
														<div class="radio-slick-group">
															<label for="recurring2" class="slick-hover-group">
																<input type="radio" name="recurring[]" data-rule-required="<?php echo $recurringfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="radio-slick" value="<?php echo $lang['form-value-service-2'];?>" rel="<?php echo $recurringtwo;?>" id="recurring2">
																<span class="slicklabel noblock" data-radio-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-2'];?>"></div>
															</label>
														</div>	
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="service-item item3">
												<div class="overlay">
													<div class="description">
														<a href="https://twitter.com/PSBM_MU"><i class="icon-twitter"></i></a>
														<p><?php echo $lang['form-service-3'];?></p>
														<?php if($merchantservice == true){ ?>
														<div class="checkbox-slick-group">
															<label for="service3" class="slick-hover-group">
																<input type="checkbox" name="service[]" data-rule-required="<?php echo $servicefield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-3'];?>" rel="<?php echo $servicethree;?>" id="service3">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-3'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $onetimepayment == true){ ?>
														<div class="checkbox-slick-group">
															<label for="payment3" class="slick-hover-group">
																<input type="checkbox" name="payment[]" data-rule-required="<?php echo $paymentfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-3'];?>" rel="<?php echo $paymentthree;?>" id="payment3">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-3'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $recurringpayment == true){ ?>
														<div class="radio-slick-group">
															<label for="recurring3" class="slick-hover-group">
																<input type="radio" name="recurring[]" data-rule-required="<?php echo $recurringfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="radio-slick" value="<?php echo $lang['form-value-service-3'];?>" rel="<?php echo $recurringthree;?>" id="recurring3">
																<span class="slicklabel noblock" data-radio-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-3'];?>"></div>
															</label>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="service-item item4">
												<div class="overlay">
													<div class="description">
														<a href="https://twitter.com/PSBM_MU"><i class="icon-twitter"></i></a>
														<p><?php echo $lang['form-service-4'];?></p>
														<?php if($merchantservice == true){ ?>
														<div class="checkbox-slick-group">
															<label for="service4" class="slick-hover-group">
																<input type="checkbox" name="service[]" data-rule-required="<?php echo $servicefield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-4'];?>" rel="<?php echo $servicefour;?>" id="service4">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-4'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $onetimepayment == true){ ?>
														<div class="checkbox-slick-group">
															<label for="payment4" class="slick-hover-group">
																<input type="checkbox" name="payment[]" data-rule-required="<?php echo $paymentfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-4'];?>" rel="<?php echo $paymentfour;?>" id="payment4">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-4'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $recurringpayment == true){ ?>
														<div class="radio-slick-group">
															<label for="recurring4" class="slick-hover-group">
																<input type="radio" name="recurring[]" data-rule-required="<?php echo $recurringfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="radio-slick" value="<?php echo $lang['form-value-service-4'];?>" rel="<?php echo $recurringfour;?>" id="recurring4">
																<span class="slicklabel noblock" data-radio-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-4'];?>"></div>
															</label>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="service-item item5">
												<div class="overlay">
													<div class="description">
														<a href="https://twitter.com/PSBM_MU"><i class="icon-twitter"></i></a>
														<p><?php echo $lang['form-service-5'];?></p>
														<?php if($merchantservice == true){ ?>
														<div class="checkbox-slick-group">
															<label for="service5" class="slick-hover-group">
																<input type="checkbox" name="service[]" data-rule-required="<?php echo $servicefield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-5'];?>" rel="<?php echo $servicefive;?>" id="service5">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-5'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $onetimepayment == true){ ?>
														<div class="checkbox-slick-group">
															<label for="payment5" class="slick-hover-group">
																<input type="checkbox" name="payment[]" data-rule-required="<?php echo $paymentfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-5'];?>" rel="<?php echo $paymentfive;?>" id="payment5">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-5'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $recurringpayment == true){ ?>
														<div class="radio-slick-group">
															<label for="recurring5" class="slick-hover-group">
																<input type="radio" name="recurring[]" data-rule-required="<?php echo $recurringfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="radio-slick" value="<?php echo $lang['form-value-service-5'];?>" rel="<?php echo $recurringfive;?>" id="recurring5">
																<span class="slicklabel noblock" data-radio-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-5'];?>"></div>
															</label>
														</div>
                                                        <?php } ?>														
													</div>
												</div>
											</div>
											<div class="service-item item6">
												<div class="overlay">
													<div class="description">
														<a href="https://twitter.com/PSBM_MU"><i class="icon-twitter"></i></a>
														<p><?php echo $lang['form-service-6'];?></p>
														<?php if($merchantservice == true){ ?>
														<div class="checkbox-slick-group">
															<label for="service6" class="slick-hover-group">
																<input type="checkbox" name="service[]" data-rule-required="<?php echo $servicefield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-6'];?>" rel="<?php echo $servicesix;?>" id="service6">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-6'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $onetimepayment == true){ ?>
														<div class="checkbox-slick-group">
															<label for="payment6" class="slick-hover-group">
																<input type="checkbox" name="payment[]" data-rule-required="<?php echo $paymentfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-6'];?>" rel="<?php echo $paymentsix;?>" id="payment6">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-6'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $recurringpayment == true){ ?>
														<div class="radio-slick-group">
															<label for="recurring6" class="slick-hover-group">
																<input type="radio" name="recurring[]" data-rule-required="<?php echo $recurringfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="radio-slick" value="<?php echo $lang['form-value-service-6'];?>" rel="<?php echo $recurringsix;?>" id="recurring6">
																<span class="slicklabel noblock" data-radio-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-6'];?>"></div>
															</label>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="service-item item7">
												<div class="overlay">
													<div class="description">
														<a href="https://twitter.com/PSBM_MU"><i class="icon-twitter"></i></a>
														<p><?php echo $lang['form-service-7'];?></p>
														<?php if($merchantservice == true){ ?>
														<div class="checkbox-slick-group">
															<label for="service7" class="slick-hover-group">
																<input type="checkbox" name="service[]" data-rule-required="<?php echo $servicefield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-7'];?>" rel="<?php echo $serviceseven;?>" id="service7">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-7'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $onetimepayment == true){ ?>
														<div class="checkbox-slick-group">
															<label for="payment7" class="slick-hover-group">
																<input type="checkbox" name="payment[]" data-rule-required="<?php echo $paymentfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="checkbox-slick" value="<?php echo $lang['form-value-service-7'];?>" rel="<?php echo $paymentseven;?>" id="payment7">
																<span class="slicklabel noblock" data-checkbox-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-7'];?>"></div>
															</label>
														</div>
														<?php } ?>
														<?php if($merchantpayment == true && $recurringpayment == true){ ?>
														<div class="radio-slick-group">
															<label for="recurring7" class="slick-hover-group">
																<input type="radio" name="recurring[]" data-rule-required="<?php echo $recurringfield;?>" data-msg-required="<?php echo $lang['form-required-service'];?>" class="radio-slick" value="<?php echo $lang['form-value-service-7'];?>" rel="<?php echo $recurringseven;?>" id="recurring7">
																<span class="slicklabel noblock" data-radio-slick="<?php echo $lang['form-choose-service'];?>"></span>
																<div class="tooltip tooltip-info top-center fixed" data-tooltip="<?php echo $lang['form-service-tooltip-7'];?>"></div>
															</label>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if($merchantpayment == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-payment'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="box-payment marginb10">
									<div class="box-header-payment">
										<div class="column six nospacedes nospaceres">
											<h4><i class="icon-lock2"></i><?php echo $lang['form-payment-method-header'];?></h4>
										</div>
										<div class="column six nospacedes nospaceres">
											<div class="card-visa"></div>
											<div class="card-electron"></div>
											<div class="card-mastercard"></div>
											<div class="card-maestro"></div>
											<div class="card-amex"></div>
											<div class="card-paypal"></div>
											<div class="card-stripe"></div>
											<div class="card-braintree"></div>
										</div>
									</div>
									<div class="box-section-payment">
										<?php if($creditcard == true){ ?>
										<input type="radio" id="creditcard" name="method" checked="checked" value="<?php echo $lang['form-payment-method-1'];?>" class="input section-one">
										<label for="creditcard" class="tabslabel marginl20"><h4><?php echo $lang['form-payment-method-1'];?></h4></label>
										<?php } ?>
										<?php if($paypal == true){ ?>
										<input type="radio" id="paypal" name="method" value="<?php echo $lang['form-payment-method-2'];?>" class="input section-two">
										<label for="paypal" class="tabslabel"><h4><?php echo $lang['form-payment-method-2'];?></h4></label>
										<?php } ?>
										<?php if($stripe == true){ ?>
										<input type="radio" id="stripe" name="method" value="<?php echo $lang['form-payment-method-3'];?>" class="input section-three">
										<label for="stripe" class="tabslabel"><h4><?php echo $lang['form-payment-method-3'];?></h4></label>
										<?php } ?>
										<ul>
											<?php if($creditcard == true){ ?>
											<li class="section-one">
												<div class="column twelve nospacedes nospaceres">
													<div class="dividers">
														<div class="divider divider-nine"><p><?php echo $lang['form-label-card-name'];?></p></div>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="input-group-right">
														<label for="card-name" class="group focus-group">
															<i class="icon-credit-card2"></i>
															<input type="text" id="card-name" name="card-name" data-rule-required="<?php echo $cardnamefield;?>" data-rule-letterswithbasicpunc="<?php echo $cardnameerrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-name'];?>" data-msg-letterswithbasicpunc="<?php echo $lang['form-error-card-name'];?>" maxlength="100" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-name'];?>">
														</label>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="dividers">
														<div class="divider divider-nine"><p><?php echo $lang['form-label-card-number'];?></p></div>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="input-group-right">
														<label for="card-number" class="group focus-group">
															<i class="icon-credit-card2"></i>
															<input type="text" id="card-number" name="card-number" data-rule-required="<?php echo $cardnumberfield;?>" data-rule-creditcard="<?php echo $cardnumbererrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-number'];?>" data-msg-creditcard="<?php echo $lang['form-error-card-number'];?>" maxlength="23" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-number'];?>">
														</label>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="dividers">
														<div class="divider divider-nine"><p><?php echo $lang['form-label-date-cvv'];?></p></div>
													</div>
												</div>
												<div class="column six nospacedesl nospaceresl">
													<div class="input-group-right">
														<label for="card-date" class="group focus-group">
															<i class="icon-calendar"></i>
															<input type="text" id="card-date" name="card-date" data-rule-required="<?php echo $carddatefield;?>" data-rule-expiredate="<?php echo $carddateerrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-date'];?>" data-msg-expiredate="<?php echo $lang['form-error-card-date'];?>" maxlength="10" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-date'];?>">
														</label>
													</div>
												</div>
												<div class="column six nospace nospacedesr nospaceresr">
													<div class="input-group-right">
														<label for="card-cvv" class="group focus-group">
															<i class="icon-lock2"></i>
															<input type="text" id="card-cvv" name="card-cvv" data-rule-required="<?php echo $cardcvvfield;?>" data-rule-cvv="<?php echo $cardcvverrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-cvv'];?>" data-msg-cvv="<?php echo $lang['form-error-card-cvv'];?>" maxlength="4" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-cvv'];?>">
														</label>
													</div>
												</div>
											</li>
											<?php } ?>
											<?php if($paypal == true){ ?>
											<li class="section-two">
												<div class="column twelve nospacedes nospaceres">
													<div class="dividers">
														<div class="divider divider-nine"><p><?php echo $lang['form-label-paypal'];?></p></div>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div id="paypal-button"></div>
												</div>
											</li>
											<?php } ?>
											<?php if($stripe == true){ ?>
											<li class="section-three">
												<div class="column twelve nospacedes nospaceres">
													<div class="dividers">
														<div class="divider divider-nine"><p><?php echo $lang['form-label-card-name'];?></p></div>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="input-group-right">
														<label for="stripe-name" class="group focus-group">
															<i class="icon-credit-card2"></i>
															<input type="text" id="stripe-name" name="stripe-name" data-rule-required="<?php echo $cardnamefield;?>" data-rule-letterswithbasicpunc="<?php echo $cardnameerrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-name'];?>" data-msg-letterswithbasicpunc="<?php echo $lang['form-error-card-name'];?>" maxlength="100" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-name'];?>">
														</label>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="dividers">
														<div class="divider divider-nine"><p><?php echo $lang['form-label-card-number'];?></p></div>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="input-group-right">
														<label for="stripe-number" class="group focus-group">
															<i class="icon-credit-card2"></i>
															<input type="text" id="stripe-number" name="stripe-number" data-rule-required="<?php echo $cardnumberfield;?>" data-rule-cardnumber="<?php echo $cardnumbererrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-number'];?>" data-msg-cardnumber="<?php echo $lang['form-error-card-number'];?>" maxlength="23" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-number'];?>">
														</label>
													</div>
												</div>
												<div class="column twelve nospacedes nospaceres">
													<div class="dividers">
														<div class="divider divider-nine"><p><?php echo $lang['form-label-date-cvv'];?></p></div>
													</div>
												</div>
												<div class="column four nospacedesl nospaceresl">
													<div class="input-group-right">
														<label for="stripe-month" class="group focus-group">
															<i class="icon-calendar"></i>
															<input type="text" id="stripe-month" name="stripe-month" data-rule-required="<?php echo $cardmonthfield;?>" data-msg-required="<?php echo $lang['form-required-card-month'];?>" maxlength="2" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-month'];?>">
														</label>
													</div>
												</div>
												<div class="column four nospace">
													<div class="input-group-right">
														<label for="stripe-year" class="group focus-group">
															<i class="icon-calendar"></i>
															<input type="text" id="stripe-year" name="stripe-year" data-rule-required="<?php echo $cardyearfield;?>" data-rule-cardexpire="<?php echo $cardyearerrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-year'];?>" data-msg-cardexpire="<?php echo $lang['form-error-card-year'];?>" maxlength="4" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-year'];?>">
														</label>
													</div>
												</div>
												<div class="column four nospace nospacedesr nospaceresr">
													<div class="input-group-right">
														<label for="stripe-cvc" class="group focus-group">
															<i class="icon-lock2"></i>
															<input type="text" id="stripe-cvc" name="stripe-cvc" data-rule-required="<?php echo $cardcvvfield;?>" data-rule-cardcvc="<?php echo $cardcvverrorfield;?>" data-msg-required="<?php echo $lang['form-required-card-cvc'];?>" data-msg-cardcvc="<?php echo $lang['form-error-card-cvc'];?>" maxlength="4" class="input-right marginb10" autocomplete="off" placeholder="<?php echo $lang['form-placeholder-card-cvc'];?>">
														</label>
													</div>
												</div>
											</li>
											<?php } ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if($newsletter == true){ ?>
						<?php if($mailchimp == true || $campaignmonitor == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-newsletter'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="box marginb10">
									<div class="box-header">
										<h4><i class="icon-file-text3"></i><?php echo $lang['form-newsletter-header'];?></h4>
									</div>
									<div class="box-section">
										<div class="checkbox-group">
											<label for="newsletter" class="group hover-group">
												<input type="checkbox" name="newsletter" data-rule-required="<?php echo $newsletterfield;?>" data-msg-required="<?php echo $lang['form-required-newsletter'];?>" class="checkbox" value="<?php echo $lang['form-newsletter-option-1'];?>" id="newsletter">
												<span class="checkboxlabel nomargin" data-checkbox="<?php echo $lang['form-newsletter-label'];?>"></span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php } ?>
						<?php if($sendcopytome == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-sendtome'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve">
								<div class="box marginb10">
									<div class="box-header">
										<h4><i class="icon-file-text3"></i><?php echo $lang['form-send-to-me-header'];?></h4>
									</div>
									<div class="box-section">
										<div class="checkbox-group">
											<label for="sendtome" class="group hover-group">
												<input type="checkbox" name="sendtome" data-rule-required="<?php echo $sendtomefield;?>" data-msg-required="<?php echo $lang['form-required-send-to-me'];?>" class="checkbox" value="<?php echo $lang['form-send-to-me-option-1'];?>" id="sendtome">
												<span class="checkboxlabel nomargin" data-checkbox="<?php echo $lang['form-send-to-me-label'];?>"></span>
											</label>
										</div>											
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if($captcha == true){ ?>
						<?php if($alphacaptcha == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-captcha'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column six marginb10">
								<div class="captcha-group">
									<div class="dinamic-captcha marginb10">
										<div class="refreshalpha">
										    <img src="<?php echo $baseurl;?>php/functions/captcha/alphacaptcha/captcha.php?<?php echo time();?>" id="captchaalpha">
										</div>
									</div>
								</div>
							</div>
							<div class="column six marginb10">
								<div class="captcha-group">												
									<label for="captcha" class="group focus-group">
										<input type="text" id="alphacaptcha" name="alphacaptcha" data-rule-required="<?php echo $captchafield;?>" data-rule-remote="<?php echo $baseurl;?>php/functions/captcha/alphacaptcha/processor-captcha.php" data-msg-required="<?php echo $lang['form-required-alphacaptcha'];?>" data-msg-remote="<?php echo $lang['form-error-alphacaptcha'];?>" autocomplete="off" maxlength="6" class="captcha marginb10" placeholder="<?php echo $lang['form-placeholder-alphacaptcha'];?>">
									</label>
								</div>
							</div>
						</div>
						<?php } elseif($mathcaptcha == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-captcha'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column six marginb10">
								<div class="captcha-group">
									<div class="dinamic-captcha marginb10">
									    <div class="refreshmath">
										    <img src="<?php echo $baseurl;?>php/functions/captcha/mathcaptcha/captcha.php?<?php echo time();?>" id="captchamath">
										</div>
									</div>
								</div>
							</div>
							<div class="column six marginb10">
								<div class="captcha-group">												
									<label for="captcha" class="group focus-group">
										<input type="text" id="mathcaptcha" name="mathcaptcha" data-rule-required="<?php echo $captchafield;?>" data-rule-remote="<?php echo $baseurl;?>php/functions/captcha/mathcaptcha/processor-captcha.php" data-msg-required="<?php echo $lang['form-required-mathcaptcha'];?>" data-msg-remote="<?php echo $lang['form-error-mathcaptcha'];?>" autocomplete="off" maxlength="2" class="captcha marginb10" placeholder="<?php echo $lang['form-placeholder-mathcaptcha'];?>">
									</label>
								</div>
							</div>
						</div>
						<?php } else { ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="dividers">
									<div class="divider divider-nine"><p><?php echo $lang['form-label-captcha'];?></p></div>
								</div>
							</div>
						</div>
						<div class="grid-container">
							<div class="column twelve marginb10">
								<div class="captcha-group">
									<label for="g-recaptcha-response" class="group focus-group gcaptcha">								
										<input type="text" id="g-recaptcha-response" name="g-recaptcha-response" data-rule-required="<?php echo $captchafield;?>" data-rule-remote="<?php echo $baseurl;?>php/functions/captcha/recaptcha/processor-captcha.php" data-msg-required="<?php echo $lang['form-required-recaptcha'];?>" data-msg-remote="<?php echo $lang['form-error-recaptcha'];?>" autocomplete="off" class="captcha-hidden marginb10" placeholder="<?php echo $lang['form-placeholder-recaptcha'];?>">
										<div class="g-recaptcha" data-sitekey="<?php echo $sitekey;?>" data-theme="<?php echo $recaptchatheme;?>"></div>
									</label>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php } ?>
						<?php if($upload == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="progress-bar-container">
									<div class="progress-bar striped">
										<div class="bar"></div>
										<div class="percent">0%</div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if($messagebottom == true){ ?>
						<div class="grid-container">
							<div class="column twelve">
								<div class="message-group">
									<div id="contact-message"></div>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="grid-container">
							<div class="column twelve margint10">
								<div class="button-group">
									<button type="submit" id="contact-button" class="button button-large button-success marginb10"><?php echo $lang['form-button-submit'];?></button>
									<button type="reset" id="reset-button" class="button button-large button-error nomargin10"><?php echo $lang['form-button-reset'];?></button>
								</div>
							</div>
						</div>
					<?php if($multilanguage == true) { ?>
					</form>						
					<?php } else { ?>
					</form>						
					<?php } ?>
				</div>	
                <div class="footer">
				    <div class="grid-container">
						<div class="column twelve">
							<p><?php echo $lang['form-footer'];?></p>
							<p class="copyright"><i class="icon-lock2"></i><?php echo $lang['form-footer-copyright'];?></p>
					    </div>
					</div>
				</div>
            <?php if($themedefault == true){ ?>
			</div>
			<?php } ?>
			<?php if($themeflat == true){ ?>
			</div>
			<?php } ?>
			<?php if($thememinimal == true){ ?>
			</div>
			<?php } ?>
		</div>
		
		<script type="text/javascript">document.getElementById("wrapper").style.display = 'block';</script>
		
		<?php if($captcha == true) { ?>
		<?php if($alphacaptcha == true) { ?>
		<script type="text/javascript">
			$('.refreshalpha').click(function(e){
				e.preventDefault();
				// ADD HERE YOU ABSOLUTE URL TO ALPHA CAPTCHA
				$("#captchaalpha").attr('src','<?php echo $baseurl;?>php/functions/captcha/alphacaptcha/captcha.php?' + Math.random());
			});
		</script>
		<?php } ?>
		<?php if($mathcaptcha == true) { ?>
		<script type="text/javascript">
		$('.refreshmath').click(function(e){
			e.preventDefault();
			// ADD HERE YOU ABSOLUTE URL TO MATH CAPTCHA
			$("#captchamath").attr('src','<?php echo $baseurl;?>php/functions/captcha/mathcaptcha/captcha.php?' + Math.random());
		});
		</script>
		<?php } ?>
		<?php } ?>
		
		<?php if($merchantpayment == true) { ?>
		<?php if($onetimepayment == true || $recurringpayment == true) { ?>
		<script type="text/javascript" src="https://js.braintreegateway.com/v2/braintree.js"></script>
		<?php if($creditcard == true) { ?>
		<script type="text/javascript">
			braintree.setup("<?php echo $creditcardtoken;?>", "custom", {
				id:"contact"
			});   
		</script>
		<?php } ?>
		<?php if($paypal == true) { ?>
		<script type="text/javascript">
			braintree.setup("<?php echo $paypaltoken;?>", "paypal", {
				container:"paypal-button"
			});   
		</script>
		<?php } ?>
		<?php } ?>
		<?php } ?>
		
    </body>
</html>
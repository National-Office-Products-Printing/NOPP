<?php

/*	--------------------------------------------------
	:: FORM FRMEWORK PRO WITH AJAX LANGUAGE
	-------------------------------------------------- */

// Site Configuration
$lang['website_title']		                           = 'National Office Products & Printing, Inc.'; 
$lang['website_author']		                           = 'Jon Schuster'; 
$lang['website_description']		                     = 'National Office Products & Printing, Inc.'; 
$lang['website_keywords']		                         = 'Contact Form, form, email, phpmailer, smtp, autoresponder, token'; 

// Form
$lang['form_title']		                               = 'Have any Doubt ? Drop us a line'; 
$lang['form_placeholder_firstname']		               = 'Please enter your first name'; 
$lang['form_placeholder_lastname']		               = 'Please enter your last name'; 
$lang['form_placeholder_useremail']			             = 'How about an email address...';
$lang['form_placeholder_userphone']                  = '...or a number where we can reach you?';
$lang['form_placeholder_subject']			               = 'What does your question pertain to?'; 
$lang['form_placeholder_message']		                 = 'What would you like to talk to us about today?'; 
$lang['form_option_1_department']		                 = 'Who are you trying to reach?'; 
$lang['form_option_2_department']		                 = 'General Inquiries'; 
$lang['form_option_3_department']		                 = 'Business Equipment'; 
$lang['form_option_4_department']		                 = 'Graphics and Printing'; 
$lang['form_option_5_department']		                 = 'Office Products'; 
$lang['form_option_6_department']		                 = 'Webmaster (issues with this website)'; 
$lang['form_userfile_choose']		                     = 'Choose'; 
$lang['form_placeholder_userfile1']		               = 'Enter your first upload File'; 
$lang['form_placeholder_userfile2']		               = 'Enter your second upload File'; 
$lang['form_placeholder_userfile3']		               = 'Enter your third upload File'; 
$lang['form_placeholder_captcha']		                 = 'Please enter Verification Code';
$lang['form_button_submit']		                       = 'Send message'; 
$lang['form_button_reset']		                       = 'Reset Fields'; 
$lang['form_footer']		                             = 'Subscribe our Newsletter <a href="#">Click here</a>'; 

// Form processor
$lang['processor_wrong_security_token']		           = '<div class="error-message"><i class="icon-close"></i>Attention! Security token is not valid!</div>'; 
$lang['processor_according_department']		           = '<div class="error-message"><i class="icon-close"></i>Who are you trying to contact (please choose a department)?</div>'; 
$lang['processor_duplicate_email']	                 = '<div class="error-message"><i class="icon-close"></i>This email already exists in our database.</div>';
$lang['processor_subject']				                   = 'Message from '.$company.' contact form';   	
$lang['processor_automail_subject']	                 = 'We received your message from our website.';
$lang['processor_successful']				                 = '<div class="success-message"><i class="icon-checkmark"></i>Thanks! Your Message has been sent</div>';
$lang['processor_unsuccessful']				               = '<div class="error-message"><i class="icon-close"></i>Whoops! Looks like you sent us a message, but left no way for us to contact you. If you want to add a phone number or email address, please use the BACK button in your browser, and resubmit the form with your desired contact info.</div>';

// Message form
$lang['message_form_1']		                           = 'Hello, '.$name.'!'; 
$lang['message_form_2']		                           = 'A person named <strong>'.$_POST["firstname"].' '.$_POST["lastname"].'</strong> has contacted you, and here is what they had to say:'; 
$lang['message_form_4']		                           = '<hr><em>'.$_POST["usermessage"].'</em><hr>';
$lang['message_form_5']                              = '<strong>They can be reached at:</strong><br>-Email: <strong>'.$_POST["useremail"].'</strong><br>-Phone: <strong>'.$_POST["userphone"].'</strong>';
$lang['message_form_6']		                           = 'Just in case this may have gone to the wrong person, they are trying to reach: <strong>'.$_POST["department"].'</strong>.'; 
$lang['message_form_7']		                           = 'Although you most likely will not need it, their IP address is: <strong>'.$_SERVER['REMOTE_ADDR'].'</strong>'; 
$lang['message_form_8']                              = 'This email was sent on: '.$localtime.'';

// Message form
$lang['message_form_9']		                           = 'Hello, '.$yourname.'!'; 
$lang['message_form_10']		                         = 'A person named <strong>'.$_POST["firstname"].' '.$_POST["lastname"].'</strong> has contacted you, and here is what they had to say:'; 
$lang['message_form_11']		                         = 'Their email is : '.$_POST["useremail"].' and phone number';
$lang['message_form_13']		                         = 'Their Message is : '.$_POST["usermessage"].''; 
$lang['message_form_14']		                         = 'Their Department is : '.$_POST["department"].''; 
$lang['message_form_15']		                         = 'Although you most likely will not need it, their IP address is: <strong>'.$_SERVER['REMOTE_ADDR'].'</strong>'; 
$lang['message_form_16']                             = 'This email was sent on : '.$localtime.'';

// Automessage form
$lang['automessage_form_1']		                       = 'Hello, '.$_POST["firstname"].' '.$_POST["lastname"].'!'; 
$lang['automessage_form_2']		                       = 'We received your message from our website, and will respond to it as soon as possible.';
$lang['automessage_form_4']                          = 'If you need immediate support, please call us at <strong>1 (800) 562-1042</strong>.';	 

?>
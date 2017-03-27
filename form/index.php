<?php

  error_reporting(0);

  include dirname(__FILE__).'/php/functions/sessions/sessions.php';

  // SESSION START!
  secure_session($sessionname);

  // INCLUDE ALL NECESSARY FILES!
	include dirname(__FILE__).'/php/libraries/security/security/security.php';
	include dirname(__FILE__).'/php/settings/settings.php';
	include dirname(__FILE__).'/php/functions/language/language.php';

	// CALL SECURITY CLASS INSTANCE!
	$secure = new Security($secret);

	// ADD HERE YOUR CALL FOR MULTILANGUAGE!
	include dirname(__FILE__).'/php/languages/en.php';

?>

<!--
* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*                                                       *
*   National Office Products & Printing, Inc. v0.3.1    *
*   Copyright 2014, Jon Schuster                        *
*   jrs86.com                                           *
*                                                       *
* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
-->
<!DOCTYPE html>
<html>
    <head>

    <meta charset="utf-8">
	  <meta name="author" content="<?php echo $lang['form-website-author'];?>">
		<meta name="keywords" content="<?php echo $lang['form-website-keywords'];?>">
    <title>Contact | National Office Products &amp; Printing, Inc.</title>
    <meta name="description=" content="Contact National Office Products &amp; Printing, Inc.">

		<!-- Viewport -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<!-- Favicon -->
    <link rel="icon" type="image/ico" href="../assets/img/favicon.ico" />

		<!-- Css Styles -->
    <link rel="stylesheet" href="../assets/css/nopp.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/structure.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/themes/default/settings.css">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,700italic,400italic|Oswald:400,300,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

		<!-- Jquery Library -->
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

		<!-- Js Files -->
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-customize.js"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-validate.js"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-methods.js"></script>
    <script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-form.js"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>js/jquery-placeholder.js"></script>
		<script type="text/javascript" src='https://www.google.com/recaptcha/api.js?hl=en'></script>

    <style>
      .form-hidden {
  			position:absolute;
  			visibility:hidden;
  			margin:0;
  			padding:0;
  		}
    </style>
    </head>

    <body data-spy="scroll">

		<noscript id="noscript">
			<?php echo $lang['form-noscript-message'];?>
		</noscript>

    <header id="main-nav">
      <div class="main-nav-wrapper">
        <div class="frame">
          <div class="branding">
            <a href="../" class="logo"><img src="../assets/img/logo-main.svg" onerror="this.onerror=null; this.src='../assets/img/logo-tablet.png'" alt="National Office Products &amp; Printing, Inc."><span class="phone-number">1 (800) 562-1042</span></a>
          </div>
          <a href="#" class="navToggle">menu</a>
          <nav class="nav-panel">
            <ul>
              <li><a href="../">home</a></li>
              <li><a href="../services">services</a></li>
              <li><a href="../about">about us</a></li>
              <li><a href="../contact">contact</a></li>
            </ul>
          </nav>
        </div>
      </div>
      <nav class="mob-nav-panel">
        <ul>
          <li><a href="../">home</a></li>
          <li><a href="../services">services</a></li>
          <li><a href="../about">about us</a></li>
          <li><a href="../contact">contact</a></li>
        </ul>
      </nav>
    </header>

    <section class="blue-box titlebar">
      <div class="frame">
        <div class="bit-1 text-center">
          <h1>Need to get ahold of us?</h1>
        </div>
      </div>
    </section>

    <section id="map" class="blue-box hide">
      <div class="frame-full">
        <div class="shadow">
          <div class="bit-1" style="padding: 0;">
            <iframe class="hide" width='100%' height='500px' frameBorder='0' src='https://a.tiles.mapbox.com/v3/shoostar.gojok598/mm/zoompan.html?secure=1#6/45.252/-84.287'></iframe>
          </div>
        </div>
      </div>
    </section>

    <section id="nopp-colors">
      <div class="frame">
        <div class="column-main">
					<form method="post" action="<?php echo $baseurl;?>php/processor.php" id="contact" accept-charset="utf-8" enctype="multipart/form-data">
						<div id="contact-message"></div>
						<?php echo $secure->generateHiddenInput();?>

						<label for="firstname">
              <i class="fa fa-user color-3">&nbsp;</i>
              <strong><?php echo $lang['form-label-firstname'];?></strong>
							<input type="text" id="firstname" name="firstname" data-rule-required="<?php echo $firstnamefield;?>" data-rule-letterswithbasicpunc="<?php echo $firstnameerrorfield;?>" data-msg-required="<?php echo $lang['form-required-firstname'];?>" data-msg-letterswithbasicpunc="<?php echo $lang['form-error-firstname'];?>" autocomplete="off" maxlength="30" placeholder="<?php echo $lang['form-placeholder-firstname'];?>">
						</label>

						<label for="lastname">
              <i class="fa fa-user color-3">&nbsp;</i>
              <strong><?php echo $lang['form-label-lastname'];?></strong>
							<input type="text" id="lastname" name="lastname" data-rule-required="<?php echo $lastnamefield;?>" data-rule-letterswithbasicpunc="<?php echo $lastnameerrorfield;?>" data-msg-required="<?php echo $lang['form-required-lastname'];?>" data-msg-letterswithbasicpunc="<?php echo $lang['form-error-lastname'];?>" autocomplete="off" maxlength="30" placeholder="<?php echo $lang['form-placeholder-lastname'];?>">
						</label>

						<label for="email">
              <i class="fa fa-envelope-o color-3">&nbsp;</i>
              <strong><?php echo $lang['form-label-email'];?></strong>
							<input type="email" id="email" name="email" data-rule-required="<?php echo $emailfield;?>" data-rule-email="<?php echo $emailerrorfield;?>" data-msg-required="<?php echo $lang['form-required-email'];?>" data-msg-email="<?php echo $lang['form-error-email'];?>" autocomplete="off" maxlength="70" placeholder="<?php echo $lang['form-placeholder-email'];?>">
						</label>

						<label for="support">
              <i class="fa fa-group color-3">&nbsp;</i>
              <strong><?php echo $lang['form-label-select'];?></strong>
							<select id="support" name="support[]" data-rule-required="<?php echo $supportfield;?>" data-msg-required="<?php echo $lang['form-required-support'];?>">
								<option value=""><?php echo $lang['form-support-1'];?></option>
								<option value="<?php echo $lang['form-support-2'];?>"><?php echo $lang['form-support-2'];?></option>
								<option value="<?php echo $lang['form-support-3'];?>"><?php echo $lang['form-support-3'];?></option>
								<option value="<?php echo $lang['form-support-4'];?>"><?php echo $lang['form-support-4'];?></option>
								<option value="<?php echo $lang['form-support-5'];?>"><?php echo $lang['form-support-5'];?></option>
							</select>
						</label>

            <label for="message">
              <i class="fa fa-pencil color-3">&nbsp;</i>
              <strong><?php echo $lang['form-label-message'];?></strong>
							<textarea rows="10" id="message" name="message" data-rule-required="<?php echo $messagefield;?>" data-msg-required="<?php echo $lang['form-required-message'];?>" maxlength="1000" placeholder="<?php echo $lang['form-placeholder-message'];?>"></textarea>
						</label>

						<label for="uploadmultiple1">
              <i class="fa fa-camera-retro color-3">&nbsp;</i>
              <strong><?php echo $lang['form-placeholder-drop-choose'];?></strong>
							<span class="drop-upload-button"><?php echo $lang['form-placeholder-drop-choose'];?></span>
							<input type="file" id="uploadmultiple1" name="uploadmultiple1[]" data-rule-required="<?php echo $uploadmultiplefield;?>" data-msg-required="<?php echo $lang['form-required-multiple-upload1'];?>" data-rule-accept="<?php echo $uploadclienttypes;?>" data-msg-accept="<?php echo $lang['form-error-mimetype-multiple-upload1'];?>" data-rule-filesize="<?php echo $uploadclientsize;?>" data-msg-filesize="<?php echo $lang['form-error-filesize-mutiple-upload1'];?>" data-rule-minupload="<?php echo $uploadclientminfiles;?>" data-msg-minupload="<?php echo $lang['form-error-minfiles-mutiple-upload1'];?>" data-rule-maxupload="<?php echo $uploadclientmaxfiles;?>" data-msg-maxupload="<?php echo $lang['form-error-maxfiles-mutiple-upload1'];?>" autocomplete="off" multiple="multiple" class="drop-select-upload">
							<input type="text" name="dropupload1" autocomplete="off" class="drop-upload form-hidden" placeholder="<?php echo $lang['form-placeholder-multiple-upload1'];?>">
              <p><small><strong>Please edit your filenames to include yourname!</strong></small></p>
            </label>

            <!-- <div class="progress-bar-container">
              <div class="progress-bar striped">
                <div class="bar"></div>
                <div class="percent">0%</div>
              </div>
            </div> -->

            <label for="g-recaptcha-response" class="group focus-group gcaptcha">
              <input type="text" id="g-recaptcha-response" name="g-recaptcha-response" data-rule-required="<?php echo $captchafield;?>" data-rule-remote="<?php echo $baseurl;?>php/functions/captcha/recaptcha/processor-captcha.php" data-msg-required="<?php echo $lang['form-required-recaptcha'];?>" data-msg-remote="<?php echo $lang['form-error-recaptcha'];?>" autocomplete="off" class="form-hidden" placeholder="<?php echo $lang['form-placeholder-recaptcha'];?>">
              <div class="g-recaptcha" data-sitekey="<?php echo $sitekey;?>" data-theme="<?php echo $recaptchatheme;?>"></div>
            </label>

            <button type="submit" id="contact-button" class="button-1 button-success"><?php echo $lang['form-button-submit'];?></button>
            <button type="reset" id="reset-button" class="button-1 button-error"><?php echo $lang['form-button-reset'];?></button>
          </form>
        </div>

        <div class="column-sidebar">
          <div class="frame no-pad">
            <div class="bit-1">
              <h4 class="heading">Sault Ste. Marie, MI</h4>
              <div itemprop="department" itemscope itemtype="http://schema.org/Store">
                <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                  <span itemprop="streetAddress">405 Ashmun St.</span> &bull;
                  <span itemprop="postOfficeBoxNumber"> PO Box 610</span><br>
                  <span itemprop="addressLocality">Sault Ste. Marie</span>,
                  <span itemprop="addressRegion">MI</span>
                  <span itemprop="postalCode">49783</span>
                </div><br>
                <i class="fa fa-phone color-3">&nbsp;</i> <strong>Toll-Free: </strong><span itemprop="telephone" content="+18005621042">800-562-1042</span><br>
                <i class="fa fa-phone color-3">&nbsp;</i> <strong>Phone: </strong><span itemprop="telephone" content="+19066323095">906-632-3095</span><br>
                <i class="fa fa-print color-3">&nbsp;</i> <strong>Fax: </strong><span itemprop="faxNumber" content="+19066326836">906-632-6836</span><br>
                <i class="fa fa-clock-o color-3">&nbsp;</i> <strong>Hours: </strong>8am&ndash;5pm, M&ndash;F
              </div>
            </div>
            <div class="bit-1">
              <hr>
              <h4 class="heading">Cheboygan, MI</h4>
              <div itemprop="department" itemscope itemtype="http://schema.org/Store">
                <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                  <span itemprop="streetAddress">514&#160;N. Main St.</span><br>
                  <span itemprop="addressLocality">Cheboygan</span>,
                  <span itemprop="addressRegion">MI</span>
                  <span itemprop="postalCode">49721</span>
                </div><br>
                <i class="fa fa-phone color-3">&nbsp;</i> <strong>Toll-Free: </strong><span itemprop="telephone" content="+18005809723">800-580-9723</span><br>
                <i class="fa fa-phone color-3">&nbsp;</i> <strong>Phone: </strong><span itemprop="telephone" content="+12316273193">231-627-3193</span><br>
                <i class="fa fa-print color-3">&nbsp;</i> <strong>Fax: </strong><span itemprop="faxNumber" content="+12316277075">231-627-7075</span><br>
                <i class="fa fa-clock-o color-3">&nbsp;</i> <strong>Hours: </strong>8am&ndash;5pm, M&ndash;F
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer>
    <section class="white-box">
      <div class="frame">
        <div class="bit-1">
          <h2><span class="hide">CALL US AT: </span>1 (800) 562-1042</h2>
          <em>Be sure to ask for your <strong>FREE</strong> full-color catalog!</em>
        </div>
      </div>
    </section>
    <div class="frame footer-links">
      <div class="bit-2 text-left">
        <ul>
          <li><strong>Sault Ste. Marie: </strong>405 Ashmun St. &bull; Sault Ste. Marie, MI 49783</li>
            <li><strong>Cheboygan: </strong>514 N. Main St. &bull; Cheboygan, MI 49721</li>
          <li><strong>Phone: </strong>1 (800) 562-1042</li>
          <li><strong>Email: </strong>info@nopp.com</li>
          <li><strong>Hours: </strong>8:00am&ndash;5:00pm, Monday&ndash;Friday</li>
        </ul>
      </div>
      <div class="bit-2 no-pad">
        <div class="frame-full no-pad">
          <div class="bit-2 half text-right">
            <h6>SERVICES</h6>
            <ul>
              <li><a href="../services/#buseqpt">Business Equipment</a></li>
              <li><a href="../services/#gfxdes">Graphic Design</a></li>
              <li><a href="../services/#offprd">Office Products</a></li>
              <li><a href="../services/#sysfrn">Systems Furniture</a></li>
            </ul>
          </div>
          <div class="bit-2 half text-right">
            <h6>OUR COMPANY</h6>
            <ul>
              <li><a href="../about">About Us</a></li>
              <li><a href="../contact">Contact</a></li>
              <li><a href="../privacy-policy">Privacy Policy</a></li>
              <li><a href="../tos">Terms of Service</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="frame">
      <div class="bit-1 no-pad">
        <hr>
      </div>
      <div class="bit-2">
        <p>Copyright &copy; 2016, National Office Products &amp; Printing, Inc.</p>
      </div>
      <div class="bit-2 text-right hide">
        <img style="margin-right: 2em;" src="../assets/img/support/hp-supplies-medallion.gif" height="35" alt="Hewlett-Packard Qualified Supplies Partner" />
        <a href="http://www.konicaminolta.us" target="_blank" style="margin-right: 2em;"><img src="../assets/img/support/kon.png" height="35" alt="Konica Minolta Authorized Dealer &amp; ProTech&reg; Service Center" /></a>
        <a href="mailto:graphics@nopp.com"><img src="../assets/img/support/jon-schuster.svg" height="20" style="margin-bottom: 8px;" alt="Website designed &amp; developed by Jon Schuster" /></a>
      </div>
    </div>
  </footer>

  <!-- Let's try to keep all the JS files down here, okay? Thanks! -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.min.js"></script>
  <script src="../assets/js/plugins.js"></script>
  <script src="../assets/js/nopp.js"></script>
  <script src="../assets/js/scripts.min.js"></script>
  <script>
    !function(g,s,q,r,d){r=g[r]=g[r]||function(){(r.q=r.q||[]).push(
    arguments)};d=s.createElement(q);q=s.getElementsByTagName(q)[0];
    d.src='//d1l6p2sc9645hc.cloudfront.net/tracker.js';q.parentNode.
    insertBefore(d,q)}(window,document,'script','_gs');
    _gs('GSN-465059-S');
  </script>

</body>
</html>

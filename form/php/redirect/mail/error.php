<?php

    error_reporting(0);

	include '../../functions/sessions/sessions.php';
 
    // SESSION START!
    secure_session($sessionname);
		
	// INCLUDE ALL NECESSARY FILES!
	include '../../settings/settings.php';
	include '../../functions/language/language.php';
	
	// ADD HERE YOUR CALL FOR MULTILANGUAGE!
	if($multilanguage == true) {
		if (isset($arraylanguage[1])){
	        include '../../languages/'.$arraylanguage[1].'.php';
		} else {
			include '../../languages/en.php';
		}
	} else {
		include '../../languages/'.$language.'.php';
	}
	
?> 
<!doctype html>
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

    </head>
	
    <body>
        
		<?php if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) { ?>
		
        <div class="container container-fixed">
		    <?php if($themeflat == true) { ?>
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
		    <div class="form form-default form-blue-default form-light-default">
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
				<div class="section-message">
				    <div class="grid-container">
					    <div class="column twelve">
					        <?php echo $lang['form-mail-redirect'];?>
					    </div>	
                    </div>
				</div>
                <div class="footer">
				    <div class="grid-container">
						<div class="column twelve">
							<p><?php echo $lang['form-footer'];?></p>
							<p class="copyright"><i class="icon-lock2"></i><?php echo $lang['form-footer-copyright'];?></p>
					    </div>
					</div>
				</div>
            </div>
		</div>
		
		<?php } else { ?>
		<?php echo $lang['form-directaccess-message'];?>
		<?php } ?>
		
    </body>
</html>
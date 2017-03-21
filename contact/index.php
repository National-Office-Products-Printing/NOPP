<?php

  include dirname(__FILE__).'/php/csrf_protection/csrf-token.php';
  include dirname(__FILE__).'/php/csrf_protection/csrf-class.php';

  include dirname(__FILE__).'/php/config/config.php';

  $language = array('en' => 'en','pt' => 'pt');

  if (isset($_GET['lang']) AND array_key_exists($_GET['lang'], $language)){
    include dirname(__FILE__).'/php/language/'.$language[$_GET['lang']].'.php';
  } else {
    include dirname(__FILE__).'/php/language/en.php';
  }

?>
<!--
* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*                                                       *
*   National Office Products & Printing, Inc. v0.3.1    *
*   Copyright 2014, Jon Schuster                        *
*   farfromrobot.com                                    *
*                                                       *
* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
-->
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact | National Office Products &amp; Printing, Inc.</title>
  <meta name="description=" content="Contact National Office Products &amp; Printing, Inc.">
  <link rel="stylesheet" href="../assets/css/nopp.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>css/tooltipster.css">
  <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>-->
  <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,700italic,400italic|Oswald:400,300,700' rel='stylesheet' type='text/css'>
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png" />

    <!-- Form JS files -->
    <script src="<?php echo $baseurl;?>js/jquery.custom.js"></script>
    <script src="<?php echo $baseurl;?>js/jquery.validate.js"></script>
    <script src="<?php echo $baseurl;?>js/jquery.methods.js"></script>
    <script src="<?php echo $baseurl;?>js/jquery.form.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="<?php echo $baseurl;?>js/localization/messages_en.js"></script>
    <script src="<?php echo $baseurl;?>js/jquery.tooltipster.js"></script>
</head>
<body data-spy="scroll">
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
                <div class="bit-1" style="padding: 0; margin-bottom: -6px;">
                  <iframe class="hide" width='100%' height='500px' frameBorder='0' src='https://a.tiles.mapbox.com/v3/shoostar.gojok598/mm/zoompan.html?secure=1#6/45.252/-84.287'></iframe>
                </div>
              </div>
            </div>
          </section>
          <section id="nopp-colors">
            <div class="frame">
              <div class="column-main">
                <h3 class="color-1">New features on their way!</h3>
                <p>In a constant effort to help make working with us a bit easier, we're hard at work adding new features to this page. Soon, you'll have the ability to contact any of our departments directly, as well as the option to upload your printable files and artwork to us.</p>
                <p>Still need to get ahold of us right away? Any of the info provided on this page can be used to reach us.</p>
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
          <a href="http://www.hp.com" target="_blank" style="margin-right: 2em;"><img src="../assets/img/support/hp-supplies-medallion.gif" height="35" alt="Hewlett-Packard Qualified Supplies Partner" /></a>
          <a href="http://www.hp.com" target="_blank" style="margin-right: 2em;"><img src="../assets/img/support/kon.png" height="35" alt="Konica Minolta Authorized Dealer &amp; ProTech&reg; Service Center" /></a>
          <a href="mailto:graphics@nopp.com"><img src="../assets/img/support/jon-schuster.svg" height="20" style="margin-bottom: 8px;" alt="Website designed &amp; developed by Jon Schuster" /></a>
        </div>
      </div>
    </footer>

    <!-- Let's try to keep all the JS files down here, okay? Thanks! -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.min.js"></script>
    <script src="../assets/js/plugins.js"></script>
    <script src="../assets/js/nopp.js"></script>
    <script src="../assets/js/scripts.min.js"></script>
    <script src="../assets/js/idangerous.swiper-2.1.min.js"></script>
    <script>
      !function(g,s,q,r,d){r=g[r]=g[r]||function(){(r.q=r.q||[]).push(
      arguments)};d=s.createElement(q);q=s.getElementsByTagName(q)[0];
      d.src='//d1l6p2sc9645hc.cloudfront.net/tracker.js';q.parentNode.
      insertBefore(d,q)}(window,document,'script','_gs');
      _gs('GSN-465059-S');
    </script>
        <script type="text/javascript">
      $(function(){
        var mySwiper = $('.swiper-container').swiper({
          mode: 'horizontal',
          loop: true,
          autoplay: 5000,
          autoplayDisableOnInteraction: false,
          calculateHeight: true,
          cssWidthAndHeight: true
        });
        $('.arrow-left').on('click', function(e){
          mySwiper.swipePrev()
        })
        $('.arrow-right').on('click', function(e){
          mySwiper.swipeNext()
        })
      })
    </script>



  </body>
</html>

// NOPP tracking with Google
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-59951929-1', 'auto');
ga('send', 'pageview');



// Simple slider
$(document).ready(function(){
  $('.slider').slick({
    draggable: true,
    arrows: true,
    dots: true,
    fade: false,
    speed: 900,
    infinite: true,
    autoplay: true,
    slidesPerView: 1,
    touchThreshold: 100
  });
});

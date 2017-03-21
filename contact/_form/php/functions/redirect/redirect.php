<?php 

	function redirect($url){
	    echo '<script type="text/javascript">window.location.href = "'.$url.'";</script>';
    }

    function redirecttime($url){
	    echo '<script type="text/javascript">window.setTimeout(function(){window.location.href = "'.$url.'";}, 5000)</script>';
    }

?>
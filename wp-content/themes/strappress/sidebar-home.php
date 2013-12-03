<?php
/**
 * Home Widgets Template
 *
 *
 * @file           sidebar-home.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Theme_Development#Widgets_.28sidebar.php.29
 * @since          available since Release 1.0
 */
$dir = get_template_directory_uri();
?>
<div id="widgets" class="home-widgets">
	<div class="row-fluid">
		<div id="owl-demo">
		    <div class="item"><img src=<?php echo $dir."/images/css3.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/modern.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/css3.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/modern.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/css3.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/modern.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/css3.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/modern.png" ?> alt="Owl Image"></div>
	    </div>
	</div>
	<div class="row-fluid">
		<div class="span6 offset3 picto_home home2">
		    <iframe width="100%" height="400" src="//www.youtube.com/embed/Mxl6FjvWS3A" frameborder="0" allowfullscreen></iframe>
		</div><!-- end of .span4 -->
	</div>
</div><!-- end of #widgets -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src=<?php echo $dir."/js/owl.js" ?>></script>
<script type="text/javascript" language="javascript">
	$(function() {
		 $("#owl-demo").owlCarousel({
 
		autoPlay: 3000, //Set AutoPlay to 3 seconds
		
		items : 4,
		itemsDesktop : [1170,4],
		itemsDesktopSmall : [979,3],
		navigation : true,
		navigationText : ["prev","next"]
		 
		});
	});
</script>
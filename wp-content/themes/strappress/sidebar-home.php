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
		<div class="span6 offset3 picto_home home2">
		    <iframe width="100%" height="400" src="//www.youtube.com/embed/Mxl6FjvWS3A" frameborder="0" allowfullscreen></iframe>
		</div>
	</div>
	<div class="row-fluid">
		<div id="owl-demo">
		    <div class="item"><img src=<?php echo $dir."/images/medias/1.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/2.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/3.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/4.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/5.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/6.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/7.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/8.png" ?> alt="Owl Image"></div>
		    <div class="item"><img src=<?php echo $dir."/images/medias/9.png" ?> alt="Owl Image"></div>
	    </div>
	</div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src=<?php echo $dir."/js/owl.js" ?>></script>
<script type="text/javascript" language="javascript">
	$(function() {
		 $("#owl-demo").owlCarousel({
			autoPlay: 5000,
			items : 5,
			itemsDesktop : [1170,4],
			itemsDesktopSmall : [979,3],
			navigation : false,
			navigationText : ["prev","next"],
			itemsScaleUp:false
		});
	});
</script>
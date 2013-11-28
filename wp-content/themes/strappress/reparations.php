<?php
/*
Template Name: reparations
*/
?>

<?php
/**
 * Pages Template
 *
 *
 * @file           page.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */
?>
<?php get_header(); 
$dir = get_template_directory_uri(); ?>
<div class="row-fluid">
	<div id="accordion-container">
		<h2 class="accordion-header">Choisissez la marque de votre téléphone ou de votre tablette</h2>
		<div class="accordion-content">          	
		<?php
			$list_marque = $wpdb->get_results("SELECT * FROM wp_phone_company");
		?>
			<div class="phone_company">
				<ul>
				<?php
					foreach ($list_marque as $marque) {
						echo '
						<li>
							<div class="company_logo">
								<a class="anil" href="../models/'.$marque->pc_name.'">
									<img src="'.$dir.'/'.$marque->pc_image.'"/>
								</a>
							</div>
						</li> ';
					}
				?>
				</ul>           
			</div>
			<div style="clear:both;"></div>
    </div>
		<h2 class="accordion-header">Choisissez le modèle de votre smartphone ou tablette</h2>
		<h2 class="accordion-header">Choisissez le type de réparation</h2>
		<h2 class="accordion-header">Nous faire parvenir votre appareil</h2>      
  </div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	//Add Inactive Class To All Accordion Headers
	$('.accordion-header').addClass('inactive-header');
	//Open The First Accordion Section When Page Loads
	$('.accordion-header').first().addClass('active-header').removeClass('inactive-header');
	$('.accordion-content').first().slideDown().addClass('open-content');
});  
</script>
<?php get_footer(); ?>
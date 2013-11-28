<?php
/*
Template Name: models
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
	<div class="sixteen colums">
		<div id="accordion-container">
			<h2 class="accordion-header"><a href="../../tarifs/">Choisissez la marque de votre téléphone ou de votre tablette</a></h2>
			<h2 class="accordion-header second-header">Choisissez le modèle de votre smartphone ou tablette</h2>
			<div class="accordion-content second-content">
				<?php
					$pc_name =  $wp_query->query_vars['pc_name'];
					$_SESSION['pc_name'] = $pc_name;
					
					$sql1 = $wpdb->get_row("SELECT pc_image FROM wp_phone_company WHERE pc_name =  '".$pc_name."'");
					
					$models = $wpdb->get_results("SELECT * from  wp_phone_model LEFT JOIN wp_phone_company ON wp_phone_model.pc_id = wp_phone_company.pc_id WHERE pc_name = '".$pc_name."'");
				?>
				<div class="brand_image_container">
					<div class="brand_image">
					<?php 
						echo '<img src="'.$dir.'/'.$sql1->pc_image.'" />';
					?>
					</div>
				</div>
				<div class="brand_phone_model_container">
					<div class="brand_phone_model">
						<div class="phone_model">
							<ul>
								<?php
									/*if(mysql_num_rows($sql1)>0)
									{*/
									foreach ($models as $model)
									{
										echo '<li>
										<div class="model_image">
											<a href="../../services/'.$model->model_name.'"><img src="'.$dir.'/'.$model->model_image.'"/></a>
										</div>
										</li> ';
									}
									/*}
									else
									{
										echo "<h3>There are no phone models to list in this category.</h3>";
									}*/
								?>
							</ul>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
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
		$('.second-header').addClass('active-header').removeClass('inactive-header');
		$('.second-content').slideDown().addClass('open-content');
		});  
	</script>
</div><!-- sixteen colum end -->

<?php get_footer(); ?>
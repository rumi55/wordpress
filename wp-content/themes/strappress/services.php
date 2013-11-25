<?php
/*
Template Name: services
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
		<h2 class="accordion-header"><a href="../tarifs/">Choisissez la marque de votre téléphone ou de votre tablette</a></h2>
		<h2 class="accordion-header second-header"><a href="../models/?pc_id=<?php echo $_SESSION['pc_id']; ?>">Choisissez le modèle de votre smartphone ou tablette</a></h2>
		<h2 class="accordion-header third-header">Choisissez le type de réparation</h2>
		<div class="accordion-content third-content">
			<?php
				$pm_id =  $_GET['pm_id'];
				$_SESSION['pm_id'] = $pm_id;
				$sql1 = $wpdb->get_row("SELECT model_image FROM wp_phone_model where pm_id = '".$pm_id."'");
				$results = $wpdb->get_results("SELECT * FROM wp_model_service where pm_id = '".$pm_id."'");
			?>
			<div class="phone_service">
				<div class="phone_service_left2">
					<div class="ps_image">
						<?php
							echo '<img src="'.$dir.'/'.$sql1->model_image.'" style="height:176px; width:auto;"/>';
						?>
					</div>
				</div>
				<div class="phone_service_right2">
					<div class="phone_service_box">
						<ul>
							<?php
								/*if(mysql_num_rows($sql2)>0)
								{*/
									foreach ($results as $result)
									{
									echo '
									<li>
										<div class="service-name">
											<a href="service_type/?pm_id='.$result->pm_id.'&ms_id='.$result->ms_id.'">'.$result->service_name.'</a>
										</div>  
										<div class="service-price"><span>à partir de</span><a href="service_type/?pm_id='.$result->pm_id.'&ms_id='.$result->ms_id.'">'.$result->service_price.' &#8364;</a></div>  
									</li>
									';
									}
								/*}
								else
								{
									echo "<h3>There are no services for phone models.</h3>";
								}*/
							?>
						</ul>
					</div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
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
	$('.third-header').addClass('active-header').removeClass('inactive-header');
	$('.third-content').slideDown().addClass('open-content');
	});
</script>

<?php get_footer(); ?>
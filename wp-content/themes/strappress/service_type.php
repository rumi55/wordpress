<?php
/*
Template Name: service type
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
		<h2 class="accordion-header third-header"><a href="../services/?pm_id=<?php echo $_SESSION['pm_id']; ?>">Choisissez le type de réparation</a></h2>
		<h2 class="accordion-header four-header">Nous faire parvenir votre appareil</h2>
		<div class="accordion-content four-content">
			<?php
				$pm_id =  $_GET['pm_id'];
				$sql1 = $wpdb->get_row("SELECT model_image FROM wp_phone_model WHERE pm_id = '".$pm_id."'");
			?>
			<div class="phone_service sub_service_container row-fluid">
				<div class="phone_service_left span2">
					<div class="ps_image">
						<?php
							echo '<img src="'.$dir.'/'.$sql1->model_image.'" style="height:176px; width="auto;"" />';
						?>
					</div>
				</div>
				<div class="phone_service_right span10">
    			<div class="phone_service_box row-fluid">
						<?php
							$ms_id =  $_GET['ms_id'];
							$results = $wpdb->get_results("SELECT a.service_name,b.sub_service_price FROM wp_model_service a,wp_model_sub_service b WHERE a.ms_id = '".$ms_id."' AND b.ms_id = '".$ms_id."'");
							$i=1;
							foreach ($results as $result)
							{
		            if($i==1)
		            {	
									echo ' 
									<a href="contact.php">
									<div class="sub_service_box en_boutique_box span4">
										<div class="service_details">
											<span class="service_name">'.$result->service_name.'</span>
											<br/>
											<span class="service_price">'.$result->sub_service_price.' &#8364;</span>
										</div>
									</div></a>';
								}
								elseif($i==2)
								{
									echo '
									<a href="formulaire_de_contact_par_courrier.pdf">
									<div class="sub_service_box par_courrier_box span4">
										<div class="service_details">
											<span class="service_name">'.$result->service_name.'</span>
											<br/>
											<span class="service_price">'.$result->sub_service_price.' &#8364;</span>
										</div>
									</div></a>';
								}
								elseif($i==3)
           			{
									echo '
									<a href="adomicile.php">
									<div class="sub_service_box par_coursier_box span4">
										<div class="service_details">
											<span class="service_name">'.$result->service_name.'</span>
											<br/>
											<span class="service_price">'.$result->sub_service_price.' &#8364;</span>
										</div>
									</div></a>';
								}
								$i++;
							}
						?>
					</div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
	//Add Inactive Class To All Accordion Headers
	$('.accordion-header').addClass('inactive-header');
	//Open The First Accordion Section When Page Loads
	$('.four-header').addClass('active-header').removeClass('inactive-header');
	$('.four-content').slideDown().addClass('open-content');
	});
</script>

<?php get_footer(); ?>
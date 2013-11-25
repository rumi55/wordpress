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
<?php get_header(); ?>
<div class="row-fluid">
	<div class="sixteen colums">
		<div id="accordion-container">
			<h2 class="accordion-header"><a href="../tarifs/">Choisissez la marque de votre téléphone ou de votre tablette</a></h2>
			<div class="accordion-content">
				<?php
					$dir = get_template_directory_uri();
					$list_marque = $wpdb->get_results("SELECT * FROM wp_phone_company");
				?>
				<div class="phone_company">
					<ul>
						<?php
							foreach ($list_marque as $marque)
							{
								echo '
								<li>
									<div class="company_logo">
										<a class="anil" href="models/?pc_id='.$marque->pc_id.'">
											<img src="'.$dir.'/'.$marque->pc_image.'"/>
											<img class="overlay" src="'.$dir.'/'.$marque->pc_hover_image.'" alt="overlay" />
										</a>
									</div>
								</li> ';
							}
						?>
					</ul>
				</div>
				<div style="clear:both;"></div>
			</div>
			<h2 class="accordion-header second-header">Choisissez le modèle de votre smartphone ou tablette</h2>
			<div class="accordion-content second-content">
				<?php
					$pc_id =  $_GET['pc_id'];
					$_SESSION['pc_id'] = $pc_id;
					
					$sql1 = $wpdb->get_row("SELECT pc_image FROM wp_phone_company WHERE pc_id =  '".$pc_id."'");
					// $result1 = mysql_fetch_array($sql1);
					
					$models = $wpdb->get_results("SELECT * from  wp_phone_model WHERE pc_id = '".$pc_id."'");
				?>
				<div class="brand_image_container">
					<div class="brand_image">
					<!-- <img src="images/brands/apple_hover.png" />-->
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
										<a href="services.php?pm_id='.$model->pm_id.'"><img src="'.$dir.'/'.$model->model_image.'"/></a>
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
			<div class="accordion-content">
				<div class="phone_service">
					<div class="phone_service_left">
						<div class="ps_image">
							<img src="images/phone_model/ipad_retina.png">          
						</div>
					</div>
					<div class="phone_service_right">
						<div class="phone_service_box">
							<ul>
								<li>
									<div class="service-name">
										<a href="#">écran cassé</a>
									</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
									<a href="#">écran cassé</a>
										</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
									<a href="#">écran cassé</a>
										</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
									<a href="#">écran cassé</a>
										</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
									<a href="#">écran cassé</a>
										</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
									<a href="#">écran cassé</a>
										</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
										<a href="#">écran cassé</a>
									</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
										<a href="#">écran cassé</a>
									</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
										<a href="#">écran cassé</a>
									</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
										<a href="#">écran cassé</a>
									</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>

								<li>
									<div class="service-name">
										<a href="#">écran cassé</a>
									</div>  
									<div class="service-price"><span>à partir de</span> 219,00 €</div>  
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<h2 class="accordion-header">Nous faire parvenir votre appareil</h2>
			<div class="accordion-content">
				<div class="phone_service sub_service_container">
					<div class="phone_service_left">
						<div class="ps_image">
							<img src="images/phone_model/ipad_retina.png">          
						</div>
					</div>
					<div class="phone_service_right">
						<div class="phone_service_box">
							<div class="sub_service_box en_boutique_box">
								<div class="service_details">
									<span class="service_name">LCD + Vitre blanc</span>
									<br/>
									<span class="service_price">89,00 €</span>
								</div>
							</div>
							<div class="sub_service_box par_courrier_box">
								<div class="service_details">
									<span class="service_name">LCD + Vitre blanc</span>
										<br/>
									<span class="service_price">89,00 €</span>
								</div>
							</div>
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
		$('.second-header').addClass('active-header').removeClass('inactive-header');
		$('.second-content').slideDown().addClass('open-content');
		});  
	</script>
</div><!-- sixteen colum end -->

<?php get_footer(); ?>
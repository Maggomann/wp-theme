<?php
// Template Name: Portfolio-3-column
/**
* @Theme Name	:	wallstreet-Pro
* @file         :	portfolio-2-column.php
* @package      :	wallstreet-Pro
@author       :	webriti
* @filesource   :	wp-content/themes/wallstreet/portfolio-2-column.php
*/
get_header(); ?>

<!-- Page Title Section -->
<?php get_template_part('index', 'banner'); ?>
<!-- /Page Title Section -->
<?php $wallstreet_pro_options=theme_data_setup();
	  $current_options = wp_parse_args(  get_option( 'wallstreet_pro_options', array() ), $wallstreet_pro_options ); 
		 $j=1;
	$tab= get_option('tab');
	?>
<div class="container">
	<div class="row">
			<div class="section_heading_title">
				<?php if($current_options['portfolio_title']) { ?>
				<h1><?php echo $current_options['portfolio_title']; ?></h1>
				<div class="pagetitle-separator">
					<div class="pagetitle-separator-border">
						<div class="pagetitle-separator-box"></div>
					</div>
				</div>
			<?php } ?>
			<?php if($current_options['portfolio_description']) { ?>
				<p><?php echo $current_options['portfolio_description']; ?></p>
			<?php } ?>				
			</div>
		</div>

<!-- Category Filter Layer-->
<div class="row">

	<?php
		//for a given post type, return all
		$post_type = 'wallstreet_portfolio';
		$tax = 'portfolio_categories'; 
		$term_args=array( 'hide_empty' => true);
		$tax_terms = get_terms($tax, $term_args);
		$defualt_tex_id = get_option('wallstreet_webriti_default_term_id');
	?>	
	
		<div class="col-md-12 portfolio-tabs-section">
			<ul class="portfolio-tabs" id="mytabs">
				<?php	foreach ($tax_terms  as $tax_term) { 
				$tax_term_name = str_replace(' ', '_', $tax_term->name);
				$tax_term_name = preg_replace('~[^A-Za-z\d\s-]+~u', 'qua', $tax_term_name);
				?>
				<li class="tab <?php if($tab==''){if($j==1){echo 'active';$j=2;}}else if($tab==$tax_term_name){echo 'active';}?>" >
					<a data-toggle="tab"  href="#<?php echo $tax_term_name; ?>"><?php echo $tax_term->name; ?></a>
				</li>
			<?php } ?>
			</ul>
		</div>		
</div><!-- /Category Filter Layer-->
	
	<div id="myTabContent" class="tab-content main-portfolio-section">
	<?php $norecord=0;
	global $paged;
			$curpage = $paged ? $paged : 1;
			
			$norecord=0;
			$total_posts=0;
			$min_post_start=0;
			$is_active=true;
	if ($tax_terms) 
	{ 	foreach ($tax_terms  as $tax_term)
		{	$count_posts = wp_count_posts( $post_type)->publish; 
			$args = array (
			'post_type' => $post_type,
			'portfolio_categories' => $tax_term->slug,
			'posts_per_page' =>6,
			'paged' => $curpage,
			'post_status' => 'publish');
		$j=1;
		$portfolio_query = null;		
		$portfolio_query = new WP_Query($args);				
		if( $portfolio_query->have_posts() )
		{ 
		$tax_term_name = str_replace(' ', '_', $tax_term->name);
		$tax_term_name = preg_replace('~[^A-Za-z\d\s-]+~u', 'qua', $tax_term_name); ?>
		<div id="<?php echo $tax_term_name; ?>" class="tab-pane fade in <?php if($tab==''){if($is_active==true){echo 'active';}$is_active=false;}else if($tab==$tax_term->name){echo 'active';} ?>">
		<div class="row">
		<?php while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); ?>
		<?php 	if(get_post_meta( get_the_ID(),'meta_project_link', true )) 
				{ $meta_project_link=get_post_meta( get_the_ID(),'meta_project_link', true ); }
				else { $meta_project_link = get_post_permalink(); } ?>
				<div class="col-md-4 col-md-6 main-portfolio-area">
					<div class="main-portfolio-showcase">
					<div class="main-portfolio-showcase-media">
					<?php
					if(has_post_thumbnail())
						{ 
							$class=array('class'=>'img-responsive');
							the_post_thumbnail('', $class);
							$post_thumbnail_id = get_post_thumbnail_id();
							$post_thumbnail_url = wp_get_attachment_url($post_thumbnail_id );
						} 
						else 
						{ ?>
							<img src="<?php echo WEBRITI_TEMPLATE_DIR_URI; ?>images/portfolio/main-port1.jpg" class="img-responsive">
							<?php $post_thumbnail_url=WEBRITI_TEMPLATE_DIR_URI .'/images/portfolio/main-port1.jpg'; 
						} ?>
						<div class="main-portfolio-showcase-overlay">
							<div class="main-portfolio-showcase-overlay-inner">
								<div class="main-portfolio-showcase-detail">
								<h4><?php the_title();?></h4>
									<p><?php the_excerpt();?></p>
									<div class="portfolio-icon">
										<a  <?php  if(get_post_meta( get_the_ID(),'meta_project_target', true )) { echo "target='_blank'"; }  ?> class="hover_thumb" title="<?php the_title(); ?>" data-lightbox="image" href="<?php echo $post_thumbnail_url; ?>" ><i class="fa fa-picture-o"></i></a>
										<a <?php  if(get_post_meta( get_the_ID(),'meta_project_target', true )) { echo "target='_blank'"; }  ?> href="<?php echo $meta_project_link; ?>"><i class="fa fa-link"></i></a>
									</div>									
								</div>
							</div>
						</div>
						</div>
					</div>					
				</div>
				<?php if($j%3==0){ echo "<div class='clearfix'></div>"; } $j++;
				$norecord=1; endwhile; ?>				
			</div>
			<?php $Webriti_pagination = new Webriti_pagination();
				  $Webriti_pagination->Webriti_page($curpage, $portfolio_query);	?>
		</div>
		<?php 
			} /* end term wise data */	wp_reset_query();
		} // end for-each tax_terms
	} // end of text data 
	?>
	</div>	
</div>
<?php if(!$norecord) { ?>
	<div class="container">
		<div class="row">
			<div class="wallstreet_page_heading" style="text-align:center;">
				<h3><?php _e('Oops! No Portfolio Found! Please add a Portfolio Using the Portfolios / Projects section.','wallstreet'); ?> </h3>
			</div>
		</div>
	</div>
<?php }	?>
<script type="text/javascript">
jQuery('.tab').click(function(e) {
				e.preventDefault();
				var h = jQuery("a",this).attr('href').replace(/#/, "");
				jQuery.ajax({
					url: "",
					type: "POST",
					data: { code : h },
					dataType: "html"
				});
});

</script>
<?php
if(isset($_POST['code'])){
	
	$code = $_POST['code'];
	update_option('tab',$code);
	
}
get_footer();
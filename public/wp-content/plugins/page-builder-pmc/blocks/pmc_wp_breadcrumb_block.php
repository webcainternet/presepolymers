<?php/** "News" block  *  * Optional to use horizontal lines/images**/class PMC_Wp_Breadcrumb_Block extends AQ_Block {		//set and create block	function __construct() {		$block_options = array(			'name' => 'WP Breadcrumb',			'size' => 'span12',			'resizable' => 0,			'icon' => 'fa-th-list',			'icon_color' => 'FFF',			'category' => 'Content',			'help' => 'This is the block that adds WP breadcrumb to the top section (or any other section if you wish it to be elsewhere).'		);				//create the block		parent::__construct('pmc_wp_breadcrumb_block', $block_options);	}		function form($instance) {				$defaults = array(							'use_background' => '1',		'background_color' => '#fff',		'use_border' => '1',		'border_color' => '#000',				'text_color' => '#000',					);						$instance = wp_parse_args($instance, $defaults);		extract($instance);					$instance = wp_parse_args($instance, $defaults);			extract($instance);			if( function_exists( 'pmc_breadcrumb' ) ){			?>			<p class="description note">				<?php _e('Use this block to create breadcrumb.', 'pmc-themes') ?>			</p>		<div class="description">						<label for="<?php echo $this->get_field_id('use_background') ?>">							<?php echo aq_field_checkbox('use_background', $block_id, $use_background); ?>							Use color for background?						</label>				</div>				<div class="description">		<label for="<?php echo $this->get_field_id('hover_color') ?>">			Background color<br/>			<?php echo aq_field_color_picker('background_color', $block_id, $background_color, $default = '#fff'); ?>		</label>			</div>			<div class="description">						<label for="<?php echo $this->get_field_id('use_border') ?>">							<?php echo aq_field_checkbox('use_border', $block_id, $use_border); ?>							Use top and bottom border?						</label>				</div>				<div class="description">		<label for="<?php echo $this->get_field_id('border_color') ?>">			Border color<br/>			<?php echo aq_field_color_picker('border_color', $block_id, $border_color, $default = '#000'); ?>		</label>			</div>					<div class="description">		<label for="<?php echo $this->get_field_id('text_color') ?>">			Text color<br/>			<?php echo aq_field_color_picker('text_color', $block_id, $text_color, $default = '#000'); ?>		</label>			</div>				<?php		}else {			echo '<p class="description note">For this block you need to use PremiumCoding themes!</p>';		}				}		function block($instance) {		$defaults = array(							'use_background' => '1',		'background_color' => '#fff',		'use_border' => '1',		'border_color' => '#000',				'text_color' => '#000',			);						$instance = wp_parse_args($instance, $defaults);		extract($instance);				if (function_exists( 'pmc_breadcrumb' ) ) { ?>			<div class = "outerpagewrap">				<style scoped>.pagewrap a, .pagewrap h1, .pagewrap h1 span, .pagewrap p{color: <?php if(isset($text_color)){ echo $text_color.'; ';} ?>}</style>				<div class="pagewrap" style="<?php if(isset($use_background) && $use_background == 1 ){ echo 'background:'.$background_color.'; ';}  if(isset($use_border) && $use_border == 1){ echo 'border: 1px solid '.$border_color.';'; } ?>">					<div class="pagecontent">						<div class="pagecontentContent-title">							<?php if(function_exists('is_shop')){ ?>								<?php if(is_shop()){ ?>									<h1><?php _e('Shop','pmc-themes'); ?></h1>								<?php } else if (!is_category() && !is_search() && !is_author() && !is_archive() && !is_tag() && !is_page() && !is_single() && !is_front_page()){ ?>									<h1><?php the_title() ?></h1>								<?php } else { ?>									<?php }  ?>								<?php } ?>														<?php if(is_category()){ ?>								<h1><?php single_cat_title( '', true ); ?></h1>								<?php }  else  if(is_archive()){ ?>								<?php if(function_exists('is_shop')){ ?>									<?php if(!is_shop()){ ?>										<h1><?php  _e('Archives','pmc-themes'); ?></h1>									<?php } ?>								<?php } ?>							<?php }  else  if(is_search()){ ?>								<h1><?php _e('Search for: ','pmc-themes'); echo get_search_query(); ?></h1>								<?php }  else  if(is_front_page()){ ?>								<h1><?php echo get_bloginfo( 'name' ) ?></h1>															<?php }  else  if(is_tag()){ ?>								<h1><?php _e('Tags','pmc-themes'); ?></h1>																<?php } else { ?>								<h1><?php  the_title() ?></h1>							<?php }  ?>														</div>								<div class="pagecontentContent-breadcrumb">							<p><?php echo pmc_breadcrumb() ?></p>						</div>					</div>				</div>			</div> 		<?php }}}?>
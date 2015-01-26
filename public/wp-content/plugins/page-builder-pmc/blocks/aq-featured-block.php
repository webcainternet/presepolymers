<?php
/** Features Block 
 * A simple block that output the "features" HTML */
if(!class_exists('AQ_Featured_Circles_Bloc')) {
	class AQ_Featured_Block extends AQ_Block {
		
		function __construct() {
			$block_options = array(
				'name' => 'Features',
				'size' => 'span3',
				'icon' => 'fa-pencil-square-o',
				'icon_color' => 'FFF',
				'category' => 'Content',
				'help' => 'This is the block that adds features/services. Set the title, small title, link and text. Check roll the title if you wish dynamic rolling of numbers.'
			);
			
			parent::__construct('aq_featured_block', $block_options);
		}
		
		function form($instance) {
			$defaults = array(
				'title' => '',				
				'icon' => 'fa-pencil-square-o',
				'text' => '',
				'color' => '#F5F6F1',
				'textcolor' => '#FFFFFF',
				'textcolorp' => '#FFFFFF',
				'link' => 'http://premiumcoding.com',
				'numbers' => 0,
				'step' => 1,
				'animated' => 1,
				'animated_from' => 'fadeInLeft'
			
			);
			
			$textcolor_option = array(
				'light'  => 'Light (white)',
				'dark'  => 'Theme main color',
				
			);	

			
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			?>
			<p class="description">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title<br/>
					<?php echo aq_field_input('title', $block_id, $title) ?>
				</label>
			</p>	
			<p class="description">
				<label for="<?php echo $this->get_field_id('icon') ?>">
					Awesome Icon Class - <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">refer here</a><br/>
					<?php echo aq_field_input('icon', $block_id, $icon) ?>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('numbers') ?>">
					<?php echo aq_field_checkbox('numbers', $block_id, $numbers); ?>
					Roll the title (only for numbers)?
				</label>
			</p>	
			<p class="color">
				<label for="<?php echo $this->get_field_id('color') ?>">
					Select Background color
					<?php echo aq_field_color_picker('color', $block_id, $color, $size = 'full') ?>
				</label>
			</p>	
			<p class="textcolor">
				<label for="<?php echo $this->get_field_id('textcolor') ?>">
					Select Title Text color
					<?php echo aq_field_color_picker('textcolor', $block_id, $textcolor, $size = 'full') ?>
				</label>
			</p>
			<p class="textcolorp">
				<label for="<?php echo $this->get_field_id('textcolorp') ?>">
					Select Small Text color
					<?php echo aq_field_color_picker('textcolorp', $block_id, $textcolorp, $size = 'full') ?>
				</label>
			</p>
			<p class="description">
				<label for="<?php echo $this->get_field_id('link') ?>">
					Link<br/>
					<?php echo aq_field_input('link', $block_id, $link) ?>
				</label>
			</p>				
			<p class="description">
				<label for="<?php echo $this->get_field_id('text') ?>">
					Feature text
					<?php echo aq_field_textarea('text', $block_id, $text, $size = 'full') ?>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('animated') ?>">
					<?php echo aq_field_checkbox('animated', $block_id, $animated); ?>
					Animated?
				</label>
			</p>			
			<p class="description">
				<label for="<?php echo $this->get_field_id('animated_from') ?>">
					Animation<br/>
					For animations check <a href="http://daneden.github.io/animate.css/">Animated</a>
					<?php echo aq_field_input('animated_from', $block_id, $animated_from) ?>
				</label>
			</p>			
			
			<?php
			
		}
		
		function block($instance) {
			$defaults = array(
				'title' => '',				
				'icon' => 'fa-pencil-square-o',
				'text' => '',
				'color' => '#F5F6F1',
				'textcolor' => '#FFFFFF',
				'textcolorp' => '#FFFFFF',
				'link' => 'http://premiumcoding.com',
				'animated' => 1,
				'animated_from' => 'fadeInLeft'			
			);
						
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			wp_enqueue_style('font-awesome');
			$rand = rand(1,100);
			$animated_class = '';
			if($animated){
				$animated_class = 'animated '.$animated_from;
			}
			if($link != ''){?>
				<a href="<?php echo $link ?>">
			<?php } ?>
			
			<div class="featured-block <?php echo $animated_class ?>" style="background:<?php echo $color;?>; ">
			
				<div class="featured-block-title" ><?php if($title) {?> <h5 class="feature-title <?php if($numbers == 1) { echo ' number-animate';} ?>" style="color:<?php echo $textcolor;?> !important; " <?php if($numbers == 1) { echo 'id="number-'.$rand.'"';} ?>><?php echo strip_tags($title) ?></h5> <?php } ?></div>
				<div class = "featuredIcon"><i class="fa <?php echo $icon ?>"></i></div>
				<div class="featured-block-text" style="color:<?php echo $textcolorp;?> !important; "><?php echo wpautop(do_shortcode(htmlspecialchars_decode($text))); ?></div>
			
			</div>
			<?php if($link != ''){?>
				</a>
			<?php } ?>			
			<?php
			
		}
		
	}
}


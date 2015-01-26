<?php
/** Features Block */
if(!class_exists('AQ_Features_Block')) {
	class AQ_Features_Block extends AQ_Block {
		
		function __construct() {
			$block_options = array(
				'name' => 'Features (Simple)',
				'size' => 'span3',
				'icon' => 'fa-pencil-square-o',
				'icon_color' => 'FFF',
				'category' => 'Content',
				'help' => 'This is the block that adds features/services. Set the title, link and text.'
				
			);
			
			parent::__construct('aq_features_block', $block_options);
		}
		
		function form($instance) {
			$defaults = array(
				'title' => '',
				'icon' => 'icon-file',
				'text' => '',
				'animated' => 1,
				'animated_from' => 'slideInLeft'				
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
			<p class="description">
				<label for="<?php echo $this->get_field_id('text') ?>">
					Feature text
					<?php echo aq_field_textarea('text', $block_id, $text, $size = 'full') ?>
				</label>
			</p>
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
				'animated' => 1,
				'animated_from' => 'slideInLeft'			
			);
						
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			wp_enqueue_style('font-awesome');
			$animated_class = '';
			if($animated){
				$animated_class = 'animated '.$animated_from;
			}
			$rand = rand(0,999);
			
			echo '<div id="rand-'.$rand.'" class="pmc-animate pmc-animated '.$animated_class .'">';
			
			if($icon) $icon = '<div class = "featuredIcon"><i class="fa '.$icon.' fa-3x"></i></div> &nbsp;';
			
			if($title) echo $icon.'<h3 class="feature-title">'.strip_tags($title). '</h3>';
			
			echo wpautop(do_shortcode(htmlspecialchars_decode($text)));
			
			echo '</div>';
			
		}
		
	}
}


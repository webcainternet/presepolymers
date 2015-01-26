<?php
/* Pricing Tables Block */

	class AQ_Pricetable_Block extends AQ_Block {
		
		function __construct() {
			$block_options = array(
				'name' => 'Pricing Table',
				'size' => 'span4',				'icon' => 'fa-money',				'icon_color' => 'FFF',				'category' => 'Shortcodes',				'help' => 'Block for adding pricing tables.'
			);
			
			parent::__construct('aq_pricetable_block', $block_options);
		}
		
		function form($instance) {
			$defaults = array(
				'title' => 'Normal',
				'price' => 'free',
				'timeline' => '',
				'img' => '',
				'features' => '',
				'style' => 'light',
				'color' => 'grey'
			);
			
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			$style_options = array (
				'light' => 'Light',
				'dark' => 'Dark'
			);
			
			$color_options = array (
				'grey' => 'Grey',
				'blue' => 'Blue',
				'green' => 'Green'
			)
			
			?>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Package Title (required)<br/>
					<?php echo aq_field_input('title', $block_id, $title) ?>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('img') ?>">
					Upload an image (optional)<br/>
					<?php echo aq_field_upload('img', $block_id, $img) ?>
				</label>
				<?php if($img) { ?>
				<div class="screenshot">
					<img src="<?php echo $img ?>" />
				</div>
				<?php } ?>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('price') ?>">
					Price (required)<br/>
					<?php echo aq_field_input('price', $block_id, $price) ?>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('timeline') ?>">
					Pricing timeline (e.g. "per month")<br/>
					<?php echo aq_field_input('timeline', $block_id, $timeline) ?>
				</label>
			</p>
			<p class="description">
				<label for="<?php echo $this->get_field_id('features') ?>">
					Features (start each feature on new line)
					<?php echo aq_field_textarea('features', $block_id, $features) ?>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('column') ?>">
					Pricing table style<br/>
					<?php echo aq_field_select('style', $block_id, $style_options, $style); ?>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('column') ?>">
					Pricing color style<br/>
					<?php echo aq_field_select('color', $block_id, $color_options, $color); ?>
				</label>
			</p>
			<?php
			
		}
		
		function block($instance) {
			extract($instance);
			
			$output = '<div class="aq-pricetable-wrapper '.$style.' '.$color.' cf">';
				$output .= '<ul class="aq-pricetable-items">';
					
					$output .= '<li class="aq-pricetable-item aq-pricetable-title"><h3 class="title">'.htmlspecialchars_decode($title).'</h3>';
					
						if(!empty($img)) {
							$width = aq_get_column_width($size);
							$img = aq_resize($img, $width);
							$output .= '<div class="aq-pricetable-item aq-pricetable-img">';
								$output .= '<img src="'.$img.'"/>';
							$output .= '</div>';
						}
						
						$output .= '<div class="aq-pricetable-item aq-pricetable-price">';
							$output .= '<h3 class="price">'.htmlspecialchars_decode($price).'</h3>';
							$output .= !empty($timeline) ? '<span>'.htmlspecialchars_decode($timeline).'</span>' : '';
						$output .= '</div>';
					
					$output .= '</li>';
					
					$features = !empty($features) ? explode("\n", trim($features)) : array();
					
					foreach($features as $feature) {
						$output .= '<li class="aq-pricetable-item">'.do_shortcode(htmlspecialchars_decode($feature)).'</li>';
					}
					
				$output .= '</ul>';
			$output .= '</div>';
			
			echo $output;	
		}
		
	}

<?php

class AQ_Slider_Block_revolutionSlider extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Rev. Slider',
			'size' => 'span12',
			'icon' => 'fa-picture-o',
			'icon_color' => 'FFF',
			'category' => 'Content',
			'help' => 'This is the block that adds revolution slider. Simply choose which Slider you wish to show (you set the sliders in Revolution Slider settings).'
		);
		
		//create the block
		parent::__construct('aq_slider_block_revolutionslider', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'title' => '',
			'revSliderAlias' => '',
		);
		

		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		$title = $revSliderAlias;
		if(!is_plugin_active( 'revslider/revslider.php')) {
			echo __('Sorry, this block requires the Revolution Slider plugin to be installed & activated. Please install/activate the plugin before using this block. You can find plugin in theme folder /plugins/cherry.zip', 'pmc-themes');
			return false;
		}		
		
		?>
		<p class="description note">
			<?php _e('Use this block to include Revolution Sliders .', 'pmc-themes') ?>
		</p>

		<?php echo aq_field_input('title', $block_id, $revSliderAlias,'','hidden') ?>
		
		 
		<p class="description">
			<label for="<?php echo $this->get_field_id('revSliderAlias') ?>">
				Select Revolution Sliders:								
				<?php 				
				$slider = new RevSlider();				
				$arrSliders = $slider->getArrSliders();				
				if(!empty($arrSliders)){ 					
					$revSliderArray = array();					
					foreach($arrSliders as $sliders){ 						
						$revSliderArray[$sliders->getAlias()] = $sliders->getShowTitle();					
					} 					
					echo aq_field_select('revSliderAlias', $block_id, $revSliderArray, $revSliderAlias);				
				}																
				?>
			</label>
		</p>
	
	
		<?php
		
	}
	
	function block($instance) {
		extract($instance);
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if(is_plugin_active( 'revslider/revslider.php')){			
			putRevSlider($revSliderAlias);			
		}
	
	}

	function update($new_instance, $old_instance) {
		$new_instance = aq_recursive_sanitize($new_instance);
		return $new_instance;
	}

}
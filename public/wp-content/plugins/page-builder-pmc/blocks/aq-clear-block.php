<?php
/** "Clear" block 
 * 
 * Clear the floats vertically
 * Optional to use horizontal lines/images
**/
class AQ_Clear_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Vertical Margin',
			'size' => 'span12',			'icon' => 'fa-text-height',			'icon_color' => 'FFF',			'category' => 'Structure',			'help' => 'This is the block that adds the vertical margin between blocks (empty space).'
		);
		
		//create the block
		parent::__construct('aq_clear_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'horizontal_line' => 'none',
			'line_color' => '#353535',
			'pattern' => '1',
			'height' => '',
			'color' => '#fff'
		);
		
		$line_options = array(
			'none' => 'None',
			'single' => 'Single',
			'double' => 'Double',						'cherry' => 'Special line',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		$line_color = isset($line_color) ? $line_color : '#353535';
		
		?>
		<p class="description note">
			<?php _e('Use this block to clear the floats between two or more separate blocks vertically.', 'pmc-themes') ?>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('line_color') ?>">
				Pick a horizontal line<br/>
				<?php echo aq_field_select('horizontal_line', $block_id, $line_options, $horizontal_line, $block_id); ?>
			</label>
		</p>
		<div class="description half last">
			<label for="<?php echo $this->get_field_id('height') ?>">
				Height (optional)<br/>
				<?php echo aq_field_input('height', $block_id, $height, 'min', 'number') ?> px
			</label>
		</div>
		<div class="description half ">
			<label for="<?php echo $this->get_field_id('color') ?>">
				Background color<br/>
				<?php echo aq_field_color_picker('color', $block_id, $color, $defaults['color']) ?>
			</label>
			
		</div>		
		<div class="description half last">
			<label for="<?php echo $this->get_field_id('line_color') ?>">
				Pick a line color<br/>
				<?php echo aq_field_color_picker('line_color', $block_id, $line_color, $defaults['line_color']) ?>
			</label>
			
		</div>
		<?php
		
	}
	
	function block($instance) {
		$defaults = array(
			'color' => '#fff'
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);		
		switch($horizontal_line) {
			case 'none':
				break;
			case 'single':
				echo '<hr class="aq-block-clear aq-block-hr-single" style="background:'.$line_color.';"/>';
				break;
			case 'double':
				echo '<hr class="aq-block-clear aq-block-hr-double" style="background:'.$line_color.';"/>';
				echo '<hr class="aq-block-clear aq-block-hr-single" style="background:'.$line_color.';"/>';
				break;							case 'cherry':				echo '<div class="line-cherry"></div>';				break;				
		}
		
		if($height) {
			echo '<div class="cf" style="height:'.$height.'px; background:'.$color.'"></div>';
		}
		
	}
	
}
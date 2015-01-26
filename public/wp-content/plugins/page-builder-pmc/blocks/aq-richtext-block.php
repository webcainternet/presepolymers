<?php
/** A simple rich textarea block **/
class AQ_Richtext_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Text & ShortCode',
			'size' => 'span12',
			'icon' => 'fa-font',
			'icon_color' => 'FFF',
			'category' => 'Text Shortcodes',
			'help' => 'Article block allows you to add the magazine article, like in the live preview.'

		);
		
		//create the block
		parent::__construct('aq_richtext_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'text' => '',
			'rand' => rand(0,999)
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		

		global $pmc_data;
		?>
			<div id="shortcodes" class="shortcodes">
			<div id="add_shortcode_button" class="add_shortcode_button">Add new ShortCode</div>

		
			<p class="description">
				<label for="<?php echo $this->get_field_id('text') ?>">
					Content				
				</label>
				<?php  echo aq_field_textarea('text', $block_id, $text, $size = 'full pmc-editor pmc-editor-'.$rand) ?>				
				
			</p>		
		</div>  

		
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		echo do_shortcode(htmlspecialchars_decode($text));
	}
	
}
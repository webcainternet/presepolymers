<?php
/** "News" block 
 * 
 * Optional to use horizontal lines/images
**/
class AQ_End_Content_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'End Content',
			'size' => 'span12',
			'resizable' => 0,			'icon' => 'fa-dot-circle-o',			'icon_color' => 'FFF',			'category' => 'Structure',			'help' => 'This is the start content block. If you wish the content to be centered and not fullwidth, it must be placed bettween start and end blocks.'
		);
		
		//create the block
		parent::__construct('aq_end_content_block', $block_options);
	}
	
	function form($instance) {
		
		

		

		extract($instance);
		
		
		?>
		<p class="description note">
			<?php _e('Use this block to create main content block after top Wrappers.', 'pmc-themes') ?>
		</p>

	
		<?php
		
	}
	
	function block($instance) {
		extract($instance);
		$text = '		</div></div>
					</div>';
		echo $text;
	
}}
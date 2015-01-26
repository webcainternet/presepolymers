<?php
/** "News" block 
 * 
 * Optional to use horizontal lines/images
**/
class AQ_Quote_Title_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Quotations',
			'size' => 'span12',
			'resizable' => 0,
			'icon' => 'fa-quote-right',
			'icon_color' => 'FFF',
			'category' => 'Content',
			'help' => 'This is the block that adds quotes.'
		);
		
		//create the block
		parent::__construct('aq_quote_title_block', $block_options);
	}
	
	function form($instance) {
		
		//get all registered sidebars
		global $wp_registered_sidebars;

		
		$defaults = array(
			'title' => '',
			'title_small' => '',
			'title_color' => '#fff',
			'id' => '',				
		);	

		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		
		?>
		
		<p class="description note">
			<?php _e('Use this block to quote with widget under slider area.', 'pmc-themes') ?>
		</p>		
		<p class="description">
			<label for="<?php echo $this->get_field_id('title') ?>">
				Title
				<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('title_small') ?>">
				Small title
				<?php echo aq_field_input('title_small', $block_id, $title_small, $size = 'full') ?>
			</label>
		</p>		
		<p class="description ">
			<label for="<?php echo $this->get_field_id('id') ?>">
				Block ID used for menu scroll
				<?php echo aq_field_input('id', $block_id, $id, $size = 'full') ?>
			</label>
		</p>			
		<div class="description ">
			<label for="<?php echo $this->get_field_id('title_color') ?>">
				Title color<br/>
				<?php echo aq_field_color_picker('title_color', $block_id, $title_color, $defaults['title_color']) ?>
			</label>
			
		</div>		

	
		<?php
		
	}
	
	function block($instance) {
		extract($instance);
		$id_out = '';
		if(isset($id) && $id != ''){
			$id_out = 'id="'.$id.'"';
		}		
		?>
		<div class="infotextwrap" <?php echo $id_out ?>>
			
			<div class="infotext">
			<div class="infotext-before"></div>
			<div class="infotext-title">
				<h2 style="color:<?php echo $title_color ?>"><?php echo do_shortcode(htmlspecialchars_decode($title)) ?></h2>
				<div class="infotext-title-small" style="color:<?php echo $title_color ?>"><?php echo wpautop(do_shortcode(htmlspecialchars_decode($title_small))) ?></div>
			</div>
			<div class="infotext-after"></div>
			</div>
		</div>
		<?php
	
}}
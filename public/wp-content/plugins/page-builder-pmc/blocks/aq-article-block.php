<?php
/** A simple Article block **/
class AQ_Article_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Article',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('aq_article_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'text_left' => '',
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		<p class="description">
			<label for="<?php echo $this->get_field_id('title') ?>">
				Title 
				<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			</label>
		</p>
		<p class="description">
		<p class="description">
			<label for="<?php echo $this->get_field_id('text_left') ?>">
				Content left column
				<?php  echo aq_field_textarea('text_left', $block_id, $text_left, $size = 'full pmc-editor') ?>
		</p>
		<p class="description">
		<?php
	}
	
	function block($instance) {
		extract($instance);
			<div class="title-date"><?php echo $date ?></div>

	}
	
}
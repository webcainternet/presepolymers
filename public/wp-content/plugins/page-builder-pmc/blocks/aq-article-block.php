<?php
/** A simple Article block **/
class AQ_Article_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Article',
			'size' => 'span12',			'icon' => 'fa-font',			'icon_color' => 'FFF',			'category' => 'Text',			'help' => 'Article block allows you to add the magazine article, like in the live preview.'
		);
		
		//create the block
		parent::__construct('aq_article_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(			'title' => '',
			'text_left' => '',			'text_right' => '',			'date' => ''
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
		<p class="description">			<label for="<?php echo $this->get_field_id('date') ?>">				Date				<?php echo aq_field_input('date', $block_id, $date, $size = 'full') ?>			</label>		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('text_left') ?>">
				Content left column				</label>
				<?php  echo aq_field_textarea('text_left', $block_id, $text_left, $size = 'full pmc-editor') ?>
		</p>
		<p class="description">			<label for="<?php echo $this->get_field_id('text_right') ?>">				Content right column				</label>				<?php  echo aq_field_textarea('text_right', $block_id, $text_right, $size = 'full pmc-editor') ?>		</p>		
		<?php
	}
	
	function block($instance) {		$defaults = array(			'title' => '',			'text_left' => '',			'text_right' => '',			'date' => ''		);		$instance = wp_parse_args($instance, $defaults);
		extract($instance);		?>				<div class="title-wrap">
			<div class="title-date"><?php echo $date ?></div>			<div class="title"><h3><?php echo $title ?></h3></div>		</div>		<div class= "article-wrap">			<div class="left-column-article"><?php echo wpautop(do_shortcode(htmlspecialchars_decode($text_left))) ?></div>			<div class="right-column-article"><?php echo wpautop(do_shortcode(htmlspecialchars_decode($text_right))) ?></div>				</div>
	<?php
	}
	
}
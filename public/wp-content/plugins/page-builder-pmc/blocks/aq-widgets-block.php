<?php
/* Registered Sidebars Blocks */
class AQ_Widgets_Block extends AQ_Block {
	
	function __construct() {
		$block_options = array(
			'name' => 'Widgets',
			'size' => 'span3',			'icon' => 'fa-th',			'icon_color' => 'FFF',			'category' => 'Content',			'help' => 'This is the block that adds widgets block. You can add any sidebar that is set in Appearance -> Widgets'
		);
		
		parent::__construct('AQ_Widgets_Block', $block_options);
	}
	
	function form($instance) {
		
		
		//get all registered sidebars
		global $wp_registered_sidebars;
		$sidebar_options = array(); $default_sidebar = '';
		foreach ($wp_registered_sidebars as $registered_sidebar) {
			$default_sidebar = empty($default_sidebar) ? $registered_sidebar['id'] : $default_sidebar;
			$sidebar_options[$registered_sidebar['id']] = $registered_sidebar['name'];
		}
		
		$defaults = array(
			'sidebar' => $default_sidebar,			'color' => '#fff'
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		<p class="description widget-title">
			<label for="<?php echo $block_id ?>_title">
				Title (optional)<br/>
				<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			</label>
		</p>
		<p class="description">
			<label for="">
				Choose widget area<br/>
				<?php echo aq_field_select('sidebar', $block_id, $sidebar_options, $sidebar); ?>
			</label>
		</p>		<div class="description ">			<label for="<?php echo $this->get_field_id('color') ?>">				Text color<br/>				<?php echo aq_field_color_picker('color', $block_id, $color, $defaults['color']) ?>							</label>					</div>		
		<?php
	}
	
	function block($instance) {		$defaults = array(			'color' => '#fff'		);		$instance = wp_parse_args($instance, $defaults);		extract($instance);				?>		<script type="text/javascript">		jQuery(document).ready(function(){				jQuery('.aq-block-aq_widgets_block .widget div').css('color','<?php echo $color ?> !important');		});		</script>		<div class="block-widget">				<?php dynamic_sidebar($sidebar); ?>		</div>		<?php
	}
	
}
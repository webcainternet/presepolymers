<?php
/** Notifications block **/

if(!class_exists('AQ_Alert_Block')) {
	class AQ_Alert_Block extends AQ_Block {
		
		//set and create block
		function __construct() {
			$block_options = array(
				'name' => 'Alerts',
				'size' => 'span6',				'icon' => 'fa-exclamation-circle',				'icon_color' => 'FFF',				'category' => 'Shortcodes',				'help' => 'Block for simple alert boxes.'
			);
			
			//create the block
			parent::__construct('aq_alert_block', $block_options);
		}
		
		function form($instance) {
			
			$defaults = array(
				'content' => '',
				'type' => 'note',
				'style' => '',
				'icon' => 'icon-smile'				
			);
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			$type_options = array(
				'default' => 'Standard',
				'info_aq' => 'Info',
				'note' => 'Notification',
				'warn' => 'Warning',
				'tips' => 'Tips'
			);
			
			?>
			
			<p class="description">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title (optional)<br/>
					<?php echo aq_field_input('title', $block_id, $title) ?>
				</label>
			</p>
			<p class="description">
				<label for="<?php echo $this->get_field_id('content') ?>">
					Alert Text (required)<br/>
					<?php echo aq_field_textarea('content', $block_id, $content) ?>
				</label>
			</p>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('type') ?>">
					Alert Type<br/>
					<?php echo aq_field_select('type', $block_id, $type_options, $type) ?>
				</label>
			</p>
			<p class="description half last">
				<label for="<?php echo $this->get_field_id('style') ?>">
					Additional inline css styling (optional)<br/>
					<?php echo aq_field_input('style', $block_id, $style) ?>
				</label>
			</p>
			<p class="description">
				<label for="<?php echo $this->get_field_id('icon') ?>">
					Icon for alert box - <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">refer here</a><br/>
					<?php echo aq_field_input('icon', $block_id, $icon) ?>
				</label>
			</p>			
			<?php
			
		}
		
		function block($instance) {
			extract($instance);
			if($icon) $icon = '<i class="'.$icon.'"/></i> &nbsp;';
			echo '<div class="aq_alert '.$type.' cf" style="'. $style .'">' .$icon. do_shortcode(htmlspecialchars_decode($content)) . '</div>';
			
		}
		
	}
}
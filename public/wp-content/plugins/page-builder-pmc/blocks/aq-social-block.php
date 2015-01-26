<?php
class AQ_Social_Block extends AQ_Block {		//set and create block	function __construct() {		$block_options = array(			'name' => 'Social',			'size' => 'span12',
			'icon' => 'fa-group',
			'icon_color' => 'FFF',
			'category' => 'Social',
			'help' => 'Add Social Block. You can add the social networks in admin panel of the Theme (under Social Tab).'			);				//create the block		parent::__construct('aq_social_block', $block_options);	}		function form($instance) {				$defaults = array(			);						$instance = wp_parse_args($instance, $defaults);		extract($instance);						?>		<p class="description note">			<?php _e('This block is used to display social links set in theme admin.', 'pmc-themes') ?>		</p>		<?php			}		function block($instance) {		$defaults = array(		);				$instance = wp_parse_args($instance, $defaults);		extract($instance);				global $pmc_data;
		?>		<div class = "builder-social">			<?php pmc_socialLink(); ?>		</div>		<?php		
			}	}
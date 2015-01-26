<?php
class PMC_Prebuild_Contact extends AQ_Block {

	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Preb. Contact',
			'size' => 'span12',
			'resizable' => 0,
			'icon' => 'fa-envelope-o',
			'icon_color' => 'FFF',
			'category' => 'Prebuild',
			'help' => 'This is prebuild contact block with sidebar.',
			'resizable' => 0			
		);
		
		//create the block
		parent::__construct('pmc_prebuild_contact', $block_options);
		
	}
	

	function form($instance) {
	
		/*start block*/
		$start = new AQ_Start_Content_Block();

		/*start content block*/
		$start_content = new AQ_Title_Border_Block();
		
		/*contact*/
		$contact = new AQ_Contact_Block();	
		
		/*widget*/
		$widget = new AQ_Widgets_Block();
		
		
		$defaults = array(
			'sidebar' => 'contact',
			'color' => '#fff',
			'block_id' => rand(0,99999),
			'border_color' => '#ececec',
			'backgroundimage' => 'http://cherry.premiumcoding.com/wp-content/uploads/2013/10/contact-form-white-background.jpg',
			'paddingtop' => '100',
			'paddingbottom' => '100',
			'bordertopbottom' => '1',
			'id' => 'contact',
			'title_color' => '#2a2b2c',
			'show_border' => '1',		
			'title_h2' => 'DROP US A NOTE',
			'descriptiontext' => 'IN CASE YOU HAVE ANY INQUIRIES ABOUT OUR WORK OR ANYTHING ELSE, DO NOT HESITATE TO CONTACT US'			
			
			
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		pmc_inner_block($start,$instance,'Start block (here you can set block background, video ...)','fa-dot-circle-o'); 
		pmc_inner_block($start_content,$instance,'Start block with title (here you can set title with border and short desctiption)','fa-dot-circle-o'); 
		pmc_inner_block($contact,$instance,'Contact Block (here you can set which contact form is used)','fa-envelope'); 	
		pmc_inner_block($widget,$instance,'Sidebar Block (here you can set which sidebar is used)','fa-th'); 
		
	}
	
	function block($instance) {
	
		$defaults = array(
			'sidebar' => 'contact',
			'color' => '#fff',
			'block_id' => rand(0,99999),
			'border_color' => '#ececec',
			'backgroundimage' => 'http://cherry.premiumcoding.com/wp-content/uploads/2013/10/contact-form-white-background.jpg',
			'paddingtop' => '100',
			'paddingbottom' => '100',
			'bordertopbottom' => '1',
			'id' => 'contact',
			'title_color' => '#2a2b2c',
			'show_border' => '1',		
			'title_h2' => 'DROP US A NOTE',
			'descriptiontext' => 'IN CASE YOU HAVE ANY INQUIRIES ABOUT OUR WORK OR ANYTHING ELSE, DO NOT HESITATE TO CONTACT US'			
			
			
		);
		
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);	
	
		/*start block*/
		$start = new AQ_Start_Content_Block();	

		/*start content block*/
		$start_content = new AQ_Title_Border_Block();

		/*contact*/
		$contact = new AQ_Contact_Block();	
		
		/*widget*/
		$widget = new AQ_Widgets_Block();
		
		/*end content block*/
		$end_content = new AQ_Title_Border_Block_End();			
		
		/*end block*/
		$end = new AQ_End_Content_Block();	
		
		
		
		?>
		<div class="prebuild-contact aq-block aq-block-aq_start_content_block aq_span12 aq-first cf">
		<?php $start -> block($instance); ?>
		</div>	
		<div class="prebuild-contact aq-block aq-block-aq_title_border_block aq_span12 aq-first cf">
		<?php $start_content -> block($instance); ?>
		</div>			
		<div id="prebuild-contact aq-block-8672-67" class="aq-block aq-block-aq_clear_block aq_span12 aq-first cf"><div class="cf" style="height:50px; background:"></div></div>		
		<div class="aq-block aq-block-aq_contact_block aq_span8 aq-first cf">
		<?php $contact -> block($instance); ?>
		</div>
		<div class="prebuild-contact aq-block aq-block-aq_widgets_block aq_span4  cf">
		<?php $widget -> block($instance); ?>
		</div>
		<div class="prebuild-contact aq-block aq-block-aq_end_content_block aq_span12 aq-first cf">
		<?php $end_content -> block($instance); ?>
		</div>			
		<div class="prebuild-contact aq-block aq-block-aq_end_content_block aq_span12 aq-first cf">
		<?php $end -> block($instance); ?>
		</div>		
		<?php
	}
}
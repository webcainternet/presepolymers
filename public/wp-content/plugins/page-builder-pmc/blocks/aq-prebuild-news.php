<?php
class PMC_Prebuild_News extends AQ_Block {

	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Preb. News',
			'size' => 'span12',
			'resizable' => 0,
			'icon' => 'fa-pencil-square-o',
			'icon_color' => 'FFF',
			'category' => 'Prebuild',
			'help' => 'This is prebuild news block.',
			'resizable' => 0,	
		);
		
		//create the block
		parent::__construct('pmc_prebuild_news', $block_options);
		
	}
	

	function form($instance) {


		/*start content block*/
		$start_content = new AQ_Title_Border_Block();
		
		/*news*/
		$news = new AQ_Posts_Full_Width_Block();	
			
		$defaults = array(
			'block_id' => rand(0,99999),
			'show_border' => '1',		
			'title_color' => '#2a2b2c',
			'id' => 'news',
			'title_h2' => 'LATEST NEWS FROM OUR BLOG',
			'descriptiontext' => 'CHECK OUT THE LATEST NEWS FROM THE CHERRY VERSE'		

		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		pmc_inner_block($start_content,$instance,'Start block with title (here you can set title with border and short desctiption)','fa-dot-circle-o'); 
		pmc_inner_block($news,$instance,'News (here you can set which categories are used for news block)','fa-tasks'); 			

		
	}
	
	function block($instance) {
	
		$defaults = array(
			'block_id' => rand(0,99999),
			'show_border' => '1',		
			'title_color' => '#2a2b2c',
			'id' => 'news',
			'title_h2' => 'LATEST NEWS FROM OUR BLOG',
			'descriptiontext' => 'CHECK OUT THE LATEST NEWS FROM THE CHERRY VERSE'
		);
		
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);	
	
		/*start block*/
		$start = new AQ_Start_Content_Block();	

		/*start content block*/
		$start_content = new AQ_Title_Border_Block();

		/*news*/
		$news = new AQ_Posts_Full_Width_Block();	
		
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
		<div class="aq-block aq-block-aq_posts_full_width_block aq_span12 aq-first cf">
		<?php $news -> block($instance); ?>
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
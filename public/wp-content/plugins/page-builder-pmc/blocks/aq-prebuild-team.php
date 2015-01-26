<?php
class PMC_Prebuild_Team extends AQ_Block {
	private $title;
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Preb. Team',
			'size' => 'span12',
			'resizable' => 0,
			'icon' => 'fa-female',
			'icon_color' => 'FFF',
			'category' => 'Prebuild',
			'help' => 'This is prebuild team block with four team members.',
			'class' => 'block-aq_column_block prebuild-column-wrapper',
			'resizable' => 0,	
		);
		
		//create the block
		parent::__construct('pmc_prebuild_team', $block_options);		
		$this->title = new AQ_Title_Border_Block;
		
	}
	

	function form($instance) {
	
		$defaults = array(
			'block_id' => 'aq_block_1',
			'title_color' => '#2a2b2c',
			'show_border' => '1',		
			'title_h2' => 'OUR TEAM',
			'id' => 'team',
			'descriptiontext' => 'OUR SMALL AND CREATIVE TEAM WILL AMAZE YOU WITH PROFESSIONALISM AND FRESH IDEAS',							
		);
		
		$instance = wp_parse_args($instance, $defaults);		
		extract($instance);		
		
		?>	
		<?php pmc_inner_block($this->title,$instance,'Start block with title (here you can set title with border and short desctiption)','fa-dot-circle-o'); ?>
		<p class="empty-column prebuild-column-title">
			<?php echo __('Drag block items into this column box', 'pmc-themes') ; ?>
		</p>
		<ul class="blocks column-blocks cf ui-sortable prebuild-column"></ul>
		<?php
		
	}
	
	function form_callback($instance = array()) {

		$instance = is_array($instance) ? wp_parse_args($instance, $this->block_options) : $this->block_options;
		
		//insert the dynamic block_id & block_saving_id into the array
		$this->block_id = 'aq_block_' . $instance['number'];
		$instance['block_saving_id'] = 'aq_blocks[aq_block_'. $instance['number'] .']';
				
		extract($instance);
		
		if(isset($order)){
		$col_order = $order;
		}
		
		//column block header
		if(isset($template_id)) { 
		
		$defaults = array(
			'block_id' => 'aq_block_'.$number,	
			'title_color' => '#2a2b2c',
			'show_border' => '1',		
			'title_h2' => 'OUR TEAM',
			'id' => 'team',
			'descriptiontext' => 'OUR SMALL AND CREATIVE TEAM WILL AMAZE YOU WITH PROFESSIONALISM AND FRESH IDEAS',							
		);
		
		$instance = wp_parse_args($instance, $defaults);		
		extract($instance); ?>
		    
			<li id="template-block-<?php echo $number ?>" class="block block-aq_column_block prebuild-column-wrapper">
			<dl class="block-bar">
				<dt class="block-handle">
					<div class="block-title">
						<i class="fa fa-female " style="color:FFF"></i>Prebuild Team Block
					</div>
					<span class="block-controls">
						<a class="block-edit" id="edit-6" title="Edit Block" href="#block-settings-6">Edit Block</a>
					</span>
				</dt>
			</dl>
			<div class="block-settings cf" id="block-settings-<?php echo $number ?>">
			<?php pmc_inner_block($this->title,$instance,'Start block with title (here you can set title with border and short desctiption)','fa-dot-circle-o'); ?>
			<p class="empty-column prebuild-column-title">
				<?php echo __('Drag team blocks into this box', 'pmc-themes'); ?>
			</p>
			<ul class="blocks column-blocks cf prebuild-column">
			<?php
			
			//check if column has blocks inside it
			$blocks = aq_get_blocks($template_id);
			
			//outputs the blocks
			if($blocks) {
				foreach($blocks as $key => $child) {
					global $aq_registered_blocks;
					extract($child);
					
					//get the block object
					$block = $aq_registered_blocks[$id_base];
					
					if($parent == $col_order) {
						$block->form_callback($child);
					}
				}
			} 
			echo 		'</ul>';
			
		} else {
			$this->before_form($instance);
			$this->form($instance);
		}
				
		//form footer
		$this->after_form($instance);
	}
	
	//form footer
	function after_form($instance) {
	
		extract($instance);	
		
		$block_saving_id = 'aq_blocks[aq_block_'.$number.']';
			
		echo '<div class="block-control-actions cf"><a href="#" class="delete">Delete</a></div>';
		echo '<input type="hidden" class="id_base" name="'.$this->get_field_name('id_base').'" value="'.$id_base.'" />';
		echo '<input type="hidden" class="name" name="'.$this->get_field_name('name').'" value="'.$name.'" />';
		echo '<input type="hidden" class="order" name="'.$this->get_field_name('order').'" value="'.$order.'" />';
		echo '<input type="hidden" class="size" name="'.$this->get_field_name('size').'" value="'.$size.'" />';
		echo '<input type="hidden" class="parent" name="'.$this->get_field_name('parent').'" value="'.$parent.'" />';
		echo '<input type="hidden" class="number" name="'.$this->get_field_name('number').'" value="'.$number.'" />';
		echo '</div>',
			'</li>';
	}
	
	function block_callback($instance) {
		$instance = is_array($instance) ? wp_parse_args($instance, $this->block_options) : $this->block_options;

		$defaults = array(
			'block_id' => 'aq_block_1',
			'title_color' => '#2a2b2c',
			'show_border' => '1',		
			'title_h2' => 'OUR TEAM',
			'id' => 'team',
			'descriptiontext' => 'OUR SMALL AND CREATIVE TEAM WILL AMAZE YOU WITH PROFESSIONALISM AND FRESH IDEAS',							
		);
		
		$instance = wp_parse_args($instance, $defaults);
		
		extract($instance);
		
		$col_order = $order;
		$col_size = absint(preg_replace("/[^0-9]/", '', $size));

		/*start block*/
		$start = new AQ_Start_Content_Block();	

		
		/*end content block*/
		$end_content = new AQ_Title_Border_Block_End();			
		
		/*end block*/
		$end = new AQ_End_Content_Block();	
		
		//column block header	
		if(isset($template_id)) { ?>
			
			<div class="prebuild-team aq-block aq-block-aq_start_content_block aq_span12 aq-first cf">
			<?php $start -> block($instance); ?>
			</div>	
			<div class="prebuild-team aq-block aq-block-aq_title_border_block aq_span12 aq-first cf">
			<?php $this->title -> block($instance); ?>
			</div>					
			<div id="prebuild-team aq-block-8672-67" class="aq-block aq-block-aq_clear_block aq_span12 aq-first cf"><div class="cf" style="height:50px; background:"></div></div>
			<?php		
			
			$this->before_block($instance);
			
			//define vars
			$overgrid = 0; $span = 0; $first = false;
			
			//check if column has blocks inside it
			$blocks = aq_get_blocks($template_id); 
			
			//outputs the blocks
			if($blocks) {
				foreach($blocks as $key => $child) {
					global $aq_registered_blocks;
					extract($child);
					
					if(class_exists($id_base)) {
						//get the block object
						$block = $aq_registered_blocks[$id_base];
						
						//insert template_id into $child
						$child['template_id'] = $template_id;
						
						//display the block
						if($parent == $col_order) {
							
							$child_col_size = absint(preg_replace("/[^0-9]/", '', $size));
							
							$overgrid = $span + $child_col_size;
							
							if($overgrid > $col_size || $span == $col_size || $span == 0) {
								$span = 0;
								$first = true;
							}
							
							if($first == true) {
								$child['first'] = true;
							}
							
							$block->block_callback($child);
							
							$span = $span + $child_col_size;
							
							$overgrid = 0; //reset $overgrid
							$first = false; //reset $first
						}
					}
				}
			} 
			
			$this->after_block($instance); ?>
			
			<div class="prebuild-team aq-block aq-block-aq_end_content_block aq_span12 aq-first cf">
			<?php $end_content -> block($instance); ?>
			</div>			
			<div class="prebuild-team aq-block aq-block-aq_end_content_block aq_span12 aq-first cf">
			<?php $end -> block($instance); ?>
			</div>			
			
			<?php
			
		} else {
			//show nothing
		}
	}	
}
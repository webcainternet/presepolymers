<?php
/** Testimonial Block **/
class AQ_Testimonial_Block extends AQ_Block {
	function __construct() {
		$block_options = array(
			'name'			=> 'Testimonials',
			'size'			=> 'span3',
			'icon' => 'fa-comments',
			'icon_color' => 'FFF',
			'category' => 'Content',
			'help' => 'Testimonial block allows you to add quotes from your customers.'				
		);
		
		parent::__construct('aq_testimonial_block', $block_options);
		
		add_action('wp_ajax_aq_block_testimonial_add_new', array($this, 'add_testimonial'));
	}
	
	function form($instance) {
		$defaults = array(
			'speed' => 6000,
			'testimonials'		=> array(
				1 => array(
					'author' => 'My New Testimonial Author',
					'link' => '',
					'text' => '',
					'show_author_on_top' => '0'
				)
			)
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		
		<div class="description">
			<label for="<?php echo $this->get_field_id('speed') ?>">
				Slider pause time (6000 = 6s)<br/>
			<?php echo aq_field_input('speed', $block_id, $speed) ?>
			</label>
			<br>
			<label for="<?php echo $this->get_field_id('show_author_on_top') ?>">				
				<?php echo aq_field_checkbox('show_author_on_top', $block_id, $show_author_on_top); ?>				
				Show Author on top?			
			</label>				
		</div>	
		<br>
		<div class="description cf">
			<ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>" alt = "testimonials">
				<?php
				$testimonials = is_array($testimonials) ? $testimonials : $defaults['testimonials'];
				$count = 1;
				foreach($testimonials as $testimonial) {	
					$this->testimonial($testimonial, $count, $block_id);
					$count++;
				}
				?>
			</ul>
			<p></p>
			<a href="#" rel="testimonial" class="aq-sortable-add-new button">Add New</a>
			<p></p>
		</div>
		<?php
	}
	
	function testimonial($testimonial = array(), $count = 0, $block_id) {
		$defaults = array (
			'author' => '',
			'link' => '',
			'text' => '',	
		);
		$testimonial = wp_parse_args($testimonial, $defaults);		
		?>
		<li id="<?php echo $this->get_field_id('testimonials') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">
		
			
			<div class="sortable-head cf">
				<div class="sortable-title">
					<strong><?php echo $testimonial['author'] ?></strong>
				</div>
				<div class="sortable-handle">
					<a href="#">Open / Close</a>
				</div>
			</div>

			<div class="sortable-body">
				<p class="description">
					<label for="<?php echo $this->get_field_id('testimonials') ?>-<?php echo $count ?>-author">
						Author<br/>
						<input type="text" id="<?php echo $this->get_field_id('testimonials') ?>-<?php echo $count ?>-author" class="input-full" name="<?php echo $this->get_field_name('testimonials') ?>[<?php echo $count ?>][author]" value="<?php echo $testimonial['author'] ?>" />
					</label>
				</p>				
				<p class="description">
					<label for="<?php echo $this->get_field_id('testimonials') ?>-<?php echo $count ?>-link">
						Link<br/>
						<input type="text" id="<?php echo $this->get_field_id('testimonials') ?>-<?php echo $count ?>-link" class="input-full" name="<?php echo $this->get_field_name('testimonials') ?>[<?php echo $count ?>][link]" value="<?php echo $testimonial['link'] ?>" />
					</label>
				</p>
				<p class="description">
					<label for="<?php echo $this->get_field_id('testimonials') ?>-<?php echo $count ?>-text">
						Testimonial Text<br/>
						<textarea id="<?php echo $block_id ?>_text-<?php echo $count ?>-text" class="textarea-full" name="aq_blocks[<?php echo $block_id ?>][testimonials][<?php echo $count ?>][text]" rows="5"><?php echo $testimonial['text'] ?></textarea>
					</label>
				</p>
				<p class="description"><a href="#" class="sortable-delete">Delete</a></p>
			</div>
			
		</li>
		<?php
	}
	
	function add_testimonial() {
		$nonce = $_POST['security'];	
		if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');
		
		$count = isset($_POST['count']) ? absint($_POST['count']) : false;
		$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
		
		//default key/value for the tab
		$testimonial = array(
			'author' => 'My New Testimonial Author',
			'link' => '',
			'text' => ''
		);
		
		if($count) {
			$this->testimonial($testimonial, $count);
		} else {
			die(-1);
		}
		
		die();
	}
	
	function block($instance) {
		$defaults = array(
			'speed' => '6000',				
			'testimonials'		=> array(
				1 => array(
					'author' => 'My New Testimonial Author',
					'link' => '',
					'text' => '',
					'show_author_on_top' => '0'
				)
			)		
		);
					
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		wp_enqueue_script('pmc_bxSlider');
		
		$count = count($testimonials);
		$i = 1;
		
		?>
		<script>
			jQuery(document).ready(function(){	  
			// Slider
			var $slider = jQuery(".slides-testimonials").bxSlider({
				controls: true,
				displaySlideQty: 1,
				pause: <?php echo $speed ?>,
				speed:900,
				auto:true,
				easing : "ease-in-out",
				prevText : "<i class='fa fa-angle-left'></i> ",
				nextText : "<i class='fa fa-angle-right'></i> ",
				pager :false
			});
			 });
		</script>				
		<div id="testimonials_<?php echo rand(1,100) ?>" class="testimonials cf">
			<ul class="slides-testimonials">
			
			<?php foreach ($testimonials as $testimonial) {	
			
					
				$defaults = array (
					'author' => '',
					'link' => '',
					'text' => '',		
					'show_author_on_top' => '0'
				);
				$testimonial = wp_parse_args($testimonial, $defaults);
				
				$hide = $i > 1 ? 'hide' : ''; $i++;
				
				if(!empty($testimonial['author']) && !empty($testimonial['text'])) {
					
					if(!empty($testimonial['link'])) {
						$author = '<a href="'.$testimonial['link'].'"><span class="author">'. do_shortcode(htmlspecialchars_decode($testimonial['author'])).'</span></a>';
					} else {
						$author = '<span class="author">'. $testimonial['author'].'</span>';
					}
				
				
					?>
					
					<li class="testimonial <?php echo $hide ?>">
					<div class="testimonial-before"></div>
						<div class="testimonial-description">
						<?php if($show_author_on_top == 1){ ?>
							<div class="testimonial-author">
								<?php echo $author ?>
							</div>
							<?php } ?>						
							<div class="testimonial-texts">
								<i class="fa fa-quote-right"></i><?php echo do_shortcode(htmlspecialchars_decode($testimonial['text'])) ?><i class="fa fa-quote-right"></i>
							</div>
							<?php if($show_author_on_top == 0){ ?>
							<div class="testimonial-author">
								<?php echo $author ?>
							</div>
							<?php } ?>
							
							
						</div>	
					<div class="testimonial-after"></div>
					</li>
					<?php
				}
			} ?>
			
			</ul>
			
		</div>
		
		<?php
		
	}
	
	function update($new_instance, $old_instance) {
		$new_instance = aq_recursive_sanitize($new_instance);
		return $new_instance;
	}	
	
}

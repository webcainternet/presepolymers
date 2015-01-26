<?php
/** "News" block 
 * 
 * Optional to use horizontal lines/images
**/
class AQ_Product_Block_Feed extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Recent product',
			'size' => 'span12',
			'resizable' => 0,
			'categories_port' => array(),
			'icon' => 'fa-shopping-cart',
			'icon_color' => 'FFF',
			'category' => 'Ecommerce',
			'help' => 'Block that adds recent products to the page.'
			
		);
		
		//create the block
		parent::__construct('aq_product_block_feed', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'number_post' => '',
			'rowsB' => '',
			'categories_port' => '',
			'port_text' => '',
			'product_ajax' => 'false',
			'filter' => 'false',
			'id' => 'products'
		);
		
		$ajax_options = array(
			'true' => 'True',
			'false' => 'False',
		);
		

		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		if (function_exists( 'is_woocommerce' ) ) {
			$port_categories = ($temp = get_terms('product_cat')) ? $temp : array();
			$categories_options = array();
			foreach($port_categories as $cat) {
				$categories_options[$cat->term_id] = $cat->name;
			}		
		}
		if( function_exists( 'pmc_productBlock' ) ){	
		?>
		<p class="description note">
			<?php _e('Use this block to create news feed.', 'framework') ?>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('categories_port') ?>">
			Product Categories<br/>
			<?php echo aq_field_multiselect('categories_port', $block_id, $categories_options, $categories_port); ?>
			</label>
		</p>		
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('number_post') ?>">
				Number of product to show (optional)- if you leave empty theme admin settings will be used
				<?php echo aq_field_input('number_post', $block_id, $number_post, $size = 'full') ?>
			</label>		
		<p class="description half">
			<label for="<?php echo $this->get_field_id('product_ajax') ?>">
				Use ajax<br/>
				<?php echo aq_field_select('product_ajax', $block_id, $ajax_options, $product_ajax); ?>
			</label>
		</p>	
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('filter') ?>">
				Show sorting filter<br/>
				<?php echo aq_field_select('filter', $block_id, $ajax_options, $filter); ?>
			</label>
		</p>		
		<p class="description half">
			<label for="<?php echo $this->get_field_id('id') ?>">
				Block ID
				<?php echo aq_field_input('id', $block_id, $id, $size = 'full') ?>
			</label>
		</p>			

		<?php
		}
		else {
			echo '<p class="description note">For this block you need to use PremiumCoding themes!</p>';
		}		
	}
	
	function block($instance) {
		$defaults = array(
			'number_post' => '',
			'rowsB' => '',
			'categories_port' => '',
			'port_text' => '',
			'product_ajax' => 'false',
			'filter' => 'false',
			'id' => 'products'
		);
		

		$instance = wp_parse_args($instance, $defaults);	
		extract($instance);
		if( function_exists( 'pmc_productBlock' ) ){	
		$rand = rand(0,100);
		if($filter == "true" && count($categories_port) > 1){ ?>				
			<div id="<?php if(isset($id)){ echo $id; }?>">
				<div id="remove-<?php echo $rand ?>" class="remove portfolioremove" data-option-key="filter">
					<h2>
					<a class="catlink" href="#filter" data-option-value="*">Show All <span> </span></a>
					<?php
					foreach ($categories_port as $category) {

					$find =     array("&", "/", " ","amp;","&#38;");
					$replace  = array("", "", "", "","");
					$entrycategory = str_replace($find , $replace, pmc_getcatname($category,'product_cat'));
						echo '<a class="catlink" href="#filter" data-option-value=".'.$entrycategory .'" >'.pmc_getcatname($category,'product_cat').' <span class="aftersortingword"> </span></a>';
					}
					?>
					</h2>
				</div>
			</div>
		<?php }	
			if(count($categories_port) > 0){
				pmc_productBlock($title,$number_post,$rowsB,$categories_port,$port_text,'recent',$product_ajax,$rand);
			} 
		?>	
		<script>
		jQuery(function(){
		  
		  var $container = jQuery('.productR-<?php echo $rand ?>');

		  $container.isotope({
			itemSelector : '.one_fourth-<?php echo $rand ?>'
			<?php if (isset($categories_port_selected) and $categories_port_selected != -1 ){
				  $cat = pmc_getcatname($categories_port_selected,'portfoliocategory'); ?>
			,filter : '.<?php echo $cat ?>'
			<?php } ?>
		  });
		  
		  
		  var $optionSets = jQuery('#remove-<?php echo $rand ?>'),
			  $optionLinks = $optionSets.find('a');

		  $optionLinks.click(function(){
			var $this = jQuery(this);
			// don't proceed if already selected
			if ( $this.hasClass('selected') ) {
			  return false;
			}
			var $optionSet = $this.parents('#remove-<?php echo $rand ?>');
			$optionSet.find('.selected').removeClass('selected');
			$this.addClass('selected');

			// make option object dynamically, i.e. { filter: '.my-filter-class' }
			var options = {},
				key = $optionSet.attr('data-option-key'),
				value = $this.attr('data-option-value');
			// parse 'false' as false boolean
			value = value === 'false' ? false : value;
			options[ key ] = value;
			if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
			  // changes in layout modes need extra logic
			  changeLayoutMode( $this, options )
			} else {
			  // otherwise, apply new options
			  $container.isotope( options );
			}
			
			return false;
		  });

		  
		});
		</script>		
		<?PHP
		}
	
	}
	
	function update($new_instance, $old_instance) {
		$new_instance = aq_recursive_sanitize($new_instance);
		return $new_instance;
	}

}
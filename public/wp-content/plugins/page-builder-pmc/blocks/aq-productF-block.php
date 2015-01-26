<?php

class AQ_ProductF_Block_Feed extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Feat. product',
			'size' => 'span12',
			'resizable' => 0,
			'icon' => 'fa-shopping-cart',
			'icon_color' => 'FFF',
			'category' => 'Ecommerce',
			'help' => 'Block that adds featured products to the page.'			
		);
		
		//create the block
		parent::__construct('aq_productf_block_feed', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'number_product' => '4',
			'rows_product' => '4',
			'product_order' => 'desc',
			'product_sort_by' => 'date',	
		);
		
		$order_options = array(
			'asc' => 'ASC',
			'desc' => 'DESC',
		);
		
		$sort_by_options = array(
			'date' => 'Date',
			'title' => 'Title',
		
		);

		$product_column_options = array(
			'1' => 'One column',
			'2' => 'Two columns',
			'3' => 'Three columns',
			'4' => 'Four columns',			
			'5' => 'Five columns',
			'6' => 'Six columns',			
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
		?>
		<p class="description note">
			<?php _e('Use this block to create featured product  block.', 'pmc-themes') ?>
		</p>	
		<p class="description half ">			
			<label for="<?php echo $this->get_field_id('number_product') ?>" style="width:100%;float: left;margin-bottom: 10px;">
				Number of product to show<br>
				<?php echo aq_field_input('number_product', $block_id, $number_product, $size = 'big') ?>
			</label>
			<label for="<?php echo $this->get_field_id('product_order') ?>">
				Number of columns<br/>
				<?php echo aq_field_select('rows_product', $block_id, $product_column_options, $rows_product); ?>
			</label>	
		</p>		
		<p class="description half last">		
			<label for="<?php echo $this->get_field_id('product_order') ?>">
				Products order<br/>
				<?php echo aq_field_select('product_order', $block_id, $order_options, $product_order); ?>
			</label>				
			<label for="<?php echo $this->get_field_id('product_sort_by') ?>" style="width:100%;float: left;margin-bottom: 10px;">
				Products order<br/>
				<?php echo aq_field_select('product_sort_by', $block_id, $sort_by_options, $product_sort_by); ?>
			</label>				
		</p>			

		<?php
	
	}

	
	
	function block($instance) {
		$defaults = array(
			'number_product' => '4',
			'rows_product' => '4',
			'product_order' => 'desc',
			'product_sort_by' => 'date',	
		);
		

		$instance = wp_parse_args($instance, $defaults);	
		extract($instance);
		
		echo do_shortcode( '[featured_products per_page="'.$number_product.'" columns="'.$rows_product.'" product_order="'.$product_order.'" product_sort_by="'.$product_sort_by.'"]' );
	}
	
	function update($new_instance, $old_instance) {
		$new_instance = aq_recursive_sanitize($new_instance);
		return $new_instance;
	}

}
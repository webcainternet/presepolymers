<?php

class AQ_Product_Category_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Shop page',
			'size' => 'span12',
			'resizable' => 1,
			'icon' => 'fa-shopping-cart',
			'icon_color' => 'FFF',
			'category' => 'Ecommerce',
			'help' => 'Block that adds products to the page.'
			
		);
		
		//create the block
		parent::__construct('aq_product_category_block', $block_options);
	}
	
	function form($instance) {
		$product_categories_default = ($temp = get_terms('product_cat')) ? $temp : array();
		$product_options_default = array();
		$i = 0;
		foreach($product_categories_default as $cat_default) {
			$product_options_default[$i++] = $cat_default->term_id;
		}	
				
		$defaults = array(
			'number_product' => '12',
			'rows_product' => '4',
			'product_order' => 'desc',
			'product_sort_by' => 'date',			
			'categories_product' => $product_options_default,
			'show_sorting' => 1,
			'show_navigation' => 1,
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
		if (pmc_woo() && PMC_SHOP){ 
			$product_categories = ($temp = get_terms('product_cat')) ? $temp : array();
			$categories_options = array();
			foreach($product_categories as $cat) {
				$categories_options[$cat->term_id] = $cat->name;
			}

			?>
			<p class="description note">
				<?php _e('Use this block to create product block.', 'pmc-themes') ?>
			</p>	
			<p class="description half ">
				<label for="<?php echo $this->get_field_id('categories_product') ?>" style="width:100%;float: left;margin-bottom: 10px;">
				Product Category<br/>
				<?php echo aq_field_multiselect('categories_product', $block_id, $categories_options, $categories_product); ?>
				</label>			
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
				<label for="<?php echo $this->get_field_id('show_navigation') ?>" style="width:100%;float: left;margin-bottom: 10px;">
					<?php echo aq_field_checkbox('show_navigation', $block_id, $show_navigation); ?>
					Show navigation?
				</label>
				<label for="<?php echo $this->get_field_id('show_sorting') ?>" style="width:100%;float: left;margin-bottom: 10px;">
					<?php echo aq_field_checkbox('show_sorting', $block_id, $show_sorting); ?>
					Show sorting?
				</label>				
				
			</p>		
			

			<?php
		}		
	}
	
	function block($instance) {
		
		if (pmc_woo() && PMC_SHOP){ 
		
			
			$defaults = array(
				'number_product' => '12',
				'rows_product' => '4',
				'product_order' => 'desc',
				'product_sort_by' => 'date',			
				'categories_product' => '',
				'show_sorting' => 1,
				'show_navigation' => 1,
			);
			

			$instance = wp_parse_args($instance, $defaults);	
			extract($instance);
			

			$product_categories_default = ($temp = get_terms('product_cat')) ? $temp : array();
			$product_options_default = array();
			$i = 0;
			foreach($product_categories_default as $cat_default) {
				$product_options_default[$i++] = $cat_default->term_id;
			}			

			If(empty($categories_product)){	
				$categories_product = $product_options_default;
			}			

			$isinarray = true;
			foreach($categories_product as $cat_saved){
				
				if(array_search($cat_saved,$product_options_default) === false){
						$isinarray = false;
					}
				if($isinarray == false){
					$categories_product = null;
					break; 
				}
				
			}
			
			if(empty($categories_product)){
				$categories_product = $product_options_default;
			}			
			
			$filtered_posts = '';
			?>
			
			<?php 
			if(!defined('SHOP_ROWS')) define( 'SHOP_ROWS', intval($rows_product) );
			/* set number of rows*/
			add_filter('loop_shop_columns', 'loop_columns');
			if (!function_exists('loop_columns')) {
				function loop_columns() {
					return SHOP_ROWS; 
				}
			}
			if(get_query_var('orderby')){
				if(get_query_var('orderby') == 'price-desc' || get_query_var('orderby') == 'price'){
					$order_by = 'meta_value_num';
					$meta_key ='_'.get_query_var('orderby');
					if(get_query_var('orderby') == 'price-desc'){
						$order_by = 'meta_value_num';
						$meta_key = '_price';
						$product_order ='DESC';				
					}				
				}
			}
			else{
				$order_by = 'product_sort_by';
				$meta_key = '';
			}
			global $wpdb;
			if ( isset( $_GET['max_price'] ) && isset( $_GET['min_price'] ) ) {
			
				$matched_products = array();
				$min 	= floatval( $_GET['min_price'] );
				$max 	= floatval( $_GET['max_price'] );

				$matched_products_query = apply_filters( 'woocommerce_price_filter_results', $wpdb->get_results( $wpdb->prepare("
					SELECT DISTINCT ID, post_parent, post_type FROM $wpdb->posts
					INNER JOIN $wpdb->postmeta ON ID = post_id
					WHERE post_type IN ( 'product', 'product_variation' ) AND post_status = 'publish' AND meta_key = %s AND meta_value BETWEEN %d AND %d
				", '_price', $min, $max ), OBJECT_K ), $min, $max );

				if ( $matched_products_query ) {
					foreach ( $matched_products_query as $product ) {
						if ( $product->post_type == 'product' )
							$matched_products[] = $product->ID;
						if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) )
							$matched_products[] = $product->post_parent;
					}
				}

				// Filter the id's
				if ( sizeof( $filtered_posts ) == 0) {
					$filtered_posts = $matched_products;
					$filtered_posts[] = 0;
				} else {
					$filtered_posts = array_intersect( $filtered_posts, $matched_products );
					$filtered_posts[] = 0;
				}

			}
			

		
			global $_chosen_attributes;

			if ( sizeof( $_chosen_attributes ) > 0 ) {

				$matched_products   = array(
					'and' => array(),
					'or'  => array()
				);
				$filtered_attribute = array(
					'and' => false,
					'or'  => false
				);

				foreach ( $_chosen_attributes as $attribute => $data ) {
					$matched_products_from_attribute = array();
					$filtered = false;

					if ( sizeof( $data['terms'] ) > 0 ) {
						foreach ( $data['terms'] as $value ) {

							$posts = get_posts(
								array(
									'post_type' 	=> 'product',
									'numberposts' 	=> -1,
									'post_status' 	=> 'publish',
									'fields' 		=> 'ids',
									'no_found_rows' => true,
									'tax_query' => array(
										array(
											'taxonomy' 	=> $attribute,
											'terms' 	=> $value,
											'field' 	=> 'term_id'
										)
									)
								)
							);

							if ( ! is_wp_error( $posts ) ) {

								if ( sizeof( $matched_products_from_attribute ) > 0 || $filtered )
									$matched_products_from_attribute = $data['query_type'] == 'or' ? array_merge( $posts, $matched_products_from_attribute ) : array_intersect( $posts, $matched_products_from_attribute );
								else
									$matched_products_from_attribute = $posts;

								$filtered = true;
							}
						}
					}

					if ( sizeof( $matched_products[ $data['query_type'] ] ) > 0 || $filtered_attribute[ $data['query_type'] ] === true ) {
						$matched_products[ $data['query_type'] ] = ( $data['query_type'] == 'or' ) ? array_merge( $matched_products_from_attribute, $matched_products[ $data['query_type'] ] ) : array_intersect( $matched_products_from_attribute, $matched_products[ $data['query_type'] ] );
					} else {
						$matched_products[ $data['query_type'] ] = $matched_products_from_attribute;
					}

					$filtered_attribute[ $data['query_type'] ] = true;

					$this->filtered_product_ids_for_taxonomy[ $attribute ] = $matched_products_from_attribute;
				}

				// Combine our AND and OR result sets
				if ( $filtered_attribute['and'] && $filtered_attribute['or'] )
					$results = array_intersect( $matched_products[ 'and' ], $matched_products[ 'or' ] );
				else
					$results = array_merge( $matched_products[ 'and' ], $matched_products[ 'or' ] );

				if ( $filtered ) {

					WC()->query->layered_nav_post__in   = $results;
					WC()->query->layered_nav_post__in[] = 0;

					if ( sizeof( $filtered_posts ) == 0 ) {
						$filtered_posts   = $results;
						$filtered_posts[] = 0;
					} else {
						$filtered_posts   = array_intersect( $filtered_posts, $results );
						$filtered_posts[] = 0;
					}

				}
			}		
				
			?>
			
			<div class="woocommerce columns-<?php echo $rows_product ?>">
			<?php
			global $wp_query;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args = array('post_type' => 'product',
					'tax_query' => array( 
						 array (
						  'taxonomy' => 'product_cat',
						  'field' => 'id',
						  'terms' => $categories_product
						 ), 
						 
					 ),
					'order' => $product_order,
					'orderby' => $order_by,
					'meta_key' => $meta_key,					
					'showposts'     => $number_product,
					'paged'    => $paged,
					'post__in' => $filtered_posts);

			query_posts($args);	 


		
	

			//new WC_Query();
				 /*
				$query = new WP_Query( array ( 'post_type' => 'product', 'orderby' => 'meta_value', 'meta_key' => 'price' ) );
				 
				$args['meta_key'] = '_price';
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'desc'; 
				return $args;	*/		 		

			?>
			<?php if ( have_posts() ) : ?>

				<?php
					if($show_sorting){
						do_action( 'woocommerce_before_shop_loop' );
					}
				?>

				<?php woocommerce_product_loop_start(); ?>

					<?php while ( have_posts() ) :  the_post(); ?>

						<?php wc_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>
				
				<?php	
					if($show_navigation){
						do_action( 'woocommerce_after_shop_loop' );
					}
				?>				
			
			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

				<?php wc_get_template( 'loop/no-products-found.php' ); ?>


			<?php endif; wp_reset_query();?>
			</div>

		<?php
		}
	}
	
	function update($new_instance, $old_instance) {
		$new_instance = aq_recursive_sanitize($new_instance);
		return $new_instance;
	}

}
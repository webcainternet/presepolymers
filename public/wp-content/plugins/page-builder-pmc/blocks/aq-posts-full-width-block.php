<?php
/** 
 * Posts Block
 * List posts by category/tags/post_format
 * Orderby latest
 * @todo - allow featured images, layout options, post formats(currently post tags offer similar functionality)
*/
if(!class_exists('AQ_Posts_Full_Width_Block')) {
	class AQ_Posts_Full_Width_Block extends AQ_Block {
		
		function __construct() {
			$block_options = array(
				'name' => 'News (Blog)',
				'size' => 'span12',
				'resizable' => '0',
				'postnum' => 2,
				'page' => false,
				'excerpt' => '',
				'post_ajax' => 'false',
				'icon' => 'fa-tasks',
				'icon_color' => 'FFF',
				'category' => 'Content',
				'help' => 'This is the block that adds recent news (blog) to the template.'
			);
			
			parent::__construct('aq_posts_full_width_block', $block_options);
			add_filter('excerpt_more', array(&$this, 'excerpt_more'));

						
		}
		
		function form($instance) {
		
	
			$defaults = array(
				'name' => 'Blog Posts full width',
				'size' => 'span12',
				'resizable' => '0',
				'categories' => null,
				'tags' => array(),
				'postnum' => 10,
				'page' => false,
				'excerpt' => '',
				'post_ajax' => 'false'
			);
			
			$ajax_options = array(
				'true' => 'True',
				'false' => 'False',
			);
			
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);

			$categories_default = ($temp = get_terms('category')) ? $temp : array();
			$categories_options_default = array();
			$i = 0;
			foreach($categories_default as $cat_default) {
				$categories_options_default[$i++] = $cat_default->term_id;
			}	

			If(empty($categories)){	
				$categories = $categories_options_default;
			}			

			$isinarray = true;
			foreach($categories as $cat_saved){
				
				if(array_search($cat_saved,$categories_options_default) === false){
						$isinarray = false;
					}
				if($isinarray == false){
					$categories = null;
					break; 
				}
				
			}

			if(empty($categories)){
				$categories = $categories_options_default;
			}
		
		
			$post_categories = ($temp = get_terms('category')) ? $temp : array();
			$categories_options = array();
			foreach($post_categories as $cat) {
				$categories_options[$cat->term_id] = $cat->name;
			}
			
			$post_tags = ($temp = get_terms('post_tag')) ? $temp : array();
			$tags_options = array();
			foreach($post_tags as $tag) {
				$tags_options[$tag->term_id] = $tag->name;
			}
			
			$page_options = array(0 => "Select a page:");
			$pages_obj = get_pages('sort_column=post_parent,menu_order');    
			foreach ($pages_obj as $page_obj) {
				$page_options[$page_obj->ID] = $page_obj->post_title; 
			}
				
			
			?>
			<p class="description half">
				<label for="<?php echo $this->get_field_id('categories') ?>">
				Posts Categories (leave empty to display all)<br/>
				<?php echo aq_field_multiselect('categories', $block_id, $categories_options, $categories); ?>
				</label>
			</p>
			<p class="description half last">
				<label for="<?php echo $this->get_field_id('types') ?>">
				Posts Tags (leave empty to display all)<br/>
				<?php echo aq_field_multiselect('tags', $block_id, $tags_options, $tags); ?>
				</label>
			</p>
			

			<?php
			
		}
		
		function block($instance) {
		
			
			$defaults = array(
				'name' => 'Blog Posts full width',
				'size' => 'span12',
				'resizable' => '0',
				'tags' => array(),
				'postnum' => 10,
				'page' => false,
				'excerpt' => '',
				'post_ajax' => 'false',
				'categories' => null,				

			);
			
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			$categories_default = ($temp = get_terms('category')) ? $temp : array();
			$categories_options_default = array();
			$i = 0;
			foreach($categories_default as $cat_default) {
				$categories_options_default[$i++] = $cat_default->term_id;
			}	

			If(empty($categories)){	
				$categories = $categories_options_default;
			}			

			$isinarray = true;
			foreach($categories as $cat_saved){
				
				if(array_search($cat_saved,$categories_options_default) === false){
						$isinarray = false;
					}
				if($isinarray == false){
					$categories = null;
					break; 
				}
				
			}

			if(empty($categories)){
				$categories = $categories_options_default;
			}

			
			wp_enqueue_script('pmc_bxSlider');


			?>
			<script type="text/javascript">


				jQuery(document).ready(function(){	  


				// Slider
				var $slider = jQuery('.sliderPostBlock').bxSlider({
					controls: true,
					displaySlideQty: 1,
					speed: 1000,
					touchEnabled: false,
					easing : 'easeInOutQuint',
					prevText : '<i class="fa fa-angle-left"></i>',
					nextText : '<i class="fa fa-angle-right"></i>',
					pager :false
					
				});



				 });
			</script>			
			
			<?php
			$isinarray = true;
			foreach($categories as $cat_saved){
				
				if(array_search($cat_saved,$categories_options_default) == ''){
						$isinarray = false;
					}
				if($isinarray == false){
					$categories = null;
					break; 
				}
				
			}
			if(!pmc_is_array_empty($categories)){
				$categories = $categories_options_default;
			}				

			$args = array();
			if($postnum) $args['posts_per_page'] = 10;
			if($categories) $args['category__in'] = $categories;
			if($tags) $args['tag__in'] = $tags;
			
			query_posts($args);
			$count = 1;
			$countitem = 1;
			$type = 'post';
			echo '<div class="aq-posts-block">';
				echo '<div class="post-full-width">';

						
						echo '<ul class="sliderPostBlock">';
						if (have_posts()) : while (have_posts()) : the_post();
						$videoPost = get_post_custom(get_the_id());
						$post_icon = '<div class="post-icon"><i class="fa fa-pencil"></i></div>';
						if ( has_post_format( 'gallery' , get_the_id())) {
							$post_icon = '<div class="post-icon"><i class="fa fa-picture-o"></i></div>';
						}
						if ( has_post_format( 'video' , get_the_id())) {
							$post_icon = '<div class="post-icon"><i class="fa fa-video-camera"></i></div>';
						}
						if ( has_post_format( 'audio' , get_the_id())) {
							$post_icon = '<div class="post-icon"><i class="fa fa-microphone"></i></div>';
						}						
						if(!has_post_format( 'link' , get_the_id())){
							global $post;
							if ( has_post_thumbnail() ){
								$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'postBlock', false);
								$image = $image[0];}
							if($countitem == 1)	
							echo '<li class="'.implode(' ', get_post_class()).' cf">';
								if($count != 3){
									echo '<div class="one_third" >';
								}
								else{
									echo '<div class="one_third last" >';
									$count = 0;
								}

								echo '<div class="imgholder home-post">';
								
								echo '<div class="recentimage">
										
										<div class="image">
											<div class="loading"></div>';
										if ( has_post_format( 'video' , get_the_id())) {
										
											$video_arg  = '';

											$video = wp_oembed_get( $videoPost["video_post_url"][0], $video_arg );		

											$video = preg_replace('/width=\"(\d)*\"/', 'width="370"', $video);			

											$video = preg_replace('/height=\"(\d)*\"/', 'height="260"', $video);	

											echo $video;											
										
										} else {
										echo '<a href="'.get_permalink( get_the_id() ) .'">';							
											if (has_post_thumbnail( get_the_ID() ) and $image != '') 
												echo '<img src = "'.$image.'" alt = "'.esc_attr(get_the_title() ? get_the_title() : get_the_ID()).'" width="370px" height="260px" > ' ;		
										echo '</a>';
										}
								echo '	</div>
									</div>';
								echo '</div>';
								 
								echo $post_icon;
								echo '<h3 class="the-title">';
								echo '<a href="'.get_permalink( get_the_id() ) .'">';									
								the_title();
								echo '</a>';	
								echo '</h3>';
								echo ' <a href="';
								$arc_month = get_the_time('m'); 
								$arc_month_text = get_the_time('F'); 
								$arc_day = get_the_time('j');
								$arc_day_suffix = get_the_time('S');
								$arc_year = get_the_time('Y');
								echo get_day_link($arc_year, $arc_month, $arc_day).'">
								<div class="date-post"> '. $arc_month_text  .' '. get_the_time('j') . ''. $arc_day_suffix  .', '. $arc_year  .' 		
								</a>';
								echo ' / ';
								echo _e('by ', 'pmc-themes');
									 the_author_posts_link();
								echo '</div>';
												
								echo '<div class="recentdescription home-post">';	
								
								echo '<div class="lineseparator"></div>';							
								echo '<div class="the_excerpt">'. substr(strip_tags(get_the_excerpt()), 0, 120) .'...</div>';
								
								echo '<div class="post-read-more">';
								echo '<div class="recentdescription-text">';

								echo '<a href="'.get_permalink( get_the_id() ) .'">';
									echo 'Read more</a>';
								echo '</div>';
								echo '</div>';
								
							 
							echo '</div>';
							$count++;
							if($countitem == 3) {
								$countitem = 0;
								echo '</div></li>';
							}
							else{
								echo '</div>';
							}
							$countitem++;
							}
						endwhile; endif; wp_reset_query();
						
						echo '</ul>';
					echo '</div>';
				echo '</div>';	
		}


		
		function update($new_instance, $old_instance) {
			$new_instance = aq_recursive_sanitize($new_instance);
			return $new_instance;
		}
		
		function excerpt_more($more) {
			global $post;
			return ' <a href="'. get_permalink($post->ID) . '">Continue Reading &rarr;</a>';
		}
		

	}
}
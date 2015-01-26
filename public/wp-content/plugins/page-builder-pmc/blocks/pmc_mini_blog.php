<?php
class PMC_Mini_Blog extends AQ_Block {
	
	function __construct() {
		$block_options = array(
			'name' => 'Mini blog',
			'size' => 'span12',
			'resizable' => '0',
			'icon' => 'fa-tasks',
			'icon_color' => 'FFF',
			'category' => 'Blog',
			'help' => 'This is the block that adds mini blog with left or right to the template.'
		);
		
		parent::__construct('pmc_mini_blog', $block_options);

					
	}
	
	function form($instance) {
		
		global $wp_registered_sidebars;
		$sidebar_options = array(); $default_sidebar = '';
		foreach ($wp_registered_sidebars as $registered_sidebar) {
			$default_sidebar = empty($default_sidebar) ? $registered_sidebar['id'] : $default_sidebar;
			$sidebar_options[$registered_sidebar['id']] = $registered_sidebar['name'];
		}
		
		$defaults = array(
			'categories' => null,
			'tags' => array(),
			'page' => false,
			'excerpt' => '10',
			'sidebar_position' => 'right',
			'sidebar' => $default_sidebar
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
		
		$sidebar_position_select = array(
			'nosidebar' => 'None (mini full width blog)',
			'left' => 'Left',
			'right' => 'Right',
		);
		
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

		<p class="description half">
			<label for="<?php echo $this->get_field_id('sidebar_position') ?>">
				Sidebar position<br/>
				<?php echo aq_field_select('sidebar_position', $block_id, $sidebar_position_select, $sidebar_position); ?>
			</label>
		</p>		

		<p class="description half last">
			<label for="">
				Choose sidebar<br/>
				<?php echo aq_field_select('sidebar', $block_id, $sidebar_options, $sidebar); ?>
			</label>
		</p>		
		<?php
		
	}
	
	function block($instance) {
		$defaults = array(
			'tags' => array(),
			'categories' => null,			
			'page' => false,
			'excerpt' => '10',
			'sidebar_position' => 'right'

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
		
		if($sidebar_position == 'nosidebar'){
			$limit = 35;
		}
		else{
			$limit = 20;
		}
		?>
			
		<?php
		wp_enqueue_script('pmc_bxSlider');		
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			jQuery('.bxslider').bxSlider({
			  auto: true,
			  speed: 1000,
			  controls: false,
			  pager :false,
			  easing : 'easeInOutQuint',
			});
		});		
		</script>
		<?php
		
		$postmeta = get_post_custom(get_the_id()); 
		if(isset($postmeta["link_post_url"][0])){
			$link = $postmeta["link_post_url"][0];
		} else {
			$link = "#";
		}		
		$args = array();
		//if($postnum) $args['posts_per_page'] = 5;
		if($categories) $args['category__in'] = $categories;
		if($tags) $args['tag__in'] = $tags;
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args['paged'] = $paged ;		
		
		query_posts($args);
		$count = 1;
		$countitem = 1;
		$type = 'post'; ?>
		<!-- main content start -->
		<div class="mainwrap blog mini-blog">
			<div class="mini-blog-content <?php echo $sidebar_position ?>">			
				<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post();
				$postmeta = get_post_custom(get_the_id()); 
				if ( has_post_format( 'gallery' , get_the_id())) { 
				?>
				<div class="slider-category">
				
					<div class="blogpostcategory">
					<?php
						global $post;
						$attachments = '';
						$post_subtitrare = get_post( get_the_id() );
						$content = $post_subtitrare->post_content;
						$pattern = get_shortcode_regex();
						preg_match( "/$pattern/s", $content, $match );
						if( isset( $match[2] ) && ( "gallery" == $match[2] ) ) {
							$atts = shortcode_parse_atts( $match[3] );
							$attachments = isset( $atts['ids'] ) ? explode( ',', $atts['ids'] ) : get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . get_the_id() .'&order=ASC&orderby=menu_order ID' );
						}
						if ($attachments) { ?>
							<div id="slider-category" class="slider-category">
							<div class="loading"></div>
								<ul id="slider" class="bxslider">
									<?php
										foreach ($attachments as $attachment) {
											$image =  wp_get_attachment_image_src( $attachment, 'blogMini' ); ?>	
												<li>
													<img src="<?php echo $image[0] ?>"/>							
												</li>
												<?php } ?>
								</ul>
							
						</div>
				  <?php } else { 
				  $image = 'http://placehold.it/800x490'; 
				  
				  ?>
				  <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo pmc_getImage(get_the_id(), 'blogMini'); ?></a>
				  <?php }?>
				  <div class="bottomborder"></div>
					<?php BlogLoop($limit); ?>
					</div>
				</div>
				<?php } 
				if ( has_post_format( 'video' , get_the_id())) { ?>
				<div class="slider-category">
				
					<div class="blogpostcategory">
					<?php
					
					if(!empty($postmeta["video_post_url"][0])) {?>
					<?php  
						echo '<div class ="mini-blog-video-container">';
						$video_arg  = '';
						$video = wp_oembed_get( $postmeta["video_post_url"][0], $video_arg );		
						$video = preg_replace('/width=\"(\d)*\"/', 'width="800"', $video);			
						$video = preg_replace('/height=\"(\d)*\"/', 'height="490"', $video);	
						echo $video;
						echo '</div>';
					}
					else{ 
						  $image = 'http://placehold.it/800x490'; 
						  
					?>
						  <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo pmc_getImage(get_the_id(),'blogMini'); ?></a>
						
					<?php } ?>	
					<div class="bottomborder"></div>
					<?php BlogLoop($limit); ?>
					</div>
				</div>
				<?php } 
				if ( has_post_format( 'link' , get_the_id())) {?>
				<div class="link-category">
					<div class="blogpostcategory">
						<div class="entry">
							<div class = "meta">
								<div class="topLeftBlog">	
									<h2 class="title"><a href="<?php echo $link  ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
									
									<div class = "post-meta">
										<?php _e('By ','pmc-themes'); ?>
										<?php echo get_the_author_link() ?>
										<?php _e(' on ','pmc-themes'); ?>
										<span><?php the_time('F j, Y') ?></span>
									</div>	
								</div>
								<div class="blogContent">
									<div class="blogcontent"><?php echo excerpt($limit); ?> </div>
									<a class="blogmore" href="<?php echo $link  ?>"><?php _e('Read more about this...','pmc-themes'); ?> </a>
								</div>
							</div>		
						</div>								
					</div>
				</div>
				
				<?php 
				} 	
				?>
				<?php if ( has_post_format( 'audio' , get_the_id())) {?>
				<div class="blogpostcategory audio">
					<div class="audioPlayerWrap">
						<div class="loading"></div>
						<div class="audioPlayer">
							<?php
							if(isset($postmeta["audio_post_url"][0]))
								echo do_shortcode('[audio file="'. $postmeta["audio_post_url"][0] .'"]') ?>
						</div>
					</div>
					<?php BlogLoop($limit); ?>		
				</div>	
				<?php
				}
				?>					
				
				
				<?php
				if ( !get_post_format() ) {?>
			
				<div class="blogpostcategory">
						
					<?php if(pmc_getImage(get_the_id(), 'blog') != '') { ?>	
						<a class="overdefultlink" href="<?php the_permalink() ?>">
						<div class="overdefult">
						</div>
						</a>
						<div class="blogimage">	
							<div class="loading"></div>		
							<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo pmc_getImage(get_the_id(), 'blogMini'); ?></a>
						</div>
						
						<div class="bottomborder"></div>
						<?php } ?>
						<?php BlogLoop($limit); ?>
				</div>	
				<?php } ?>		
				<?php endwhile; ?>
					
				<?php

				if(!function_exists('wp_pagenavi')) { 
					get_template_part('includes/wp-pagenavi');
					wp_pagenavi(); 
				}else{
					wp_pagenavi();
				}
				?>
				
				<?php else : ?>
				
					<div class="postcontent">
						<h1><?php echo $pmc_data['errorpagetitle'] ?></h1>
						<div class="posttext">
							<?php echo $pmc_data['errorpage'] ?>
						</div>
					</div>
					
				<?php endif; wp_reset_query();?>
			</div>
			<?php if($sidebar_position !='nosidebar') { ?>
			<!-- sidebar -->
			<div class="sidebar <?php echo $sidebar_position ?>">	
				<?php dynamic_sidebar( $sidebar ); ?>
			</div>
			<?php } ?>
			
		</div>	
		<?php
	}


	
	function update($new_instance, $old_instance) {
		$new_instance = aq_recursive_sanitize($new_instance);
		return $new_instance;
	}
	

	

}
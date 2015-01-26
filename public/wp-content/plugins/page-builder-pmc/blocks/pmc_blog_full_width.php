<?php
class PMC_Full_Width_Blog extends AQ_Block {
	
	function __construct() {
		$block_options = array(
			'name' => 'Blog Full w.',
			'size' => 'span12',
			'resizable' => '0',
			'icon' => 'fa-tasks',
			'icon_color' => 'FFF',
			'category' => 'Blog',
			'help' => 'This is the block that adds full width blog to the template.'
		);
		
		parent::__construct('pmc_full_width_blog', $block_options);
		add_filter('excerpt_more', array(&$this, 'excerpt_more'));

					
	}
	
	function form($instance) {
	


		$defaults = array(
			'name' => 'Blog Posts full width',
			'size' => 'span12',
			'resizable' => '0',
			'categories' => null,
			'tags' => array(),
			'postnum' => -1,
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
		<p class="description half last">		
			<label for="<?php echo $this->get_field_id('postnum') ?>">
				Number of blog items (for unlimitid add -1)<br/>
				<?php echo aq_field_input('postnum', $block_id, $postnum) ?>
			</label>		
		</p>		

		<?php
		
	}
	
	function block($instance) {
	

		
		$defaults = array(
			'name' => 'Blog Posts full width',
			'size' => 'span12',
			'resizable' => '0',
			'categories' => null,
			'tags' => array(),
			'postnum' => -1,
			'page' => false,
			'excerpt' => '',
			'post_ajax' => 'false'

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
		if($postnum != -1){
			if($postnum) $args['posts_per_page'] = $postnum;}
		if($categories) $args['category__in'] = $categories;
		if($tags) $args['tag__in'] = $tags;
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args['paged'] = $paged ;		
		
		query_posts($args);
		$count = 1;
		$countitem = 1;
		$type = 'post'; ?>
		<!-- main content start -->
		<div class="mainwrap blog full-width-blog">
			<div class="full-width-blog-content">			
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
						if ($attachments) {?>
							<div id="slider-category" class="slider-category">
							<div class="loading"></div>
								<ul id="slider" class="bxslider">
									<?php
										foreach ($attachments as $attachment) {
											$image =  wp_get_attachment_image_src( $attachment, 'blog' ); ?>	
												<li>
													<img src="<?php echo $image[0] ?>"/>							
												</li>
												<?php } ?>
								</ul>
							
						</div>
				  <?php } else { 
				  $image = 'http://placehold.it/700x490'; 
				  
				  ?>
				  <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo pmc_getImage(get_the_id(), 'blog'); ?></a>
				  <?php }?>
				  <div class="bottomborder"></div>
					<?php BlogLoop(70); ?>
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
						$video = preg_replace('/width=\"(\d)*\"/', 'width="700"', $video);			
						$video = preg_replace('/height=\"(\d)*\"/', 'height="490"', $video);	
						echo $video;
						echo '</div>';
					}
					else{ 
						  $image = 'http://placehold.it/700x490'; 
						  
					?>
						  <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo pmc_getImage(get_the_id(),'blog'); ?></a>
						
					<?php } ?>	
					<div class="bottomborder"></div>
					<?php BlogLoop(70); ?>
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
									<div class="blogcontent"><?php echo excerpt(70) ?> </div>
									<a class="blogmore" href="<?php echo $link  ?>"><?php _e('Read more','pmc-themes'); ?> </a>
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
					<?php BlogLoop(70); ?>		
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
							<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo pmc_getImage(get_the_id(), 'blog'); ?></a>
						</div>
						
						<div class="bottomborder"></div>
						<?php } ?>
						<?php BlogLoop(70); ?>
				</div>	
				<?php } ?>		
					<?php endwhile; ?>
					
						<?php
						if(!is_home()){
							if(!function_exists('wp_pagenavi')) { 
								get_template_part('includes/wp-pagenavi');
								wp_pagenavi(); 
							}else{
								wp_pagenavi();
							}
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
			
		</div>	
		<?php
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
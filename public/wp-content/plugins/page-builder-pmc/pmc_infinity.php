<?php
	$root = dirname(dirname(dirname(dirname(__FILE__))));
    require_once($root.'/wp-load.php');

		$item = $_GET['item']; 
		$post = $_GET['type'];
		$number = $_GET['number']; 
		$cat = unserialize(urldecode($_GET['categories']));  
		$port_ajax= $_GET['ajax']; 
		$fullwidth = $_GET['fullwidth'];
		$title = $_GET['title']; 
		$excerpt = $_GET['excerpt'];
		$home = '';
		$masonry = $_GET['masonry'];
		$related_port = '';
		$paged_in = $_GET['page'];		
		global $pmc_data; 
		$categport = '';
		$paged = (get_query_var('paged')) ? get_query_var('paged') : $paged_in;
		if($post == 'port'){
			$postT = $pmc_data['port_slug'];
			$postC = 'portfoliocategory';
			$showPost = $pmc_data['port_slug'];
		} else {
			$postT = 'post';
			$postC = 'category';	
			$showPost = 'post';
		}	
		if($cat != ''){	
				$args = array(
				'tax_query' => array(array('taxonomy' => $postC,'field' => 'id','terms' => $cat)),
				'showposts'     => $number,
				'post_type' => $postT,
				'paged'    => $paged
				);	
		
			}
		else{
				$args = array(
				'showposts'     => $number,
				'post_type' => $postT,
				'paged'    => $paged
				);
			}
		query_posts( $args );
		
		if($item == 4){
			$columns = 'one_fourth';
			$image_width = 'port4';
			$limit = 20;
		}
		if($item == 3){
			$columns = 'one_third';
			$image_width = 'port3';
			$limit = 26;
		}	
		if($item == 2){
			$columns = 'one_half';
			$image_width = 'port2';
			$limit = 35;
		}
		$new_class =  'pagePort'; 
		
		$masonry_pmc = '';
		if($masonry == 1){
			$masonry_pmc =  'masonry_pmc'; 
		} 		
		$currentindex = $linkPost = '';
		$currentindex = '';
		$count = 1;
		$countitem = 1;?>
					<?php
					while ( have_posts() ) : the_post();
						if($post == 'post')
							$postmeta = get_post_custom(get_the_ID()); 
						$do_not_duplicate = get_the_ID(); 
						if ( has_post_thumbnail() ){
							$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $image_width , false);
							$image = $image[0];
							
							$imagefull = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', false);
							$imagefull = $imagefull[0];			
							}
						$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', false);
						$entrycategory = get_the_term_list( get_the_ID(), $postC, '', ',', '' );
						$catstring = $entrycategory;
						$catstring = strip_tags($catstring);
						$catidlist = explode(",", $catstring);
						$catlist = '';
						for($i = 0; $i < sizeof($catidlist); ++$i){
							$catidlist[$i].=$currentindex;
							$find =     array("&", "/", " ","amp;","&#38;");
							$replace  = array("", "", "", "","");			
							$catlist .= str_replace($find,$replace,$catidlist[$i]). ' ';
							
						}
						$categoryIn = get_the_term_list( get_the_ID(), $postC, '', ', ', '' );	
						$category = explode(',',$categoryIn);	
						if ( has_post_format( 'link' , get_the_ID()) and $post == 'post') {
							if(isset($postmeta["link_post_url"][0] )) $linkPost = $postmeta["link_post_url"][0];
							}
						else{
							if (function_exists('icl_object_id')) 
								$linkPost = get_permalink(icl_object_id(get_the_ID(), $pmc_data['port_slug'], true, true));
							else 
								$linkPost = get_permalink();
						}		
						
						if($count != ($item+1)){
							echo '<div class="'.$columns.' item'.$item.' '.$catlist .' '.$masonry_pmc.'" data-category="'. $catlist.'" >';							
						}
						else{
							echo '<div class="'.$columns.' last item'.$item.' '.$catlist .' '.$masonry_pmc.'" data-category="'. $catlist.'" >';
							$count = 0;
						}
						
						?>
								<?php if ($port_ajax == 'true' && !has_post_format( 'link' , get_the_id())){ ?>
								<div class="click" id="<?php echo $showPost ?>_<?php echo get_the_id() ?>">
								<?php } ?>
			
									<div class="recentimage">
										<?php if ($port_ajax != 'true' || has_post_format( 'link' , get_the_id())){ ?>
											<?php if (has_post_format( 'link' , get_the_id())) { ?>
												<a href = "<?php echo $postmeta["link_post_url"][0] ?>" title="<?php echo esc_attr(  get_the_title(get_the_id()) ) ?>"  >
											<?php } else { ?>
												<a href = "<?php echo get_permalink( get_the_id()) ?>" title="<?php echo esc_attr(  get_the_title(get_the_id()) ) ?>"  >
											<?php } ?>
										<?php } ?>									
											<div class="overdefult">
											
												<div class="recentdescription">
													<?php if($masonry != 1){ ?>
														<div class="home-portfolio-categories pagePort"><?php echo strip_tags(get_the_term_list( get_the_ID(), $postC, '', ', ', '' )); ?></div>
													<?php } ?>							
												</div>
												
											</div>	
										<?php if ($port_ajax != 'true' || has_post_format( 'link' , get_the_id())){ ?>
											</a>
										<?php } ?>										
										<div class="image">
											<?php if ($port_ajax != 'true' || has_post_format( 'link' , get_the_id())){ ?>
												<?php if (has_post_format( 'link' , get_the_id())){ ?>
													<a href = "<?php echo $postmeta["link_post_url"][0] ?>" title="<?php echo esc_attr(  get_the_title(get_the_id()) ) ?>"  >
												<?php } else { ?>
													<a href = "<?php echo get_permalink( get_the_id()) ?>" title="<?php echo esc_attr(  get_the_title(get_the_id()) ) ?>"  >
												<?php } ?>
											<?php } ?>										
											<?php if (isset($image)){ ?>
												<?php if($countitem == 2) { ?>
													<img class="portfolio-home-image" src="<?php echo $image_big ?>" alt="<?php the_title(); ?>">
												<?php } else { ?>
												
													<img class="portfolio-home-image" src="<?php echo $image ?>" alt="<?php the_title(); ?>">
												
												<?php } ?>	
											<?php } else  { ?>	
												<img  class="portfolio-home-image" src="http://placehold.it/393x300" alt="<?php the_title(); ?>">
											<?php } ?>	
											<?php if ($port_ajax != 'true'){ ?>
												</a>
											<?php } ?>	
										</div>
									</div>								

									<div class="port-meta">
									<?php if($related_port == ''){ ?>
									<?php if($title == 1) { ?>
										<?php if ($port_ajax != 'true' || has_post_format( 'link' , get_the_id())){ ?>
											<?php if (has_post_format( 'link' , get_the_id())){ ?>
												<a href = "<?php echo $postmeta["link_post_url"][0] ?>" title="<?php echo esc_attr(  get_the_title(get_the_id()) ) ?>"  >
											<?php } else { ?>
												<a href = "<?php echo get_permalink( get_the_id()) ?>" title="<?php echo esc_attr(  get_the_title(get_the_id()) ) ?>"  >
											<?php } ?>
										<?php } ?>									
										<div class="port-meta title"><?php the_title() ?></div>
										<?php if ($port_ajax != 'true' || has_post_format( 'link' , get_the_id())){ ?>
											</a>
										<?php } ?>	
									<?php } ?>		
									<?php if($masonry == 1){ ?>
										<div class="home-portfolio-categories pagePort"><?php echo strip_tags(get_the_term_list( get_the_ID(), $postC, '', ', ', '' )); ?></div>
									<?php } ?>								
									<?php if($excerpt == 1) { ?>
										<div class="port-meta excerpt"><?php echo excerpt($limit); ?> </div>
									<?php } ?>
									<?php if ($post == 'post' && $masonry == 1){?> <a class="masonry-blog-link" href = "<?php echo get_permalink( get_the_id()) ?> " ><?php _e('Read more','pmc-themes'); ?></a><?php } ?>
									<?php } ?>
									</div>
								<?php if ($port_ajax == 'true' && !has_post_format( 'link' , get_the_id())){ ?>	
								</div>
								<?php } ?>
							</div>
						<?php 
						$count++;		
						
					endwhile; ?>
				
			<?php 
			wp_reset_query();
			
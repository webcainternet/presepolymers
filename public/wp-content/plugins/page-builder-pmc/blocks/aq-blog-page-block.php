<?phpclass AQ_Blog_Page_Block extends AQ_Block {		//set and create block	function __construct() {		$block_options = array(			'name' => 'Blog Page',
			'size' => 'span12',			'icon' => 'fa-th-large',			'icon_color' => 'FFF',			'category' => 'Blog',			'help' => 'This is the block that adds sort blog block. This is the block where you can sort the blog items by category.',			'resizable' => 0		);				//create the block		parent::__construct('aq_blog_page_block', $block_options);	}		function form($instance) {			$defaults = array(			'filter' => 1,			'categories_blog'	=> null,			'numberofpost'	=> 12,			'id' => 'blog_block',			'blog_ajax' => 'false',				'categories_post_selected' => '',			'show_title' => 1,			'show_excerpt' => 1,			'masonry' => 0,			'no_blog' => 4,			'infinite' => 0,			'delay' => 1000					);		$instance = wp_parse_args($instance, $defaults);		extract($instance);				$categories_default = ($temp = get_terms('category')) ? $temp : array();		$categories_options_default = array();		$i = 0;		foreach($categories_default as $cat_default) {			$categories_options_default[$i++] = $cat_default->term_id;		}			If(empty($categories_blog)){				$categories_blog = $categories_options_default;		}			$isinarray = true;		foreach($categories_blog as $cat_saved){						if(array_search($cat_saved,$categories_options_default) === false){					$isinarray = false;				}			if($isinarray == false){				$categories_blog = null;				break; 			}					}		if(empty($categories_blog)){			$categories_blog = $categories_options_default;		}						$categories = ($temp = get_terms('category')) ? $temp : array();		$categories_options = array();		foreach($categories as $cat) {			$categories_options[$cat->term_id] = $cat->name;		}						$categories = ($temp = get_terms('category')) ? $temp : array();		$categories_options_selected = array();		$categories_options_selected[-1] = 'None';		foreach($categories as $cat) {			$categories_options_selected[$cat->term_id] = $cat->name;		}							$ajax_options = array(			'true' => 'True',			'false' => 'False',		);		$blog_select = array(			'2' => 'Two column',			'3' => 'Three column',			'4' => 'Four column',		);						if( function_exists( 'pmc_portfolio' ) ){							?>			<p>Note: You should only use this block on a full-width template</p>			<p class="description third">				<label for="<?php echo $this->get_field_id('categories_blog') ?>">				Blog Categories<br/>				<?php echo aq_field_multiselect('categories_blog', $block_id, $categories_options, $categories_blog); ?>				</label>						</p>									<p class="description third">				<label for="<?php echo $this->get_field_id('blog_ajax') ?>">					Use ajax<br/>					<?php echo aq_field_select('blog_ajax', $block_id, $ajax_options, $blog_ajax); ?>				</label>				<label for="<?php echo $this->get_field_id('categories_post_selected') ?>">				Select active category on load<br/>				<?php echo aq_field_select('categories_post_selected', $block_id, $categories_options_selected, $categories_post_selected); ?>				</label>					<label for="<?php echo $this->get_field_id('no_blog') ?>">					Numner of columns<br/>					<?php echo aq_field_select('no_blog', $block_id, $blog_select, $no_blog); ?>				</label>							</p>					<p class="description third last">				<label for="<?php echo $this->get_field_id('numberofpost') ?>">					Number of blog items (for unlimitid add -1)<br/>					<?php echo aq_field_input('numberofpost', $block_id, $numberofpost) ?>				</label>				<label for="<?php echo $this->get_field_id('filter') ?>" style="width:100%;float: left;margin: 10px 0;">					<?php echo aq_field_checkbox('filter', $block_id, $filter); ?>					Show filter (if not selected Isotope is disabled)?				</label>					<label for="<?php echo $this->get_field_id('show_title') ?>"  style="width:100%;float: left;margin-bottom: 10px;">					<?php echo aq_field_checkbox('show_title', $block_id, $show_title); ?>					Show title?				</label>				<label for="<?php echo $this->get_field_id('show_excerpt') ?>" style="width:100%;float: left;margin-bottom: 10px;">					<?php echo aq_field_checkbox('show_excerpt', $block_id, $show_excerpt); ?>					Show excerpt?				</label>				<label for="<?php echo $this->get_field_id('masonry') ?>" style="width:100%;float: left;margin-bottom: 10px;">					<?php echo aq_field_checkbox('masonry', $block_id, $masonry); ?>					Masonry design?				</label>					<label for="<?php echo $this->get_field_id('infinite') ?>" style="width:100%;float: left;margin-bottom: 10px;">					<?php echo aq_field_checkbox('infinite', $block_id, $infinite); ?>					Infinite scroll?				</label>					<label for="<?php echo $this->get_field_id('infinite') ?>">					Delay befor loading infinite content					<?php echo aq_field_input('delay', $block_id, $delay); ?>				</label>							</p>						<?php		}		else {			echo '<p class="description note">For this block you need to use PremiumCoding themes!</p>';		}				}		function block($instance) {				$defaults = array(			'filter' => 1,			'categories_blog'	=> null,			'numberofpost'	=> 12,			'id' => 'blog_block',			'blog_ajax' => 'false',				'categories_post_selected' => '',			'show_title' => 1,			'show_excerpt' => 1,			'masonry' => 0,			'no_blog' => 4,			'infinite' => 0,				'delay' => 1000,						);		$instance = wp_parse_args($instance, $defaults);		extract($instance);		$categories_default = ($temp = get_terms('category')) ? $temp : array();		$categories_options_default = array();		$i = 0;		foreach($categories_default as $cat_default) {			$categories_options_default[$i++] = $cat_default->term_id;		}			If(empty($categories_blog)){				$categories_blog = $categories_options_default;		}		$isinarray = true;		foreach($categories_blog as $cat_saved){						if(array_search($cat_saved,$categories_options_default) === false){					$isinarray = false;				}			if($isinarray == false){				$categories_blog = null;				break; 			}					}		if(empty($categories_blog)){			$categories_blog = $categories_options_default;		}							wp_enqueue_script('pmc_ba-bbq');			if($filter == 1 && count($categories_blog) > 1){ ?>							<div id="<?php if(isset($id)){ echo $id; }?>">				<div id="remove" class="portfolioremove" data-option-key="filter">					<h2>					<a class="catlink" href="#filter" data-option-value="*"><?php _e('Show All','pmc-themes'); ?> <span> </span></a>					<?php					foreach ($categories_blog as $category) {					$find =     array("&", "/", " ","amp;","&#38;");					$replace  = array("", "", "", "","");					$entrycategory = str_replace($find , $replace, pmc_getcatname($category,'category'));						echo '<a class="catlink" href="#filter" data-option-value=".'.$entrycategory .'" >'.pmc_getcatname($category,'category').' <span class="aftersortingword"> </span></a>';					}					?>					</h2>				</div>			</div>					<?php } ?>			<input id = "plugin-url" type = "hidden" value="<?php echo plugins_url() ?>/page-builder-pmc/">			<?php pmc_portfolio(4,$no_blog,'post',$numberofpost,$categories_blog,$blog_ajax, 'blog-page' ,$show_title , $show_excerpt,'',$masonry,'',$infinite); ?>							<?php wp_reset_query(); 				if($filter == 1){ ?>				<script>			jQuery(function(){			  			  var $container = jQuery('#portitems4');			  $container.isotope({				itemSelector : '.item<?php echo $no_blog ?>'				<?php if (isset($categories_port_selected) and $categories_port_selected != -1 ){					  $cat = pmc_getcatname($categories_port_selected,'portfoliocategory'); ?>				,filter : '.<?php echo $cat ?>'				<?php } ?>			  });			  			  			  var $optionSets = jQuery('#remove'),				  $optionLinks = $optionSets.find('a');			  $optionLinks.click(function(){				var $this = jQuery(this);				// don't proceed if already selected				if ( $this.hasClass('selected') ) {				  return false;				}				var $optionSet = $this.parents('#remove');				$optionSet.find('.selected').removeClass('selected');				$this.addClass('selected');				// make option object dynamically, i.e. { filter: '.my-filter-class' }				var options = {},					key = $optionSet.attr('data-option-key'),					value = $this.attr('data-option-value');				// parse 'false' as false boolean				value = value === 'false' ? false : value;				options[ key ] = value;				if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {				  // changes in layout modes need extra logic				  changeLayoutMode( $this, options )				} else {				  // otherwise, apply new options				  $container.isotope( options );				}								return false;			  });			jQuery(window).on('load', function(){				var height = parseInt(jQuery('.isotope-item img').height());				height = height + parseInt(jQuery('.isotope-item .port-meta').height());				jQuery('.isotope-item ').css('height',height+'px');				$container.isotope('reLayout');			});
			  			jQuery( window ).resize(function() {				var height = parseInt(jQuery('.isotope-item img').height());				height = height + parseInt(jQuery('.isotope-item .port-meta').height());				jQuery('.isotope-item ').css('height',height+'px');				$container.isotope('reLayout');			});			<?php if ($infinite == 1) { ?>				var path = jQuery('#plugin-url').attr('value');				$container.infinitescroll({				navSelector  : '.navigation-pmc',    // selector for the paged navigation 				nextSelector : '.navigation-pmc a:first',  // selector for the NEXT link (to page 2)				itemSelector : '.item<?php echo $no_blog ?>',     // selector for all items you'll retrieve				delay: <?php echo $delay ?>,				loading: {					finishedMsg: '<?php _e("<span>No more pages to load.</span>","pmc-themes") ?>',					msgText: '<?php _e("<i class=\'fa fa-spinner fa-spin fa-2x\'></i><span>Loading the next set of posts...</span>","pmc-themes") ?>',					speed: 500,				  },				path: function(index) { return path+'pmc_infinity.php?page='+ index +'&item=<?php echo $no_blog?>&type=post&number=<?php echo $numberofpost ?>&categories=<?php echo urlencode(serialize($categories_blog)) ?>&ajax=<?php echo $blog_ajax ?>&fullwidth=0&title=<?php echo $show_title ?>&excerpt=<?php echo $show_excerpt ?>&masonry=<?php echo $masonry ?>'}				},				// call Isotope as a callback				function( newElements ) {				  $container.isotope( 'appended', jQuery( newElements ) ); 				  $container.isotope('reLayout');				}				);			<?php } ?>						});			</script>		<?php		}		if($filter == 0 && $infinite == 1){ ?>			<script>			jQuery(function(){				var path = jQuery('#plugin-url').attr('value');				jQuery('#portitems4').infinitescroll({				navSelector  : '.navigation-pmc',    // selector for the paged navigation 				nextSelector : '.navigation-pmc a:first',  // selector for the NEXT link (to page 2)				itemSelector : '.item<?php echo $no_blog ?>',     // selector for all items you'll retrieve				delay: <?php echo $delay ?>,				loading: {					finishedMsg: '<?php _e("</span>No more pages to load.</span>","pmc-themes") ?>',					msgText: '<?php _e("<i class=\'fa fa-spinner fa-spin fa-2x\'></i><span>Loading the next set of posts...</span>","pmc-themes") ?>',					speed: 500,				  },				path: function(index) { return path+'pmc_infinity.php?page='+ index +'&item=<?php echo $no_blog ?>&type=post&number=<?php echo $numberofpost ?>&categories=<?php echo urlencode(serialize($categories_blog)) ?>&ajax=<?php echo $blog_ajax ?>&fullwidth=0&title=<?php echo $show_title ?>&excerpt=<?php echo $show_excerpt ?>&masonry=<?php echo $masonry ?>'}				}			  );									});			</script>		<?php }			}		function update($new_instance, $old_instance) {		return $new_instance;	}	}
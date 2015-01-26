<?php
/*meta box in post and pages*/
function pmc_shortcodes_admin() {
	wp_register_style( 'pmc-shortcodes-css', plugins_url().'/page-builder-pmc/assets/css/aqpb-custom-admin.css', false, '1.0.0' );
	wp_register_style('icons', plugins_url() . '/page-builder-pmc/assets/css/font-awesome.css', array(), time(), 'all');
	wp_register_script('pmc_isotope', plugins_url() . '/page-builder-pmc/assets/js/jquery.isotope.min.js', array('jquery'),true,true);
	wp_enqueue_script('pmc_isotope');		
	wp_enqueue_style( 'pmc-shortcodes-css' );
	wp_enqueue_style( 'icons' );
	wp_enqueue_script('jquery-ui-dialog');

}
add_action( 'admin_enqueue_scripts', 'pmc_shortcodes_admin' );
function pmc_shortcodes_front() {
	wp_enqueue_style('pmc_charts-css', plugins_url().'/page-builder-pmc/assets/css/jquery.easy-pie-chart.css', 'style');  	
	wp_enqueue_script('pmc_rain', plugins_url().'/page-builder-pmc/assets/js/rainyday.js', '', time(), false);     	
	wp_enqueue_script('pmc_charts', plugins_url().'/page-builder-pmc/assets/js/circles.js', '' ,'' ,false);   
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-progressbar');
}
add_action( 'wp_enqueue_scripts', 'pmc_shortcodes_front' );
add_action("admin_init", "pmc_add_shortcodes");


function pmc_add_shortcodes(){
	global $pmc_data;
	if (isset($pmc_data['port_slug']) && $pmc_data['port_slug'] != ''){
		$port = $pmc_data['port_slug'];
	}
	else{
		$port = 'portfolio';
	}
	$types = array( 'post', 'page', 'product', $port );
	foreach( $types as $type ) {
	add_meta_box("pmc_shortcodes", "PMC Shortcodes", "pmc_shortcodes", $type , "side", "core");	
	}
}

function pmc_shortcodes(){
	if(!isset($rand)){$rand=rand(0,99);}
	global $pmc_data;	
?>
    <div id="shortcodes">
		<div id="add_shortcode_button-<?php echo $rand ?>" class="add_shortcode_button">Add new ShortCode</div>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#insert').attr("disabled", true);
					jQuery('#insert').addClass("disabled");
					jQuery('#select_shortcode').change(function() {
						if( jQuery(this).val() == '' ) {
							jQuery('#insert').attr("disabled", true);
							jQuery('#insert').addClass("disabled");
						} else {
							jQuery('#insert').removeAttr("disabled");
							jQuery('#insert').removeClass("disabled");
						}
					});
					
					jQuery('.shortcodes_buttons a').click(function(){
						var shortcode = jQuery(this).attr('id'); 
						returnShortcodeValue(shortcode);
					});
					
					jQuery('.add_shortcode_button').unbind('click').click( function(){	
						var id = jQuery(this).parent().find('textarea').attr('id');
						jQuery('.shortcode-buttons').attr('content',id);
							jQuery('#select-shortcode').dialog({
								title: 'Select PMC ShortCode',
								resizable: true,
								modal: true,
								hide: 'fade',
								width:1090,
								height:600,
								dialogClass: 'pmc_shortcode_dialog'
							});//end dialog   
						jQuery('#shortcodes_buttons').isotope('reLayout');	
					});	

					
					jQuery('.shortcode-help').click(function(){
						
						jQuery('#tab-link-customize-display, #tab-panel-customize-display').removeClass('active');
						jQuery('#tab-link-pmc_shortcode,#tab-panel-pmc_shortcode').addClass('active');
						setTimeout(function(){jQuery( "html, body" ).animate({ scrollTop: 0 }, "slow"),5000});
						jQuery('#contextual-help-link').click();
					});
					
				});
				
				function returnShortcodeValue(shortcode) {
					var out;
					switch(shortcode)
					{
						case "one_half": 
							out = "[half]<p>Your content here...</p>[/half]<br />";
							break;
						case "one_half_last": 
							out = "[half_last]<p>Your content here...</p>[/half_last]<br />";
							break;
						case "one_third": 
							out = "[one_third]<p>Your content here...</p>[/one_third]<br />";
							break;
						case "one_third_last": 
							out = "[one_third_last]<p>Your content here...</p>[/one_third_last]<br />";
							break;
						case "two_thirds": 
							out = "[two_thirds]p>Your content here...</p>[/two_thirds]<br />";
							break;
						case "two_thirds_last": 
							out = "[two_thirds_last]<p>Your content here...</p>[/two_thirds_last]<br />";
							break;
						case "one_fourth": 
							out = "[one_fourth]<p>Your content here...</p>[/one_fourth]<br />";
							break;
						case "one_fourth_last": 
							out = "[one_fourth_last]<p>Your content here...</p>[/one_fourth_last]<br />";
							break;
						case "three_fourths": 
							out = "[three_fourths]<p>Your content here...</p>[/three_fourths]<br />";
							break;
						case "three_fourths_last": 
							out = "[three_fourths_last]<p>Your content here...</p>[/three_fourths_last]<br />";
							break;
						case "one_fifth": 
							out = "[one_fifth]<p>Your content here...</p>[/one_fifth]<br />";
							break;
						case "one_fifth_last": 
							out = "[one_fifth_last]<p>Your content here...</p>[/one_fifth_last]<br />";
							break;					
						case "accordion":
							out = "[accordion]<br />[atab title=\"First Accordion tab\"]Accordion Tab content goes here[/atab]<br />[atab title=\"Second Accordion tab\"]Accordion Tab content goes here[/atab]<br />[atab title=\"Third Accordion tab\"]Accordion Tab content goes here[/atab]<br /> [/accordion]<br />";
							break;	
						case "tabs":
							out = "[tabgroup]<br />[tab title=\"First tab\"]Tab content goes here[/tab]<br />[tab title=\"Second tab\"]Tab content goes here[/tab]<br />[tab title=\"Third tab\"]Tab content goes here[/tab]<br /> [/tabgroup]<br />";
							break;
						case "toggle":
							out = "[toggle title=\"Toggle title...\"]Toggle content...[/toggle]<br />";
							break;
						case "progressbar":
							out = "[progressbar progress=30 color=#fff]Title[/progressbar]<br />";
							break;		
						case "pmc_progress_circle":
							out = "[pmc_progress_circle progress=\"30\" background_color=\"#000\" progress_border=\"#fff\"  border_color=\"#000\" radius=\"60\"]Title[/pmc_progress_circle]<br />";
							break;									
						case "break":
							out = "[break/]<br />";
							break;
						case "break_line":
							out = "[break_line border_color=\"<?php echo $pmc_data['mainColor'] ?>\" /]<br />";
							break;							
						case "dropcap":
							out = "[dropcap]A[/dropcap]<br />";
							break;
						case "list_comment":
							out = "[list_comment]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_comment]<br />";
							break;
						case "list_circle":
							out = "[list_circle]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_circle]<br />";
							break;
						case "list_plus":
							out = "[list_plus]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_plus]<br />";
							break;
						case "list_ribbon":
							out = "[list_ribbon]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_ribbon]<br />";
							break;
						case "list_settings":
							out = "[list_settings]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_settings]<br />";
							break;
						case "list_star":
							out = "[list_star]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_star]<br />";
							break;
						case "list_image":
							out = "[list_image]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_image]<br />";
							break;
						case "list_link":
							out = "[list_link]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_link]<br />";
							break;		
						case "list_mail":
							out = "[list_mail]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_mail]<br />";
							break;						
						case "list_arrow":
							out = "[list_arrow]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_arrow]<br />";
							break;
						case "list_tick":
							out = "[list_tick]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_tick]<br />";
							break;					
						case "list_arrow_point":
							out = "[list_arrow_point]<br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br /><li>list item...</li><br />[/list_arrow_point]<br />";
							break;		
						case "box_1":
							out = "[info icon=\"fa-info-circle\"]Content...[/info]<br />";
							break;
						case "box_2":
							out = "[success icon=\"fa-check-circle\"]Content...[/success]<br />";
							break;
						case "box_3":
							out = "[question icon=\"fa-question-circle\"]Content...[/question]<br />";
							break;
						case "box_4":
							out = "[error icon=\"fa-times-circle\"]Content...[/error]<br />";
							break;	
						case "font_icon":
							out = "[font_icon color=\"<?php echo $pmc_data['mainColor'] ?>\" height=\"14px\" icon=\"fa-picture-o\" /]<br />";
							break;								
						case "button_icon":
							out = "[button_icon target=\"_blank\" link=\"http://premiumcoding.com\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\" text_color=\"#fff\" icon=\"fa-picture-o\" ]Button with Font Awesome icons[/button_icon]<br />";
							break;	
						case "button_simple":
							out = "[button_simple target=\"_blank\" link=\"http://premiumcoding.com\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\" text_color=\"#fff\"  ]Simple button[/button_simple]<br />";
							break;		
						case "button_round":
							out = "[button_round target=\"_blank\" link=\"http://premiumcoding.com\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\" text_color=\"#fff\"  ]Round button[/button_round]<br />";
							break;			
						case "button_simple_icon":
							out = "[button_simple_icon target=\"_blank\" link=\"http://premiumcoding.com\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\" text_color=\"#fff\" icon=\"fa-picture-o\" ]Simple button with Font Awesome icons[/button_simple_icon]<br />";
							break;		
						case "button_only_icon":
							out = "[button_only_icon target=\"_blank\" link=\"http://premiumcoding.com\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\"  icon=\"fa-picture-o\" ][/button_only_icon]<br />";
							break;		
						case "button_social":
							out = "[button_social target=\"_blank\" link=\"http://premiumcoding.com\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\" text-color=\"#fff\" icon=\"fa-picture-o\" ]Social button[/button_social]<br />";
							break;	
						case "button_simple_double":
							out = "[button_simple_double target=\"_blank\" link=\"http://premiumcoding.com\" background_color_double=\"<?php echo $pmc_data['mainColor'] ?>\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" text_color=\"#fff\"  ]Double simple button[/button_simple_double]<br />";
							break;
						case "button_icon_double":
							out = "[button_icon_double target=\"_blank\" link=\"http://premiumcoding.com\" background_color_double=\"<?php echo $pmc_data['mainColor'] ?>\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" text_color=\"#fff\" icon=\"fa-picture-o\" ][/button_icon_double]<br />";
							break;		
						case "pmc_icon":
							out = "[pmc_icon target=\"_blank\" link=\"http://premiumcoding.com\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\"  icon=\"fa-picture-o\" size=\"medium\"][/pmc_icon]<br />";
							break;								
						case "pricing_tables":
							out = "[pricing_tabels width=\"\" highlighted=\"false\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\"  title=\"Pricing tabel\" price=\"$999.99\" price_title=\"per month\" button=\"SIGN UP\" button_link=\"http://premiumcoding.com\"]<br />[pricing_options background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]First option[/pricing_options]<br>[pricing_options background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]Second option[/pricing_options]<br>[pricing_options background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]Third option[/pricing_options]<br /> [/pricing_tabels]<br>";
							break;	
						case "pricing_tables_circle":
							out = "[pricing_tabels_circle background_color_circle=\"<?php echo $pmc_data['mainColor'] ?>\" border_color_circle=\"<?php echo $pmc_data['mainColor'] ?>\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\"  title=\"Pricing tabel\" price=\"$999.99\" price_title=\"per month\" button=\"SIGN UP\" button_link=\"http://premiumcoding.com\"]<br />[pricing_options_circle background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]First option[/pricing_options_circle]<br>[pricing_options_circle background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]Second option[/pricing_options_circle]<br>[pricing_options_circle background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]Third option[/pricing_options_circle]<br /> [/pricing_tabels_circle]<br>";
							break;	
						case "pricing_tables_icon":
							out = "[pricing_tabels_icon background_color=\"<?php echo $pmc_data['mainColor'] ?>\" icon=\"http://cherry.premiumcoding.com/wp-content/uploads/2013/10/hosting-icon.png\" title=\"Pricing tabel\" price=\"$999.99\" price_title=\"per month\" button=\"SIGN UP\" button_link=\"http://premiumcoding.com\"]<br />[pricing_options_icon background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]First option[/pricing_options_icon]<br>[pricing_options_icon background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]Second option[/pricing_options_icon]<br>[pricing_options_icon background_color=\"<?php echo $pmc_data['mainColor'] ?>\" ]Third option[/pricing_options_icon]<br /> [/pricing_tabels_icon]<br>";
							break;	
						case "pricing_tables_white":
							out = "[pricing_tabels_white  border_color_circle=\"<?php echo $pmc_data['mainColor'] ?>\" background_color=\"<?php echo $pmc_data['mainColor'] ?>\"  title=\"Pricing tabel\" price=\"$999.99\" price_title=\"per month\" button=\"SIGN UP\" button_link=\"http://premiumcoding.com\" button_color=\"<?php echo $pmc_data['mainColor'] ?>\"]<br />[pricing_options_white text_color=\"#000\" ]First option[/pricing_options_white]<br>[pricing_options_white text_color=\"#000\"]Second option[/pricing_options_white]<br>[pricing_options_white text_color=\"#000\"]Third option[/pricing_options_circle]<br /> [/pricing_tabels_white]<br />";
							break;								
						case "count_block":
							out = "[count_block background_color=\"<?php echo $pmc_data['mainColor'] ?>\" text_color=\"#fff\" icon=\"fa-picture-o\" number=\"999\" ]Count Block[/count_block]<br />";
							break;
						case "count_block_simple":
							out = "[count_block_simple background_color=\"#fff\" text_color=\"#2a2b2c\" icon=\"fa-clock-o\" number=\"365\" ]DAYS TO MAKE IT[/count_block_simple]<br />";
							break;							
						case "google_map":
							out = "[google_map zoom=\"8\" width=\"\" height=\"500\" full_width=\"false\" address=\"Slovenia, Ljubljana\" style=\"\" image=\"\" bounce=\"true\"][/google_map]<br />";
							break;
						case "google_map_more":
							out = "[google_map_more zoom=\"8\" width=\"\" height=\"500\" full_width=\"false\" address=\"46.0569465,14.5057515|45.9665830,14.2973873\" style=\"\" image=\"\" bounce=\"true\"][/google_map_more]<br />";
							break;							
						case "pmc_box":
							out = "[pmc_box background_color=\"<?php echo $pmc_data['mainColor'] ?>\" border_color=\"<?php echo $pmc_data['gradient_color'] ?>\" text_color=\"#fff\" ]Your text...[/pmc_box]<br />";
							break;
						case "pmc_box_icon":
							out = "[pmc_box_icon target=\"_blank\" animated=\"fadeInLeft\" link=\"http://premiumcoding.com\" icon_location=\"top\" size=\"medium\"  border_color=\"<?php echo $pmc_data['gradient_color'] ?>\" text_color=\"#333\" icon=\"fa-picture-o\" title=\"Box with icon\"]Your content goes here...[/pmc_box_icon]<br />";
							break;								
						case "pmc_quote":
							out = "[pmc_quote border_color=\"<?php echo $pmc_data['gradient_color'] ?>\"]Your text...[/pmc_quote]<br />";
							break;			
						case "image_circle_1":
							out = "[image_circle_1 target=\"_blank\" image=\"\" border_color_1=\"#000\" border_color_2=\"<?php echo $pmc_data['gradient_color'] ?>\" title=\"HEADING\" ]Your content...[/image_circle_1]<br />";
							break;	
						case "image_square_1":
							out = "[image_square_1 target=\"_blank\" link=\"http://premiumcoding.com\" image=\"\" border_color_1=\"#000\" animation=\"bottom_to_top\" title=\"HEADING\" ]Your content...[/image_square_1]<br />";
							break;
						case "pmc_image":
							out = "[pmc_image link=\"http://premiumcoding.com\" image=\"\" animated=\"fadeInLeft\" icon=\"fa-picture-o\"]Your content...[/pmc_image]<br />";
							break;
						case "lightbox":
							out = "[lightbox image=\"\" small_image=\"\"][/lightbox]<br />";
						break;		
						case "scroll_link":
							out = "[scroll_link link=\"\" ]Content[/scroll_link]<br />";
						break;	
						<?php if(pmc_woo() && PMC_SHOP){ ?>
						case "woocommerce_cart":
							out = "[woocommerce_cart]<br />";
							break;	
						case "woocommerce_checkout":
							out = "[woocommerce_checkout]<br />";
							break;	
						case "woocommerce_order_tracking":
							out = "[woocommerce_order_tracking]<br />";
							break;	
						case "woocommerce_my_account":
							out = "[woocommerce_my_account order_count=\"6\" ]<br />";
						break;	
						case "recent_products":
							out = "[recent_products per_page=\"12\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;	
						case "featured_products":
							out = "[featured_products per_page=\"12\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;		
						case "product_id":
							out = "[product id=\"\" ]<br />";
						break;	
						case "product_sku":
							out = "[product sku=\"\" ]<br />";
						break;	
						case "products_id":
							out = "[products ids=\"\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;		
						case "products_sku":
							out = "[products skus=\"\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;	
						case "add_to_cart_sku":
							out = "[add_to_cart sku=\"foo\" ]<br />";
						break;
						case "add_to_cart_id":
							out = "[add_to_cart id=\"foo\" ]<br />";
						break;
						case "add_to_cart_url_sku":
							out = "[add_to_cart_url id=\"\" ]<br />";
						break;
						case "add_to_cart_url_id":
							out = "[add_to_cart_url id=\"foo\" ]<br />";
						break;		
						case "product_page_sku":
							out = "[product_page sku=\"foo\" ]<br />";
						break;
						case "product_page_id":
							out = "[product_page id=\"foo\" ]<br />";
						break;	
						case "product_category":
							out = "[product_category category=\"category\" per_page=\"12\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;	
						case "product_categories":
							out = "[product_categories ids=\"\" hide_empty=\"1\" parent=\"0\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;		
						case "sale_products":
							out = "[sale_products per_page=\"12\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;	
						case "best_selling_products":
							out = "[best_selling_products per_page=\"12\" columns=\"4\" ]<br />";
						break;	
						case "top_rated_products":
							out = "[top_rated_products per_page=\"12\" columns=\"4\" orderby=\"date\" order=\"desc\"]<br />";
						break;		
						case "product_attribute":
							out = "[product_attribute attribute=\"\" filter=\"\" per_page=\"12\" columns=\"4\" orderby=\"date\" order=\"desc\" ]<br />";
						break;		
						case "related_products":
							out = "[related_products per_page=\"12\" columns=\"4\" orderby=\"date\"]<br />";
						break;							
						<?php } ?>
						default: 
							out = '';
					}
	
					
					tinyMCE.activeEditor.insertContent(out);
					tinyMCE.activeEditor.execCommand('mceRepaint');
					
					jQuery('.select-shortcode').dialog('close'); 
				}
			</script>
			<?php pmc_shortcode_generator()	 ?>
			<script>
				jQuery(function(){
				  
				  var $container = jQuery('#shortcodes_buttons');
				  $container.isotope({
					itemSelector : '#shortcodes_buttons .shortcode-buttons'
				  });
				  
				  
				  var $optionSets = jQuery('#remove'),
					  $optionLinks = $optionSets.find('a');
				  $optionLinks.click(function(){
					var $this = jQuery(this);
					// don't proceed if already selected
					if ( $this.hasClass('selected') ) {
					  return false;
					}
					var $optionSet = $this.parents('#remove');
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
				jQuery(window).on('load', function(){
					$container.isotope('reLayout');
				});
				  
				jQuery( window ).resize(function() {
					$container.isotope('reLayout');
				});
				});
			</script>
		</div>  
	<?php
	
}
/*audio player*/
function wp_audio_player_head() {
	global $pmc_data;	
	
	echo '<script type="text/javascript" src="'.PMC_DIR .'assets/js/audio-player.js"></script>';
	echo '<script type="text/javascript">';
	echo 'AudioPlayer.setup("'.PMC_DIR .'assets/js/player.swf", {';
	echo 'width: 800,animation:"no", bg:"2a2b2c",leftbg:"1e1e20", rightbg:"1e1e20", volslider:"'.removeChar($pmc_data['mainColor']).'", voltrack:"ffffff", lefticon:"ffffff",righticon:"ffffff",skip:"ffffff", loader:"'.removeChar($pmc_data['mainColor']).'",
		 righticonhover:"ffffff", rightbghover:"'.removeChar($pmc_data['mainColor']).'", text:"1e1e20", border:"1e1e20"';
	echo '});</script>';
}
add_action('wp_head','wp_audio_player_head');
function wp_audio_player($atts) {
extract(shortcode_atts(array(
    'no' => rand(0,999),
    'file' => 'http://'
  ), $atts));
	$postmeta = get_post_custom(get_the_id());
	$title = 'Sample title';
	if(isset($postmeta["audio_product_title"][0]))
		$title = $postmeta["audio_product_title"][0];
	if(isset($postmeta["audio_post_title"][0]))
		$title = $postmeta["audio_post_title"][0];	
		
	return '<script type="text/javascript">AudioPlayer.embed("audioplayer_'.$no.'", {soundFile: "'.$file.'",titles: "'.$title.'"});</script><p id="audioplayer_'.$no.'">
			<audio controls="controls">
			<source src="'.$file.'" type="audio/mpeg" />
			Your browser does not support the audio tag.
			</audio>
			</p>';
}
add_shortcode('audio', 'wp_audio_player');
function removeChar($char){
	$char = explode('#',$char);
	return $char[1];
}
//button with icons
function shortcode_button_icon($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'text_color' => '#fff',
		'target' => '',
		'link' => 'http://premiumcoding.com',
	), $atts));
	return '<a href="'.$link.'" target="'.$target.'" style="color:'.$text_color.'"><div class="pmc-button pmc-icon-button" style="background-color:'.$background_color.';border-color:'.$border_color.'"><div class="button-icon-icon"><i class="fa '.$icon.'"></i></div><div class="button_icon-content">'.$content.'</div></div></a>';	
}
add_shortcode('button_icon', 'shortcode_button_icon');
//simple button
function shortcode_button_simple($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'text_color' => '#fff',
		'target' => '',		
		'link' => 'http://premiumcoding.com',
	), $atts));
	return '<div class="pmc-button pmc-simple-button" target="'.$target.'" style="background-color:'.$background_color.';border-color:'.$border_color.'"><a href="'.$link.'" style="color:'.$text_color.'">'.$content.'</a></div>';	
}
add_shortcode('button_simple', 'shortcode_button_simple');
//round button
function shortcode_button_round($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'text_color' => '#fff',
		'target' => '',		
		'link' => 'http://premiumcoding.com',
	), $atts));
	return '<a href="'.$link.'" target="'.$target.'" style="color:'.$text_color.'"><div class="pmc-button pmc-round-button" style="background-color:'.$background_color.';border-color:'.$border_color.'">'.$content.'</div></a>';	
}
add_shortcode('button_round', 'shortcode_button_round');
//simple with icon button
function shortcode_button_simple_icon($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'text_color' => '#fff',
		'target' => '',			
		'link' => 'http://premiumcoding.com',
	), $atts));
	return '<a href="'.$link.'" target="'.$target.'" style="color:'.$text_color.'"><div class="pmc-button pmc-simple-icon-button" style="background-color:'.$background_color.';border-color:'.$border_color.'"><div class="button-icon-icon"><i class="fa '.$icon.'"></i></div><div class="button_icon-content">'.$content.'</div></div></a>';	
}
add_shortcode('button_simple_icon', 'shortcode_button_simple_icon');
//only icon button
function shortcode_button_only_icon($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'text_color' => '#fff',
		'target' => '',			
		'link' => 'http://premiumcoding.com',
	), $atts));
	return '<a href="'.$link.'" target="'.$target.'" style="color:'.$text_color.'"><div class="pmc-button pmc-only-icon-button" style="background-color:'.$background_color.';border-color:'.$border_color.'"><div class="button-icon-icon"><i class="fa '.$icon.'"></i></div></div></a>';	
}
add_shortcode('button_only_icon', 'shortcode_button_only_icon');
//only icon button withot border
function shortcode_button_icon_no_border($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'height' => '14px',
		'color' => $pmc_data['main_color'],
	), $atts));
	return '<div class="pmc-font-icon"><i style="color:'.$color.'; font-size:'.$height.'" class="fa '.$icon.'"></i></div>';	
}
add_shortcode('font_icon', 'shortcode_button_icon_no_border');
//social button
function shortcode_button_social($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'text_color' => '#fff',
		'target' => '',			
		'link' => 'http://premiumcoding.com',
	), $atts));
	return '<a href="'.$link.'" target="'.$target.'" style="color:'.$text_color.'"><div class="pmc-button pmc-social-button" style="background-color:'.$background_color.';border-color:'.$border_color.';"><div class="button-icon-icon"><i class="fa '.$icon.'"></i></div><div class="button_icon-content">'.$content.'</div></div></a>';	
}
add_shortcode('button_social', 'shortcode_button_social');
//simple double button
function shortcode_button_simple_double($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'background_color' => $pmc_data['mainColor'],
		'text_color' => '#fff',
		'target' => '',			
		'link' => 'http://premiumcoding.com',
		'background_color_double' => $pmc_data['mainColor']
	), $atts));
	return '<style scoped>.pmc-double-'.$rand.'.pmc-simple-double-button:before{background:'.$background_color_double.' !important}</style><a href="'.$link.'" target="'.$target.'" style="color:'.$text_color.'"><div class="pmc-button pmc-simple-double-button pmc-double-'.$rand.'" style="background-color:'.$background_color.'"><div class="button-text">'.$content.'</div></div></a>';	
}
add_shortcode('button_simple_double', 'shortcode_button_simple_double');
//simple double button
function shortcode_button_icon_double($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'background_color' => $pmc_data['mainColor'],
		'text_color' => '#fff',
		'target' => '',				
		'link' => 'http://premiumcoding.com',
		'background_color_double' => $pmc_data['mainColor'],
	), $atts));
	return '<style scoped>.pmc-double-'.$rand.'.pmc-simple-double-button:before{background:'.$background_color_double.' !important}</style><a href="'.$link.'" target="'.$target.'" style="color:'.$text_color.'"><div class="pmc-button pmc-simple-double-button pmc-double-'.$rand.'" style="background-color:'.$background_color.'"><div class="button-text"><i class="fa '.$icon.'"></i></div></div></a>';	
}
add_shortcode('button_icon_double', 'shortcode_button_icon_double');
//count block
function shortcode_count_block($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'background_color' => $pmc_data['mainColor'],
		'text_color' => '#fff',
		'number' => 999,
	), $atts));
	return '<div class="pmc-count" style="background-color:'.$background_color.'; color:'.$text_color.'"><div class="pmc-count-icon"><i class="fa '.$icon.'"></i></div><div id="number-'.$rand.'" class="pmc-count-number number-animate">'.$number.'</div><div class="pmc-count-number-border"></div><div class="pmc-count-text">'.$content.'</div></div>';	
}
add_shortcode('count_block', 'shortcode_count_block');
//count block simple
function shortcode_count_block_simple($atts, $content=null){
 global $pmc_data; 
 $rand = rand(0,999);
 extract(shortcode_atts(array(
  'icon' => 'fa-question-circle',
  'background_color' => $pmc_data['mainColor'],
  'text_color' => '#fff',
  'number' => 999,
 ), $atts));
 return '<div class="pmc-count-simple" style="background-color:'.$background_color.'; color:'.$text_color.'"><div class="pmc-count-icon"><i class="fa '.$icon.'"></i></div><div id="number-'.$rand.'" class="pmc-count-number number-animate">'.$number.'</div><div class="pmc-count-number-border"></div><div class="pmc-count-text">'.$content.'</div></div>'; 
}
add_shortcode('count_block_simple', 'shortcode_count_block_simple');
//box
function shortcode_button_box($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,99);
	extract(shortcode_atts(array(
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'text_color' => '#fff'
	), $atts));
	return '<style scoped>.pmc-box.rand-'.$rand .' span, .pmc-box.rand-'.$rand .' p, .pmc-box.rand-'.$rand .' {color: '.$text_color.' !important; }</style><div class="pmc-box rand-'.$rand .' " style="background-color:'.$background_color.';border-color:'.$border_color.';"><div class="pmc-box-text" >'.do_shortcode($content).'</div></div>';	
}
add_shortcode('pmc_box', 'shortcode_button_box');
//quote
function shortcode_button_quote($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'border_color' => $pmc_data['gradient_color']
	), $atts));
	return '<div class="pmc-quote" style="border-color:'.$border_color.';">'.$content.'</div>';	
}
add_shortcode('pmc_quote', 'shortcode_button_quote');
//icon
function shortcode_icon($atts, $content=null){
	global $pmc_data;	
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'background_color' => $pmc_data['mainColor'],
		'border_color' => $pmc_data['gradient_color'],
		'link' => '',
		'target' => '',		
		'size' => 'small',		
	), $atts));
	if($link != ''){$link_text = '<a target="'.$target.'" href="'.$link.'" ><i class="fa '.$icon.'"></i></a>';} else {$link_text = '<i class="fa '.$icon.'"></i>';}	
	if($size != 'small' && $size != 'medium' && $size != 'big' ){$size = 'small';}
	return '<div class="pmc-only-icon" style="background-color:'.$background_color.';border-color:'.$border_color.'"><div class="pmc-icon icon '.$size.'">'.$link_text.'</div></div>';	
}
add_shortcode('pmc_icon', 'shortcode_icon');
//icon box
function shortcode_box_icon($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,9999);
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle',
		'border_color' => $pmc_data['gradient_color'],
		'link' => '',
		'size' => 'small',	
		'icon_location' => 'top',	
		'title' => 'Box with icon',
		'animated' => '',
		'background_color' => $pmc_data['mainColor'],
		'text_color' => '#333'
	), $atts));
	$custom_color = '';
	if($animated != '' ){$animated = 'animated '.$animated; $animated_js = 'animated';}
	if($size != 'small' && $size != 'medium' && $size != 'big' ){$size = 'small';}
	if($icon_location != 'left' && $icon_location != 'top' ){$icon_location = 'top';}
	if($link != ''){$link_text = '<a href="'.$link.'" ><i class="fa '.$icon.'"></i></a>';} else {$link_text = '<i class="fa '.$icon.'"></i>';}
	if($text_color != '#333'){$custom_color = '<style scoped>#rand-'.$rand.'.pmc-icon,#rand-'.$rand.' .pmc-icon-content{color:'.$text_color.'}</style>';}
	return $custom_color.'<div id="rand-'.$rand.'" class="pmc-animate pmc-'.$animated_js.' pmc-icon '.$animated.'"><div style="border-color:'.$border_color.';background:'.$background_color.'" class="pmc-icon-icon '.$size.' '.$icon_location.'">'.$link_text.'</div><div class="pmc-icon-wraper '.$icon_location.' '.$size.'"><div class="pmc-icon-title">'.$title.'</div><div class="pmc-icon-border"></div><div class="pmc-icon-content">'.$content.'</div></div></div>';	
}
add_shortcode('pmc_box_icon', 'shortcode_box_icon');
//progress circle
function shortcode_progress_circle($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'progress' => '75',
		'radius' => '60',
		'progress_border' => '#fff',
		'border_color' => $pmc_data['gradient_color'],
		'background_color' => $pmc_data['mainColor'],
	), $atts));
	return '
	<div id="canvas"><div class="pmc-progress-circle"><div  id="rand-'.$rand.'" class="circle pmc-progress-circle-progress" value="75"></div><div class="pmc-pmc-progress-circle-content">'.$content.'</div></div></div>	
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery( ".circle" ).each(function() {
			var id = jQuery(this).attr("id");
			var circle = jQuery(this).find(".circles-number .circles-number").html();	
			if(jQuery("#"+id).isOnScreen() && typeof circle === "undefined" ){		
				Circles.create({
					id:         "rand-'.$rand.'",
					percentage: '.$progress.',
					radius:     '.$radius.',
					width:       10,
					number:     '.$progress.',
					text:       "%",
					colors:     ["'.$border_color.'", "'.$progress_border.'"],
					duration:   900
				})
			}	
		});	
		jQuery( window ).scroll(function() {
			jQuery( ".circle" ).each(function() {	
				var id = jQuery(this).attr("id");
				var circle = jQuery(this).find(".circles-number .circles-number").html();	
				if(jQuery("#"+id).isOnScreen() && typeof circle === "undefined" ){		
					Circles.create({
						id:         "rand-'.$rand.'",
						percentage: '.$progress.',
						radius:     '.$radius.',
						width:       10,
						number:     '.$progress.',
						text:       "%",
						colors:     ["'.$border_color.'", "'.$progress_border.'"],
						duration:   900
					})
				}
			});
		});
	});		
	</script>';
}
add_shortcode('pmc_progress_circle', 'shortcode_progress_circle');
//image circle 1
function shortcode_image_circle_1($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'border_color_1' => '#000',
		'border_color_2' => $pmc_data['gradient_color'],
		'image' => '',
		'title' => 'HEADING',
		'link' => '',
		'target' => '',			
	), $atts));
	if($link != ''){$link_text_start = '<a target="'.$target.'" href="'.$link.'">'; $link_text_end = '</a>'; }else{$link_text_start = '<a href="#" onclick="return false;">'; $link_text_end = '</a>';}
	return '
	<style scoped>.ih-item.rand-'.$rand.'.circle.effect1 .spinner {border-color:'.$border_color_1.';border-right-color:'.$border_color_2.';border-bottom-color:'.$border_color_2.';}</style>
    <div class="ih-item rand-'.$rand.' circle effect1">'.$link_text_start.'
        <div class="spinner"></div>
        <div class="img"><img src="'.$image.'" alt="img"></div>
        <div class="info">
          <div class="info-back">
            <h3>'.$title.'</h3>
            <p>'.$content.'</p>
          </div>
        </div>'.$link_text_end.'
	</div>';
	}
add_shortcode('image_circle_1', 'shortcode_image_circle_1');
//Responsive Image
if( !function_exists('shortcode_lightbox') ) {
	function shortcode_lightbox($atts, $content=null){
		global $pmc_data;	
		$rand = rand(0,999);
		extract(shortcode_atts(array(
			'image' => '',
			'small_image' => ''
		), $atts));
		return '
		<a rel="lightbox" href="'.$image.'"><img src="'.$small_image.'" /></a>';
		}
	add_shortcode('lightbox', 'shortcode_lightbox');
}
//image square 1
function shortcode_image_square_1($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'animation' => 'bottom_to_top',
		'image' => '',
		'title' => 'HEADING',
		'link' => '',
		'animated' => '',
		'target' => '',					
	), $atts));
	if($link != ''){$link_text_start = '<a target="'.$target.'" href="'.$link.'">'; $link_text_end = '</a>'; }else{$link_text_start = '<a href="#" onclick="return false;">'; $link_text_end = '</a>';}
	return 
	   ' <div class="ih-item rand-'.$rand.'  square effect9 '.$animation.'">'.$link_text_start.'
        <div class="img"><img src="'.$image.'" alt="img"></div>
        <div class="info">
          <div class="info-back">
            <h3>'.$title.'</h3>
            <p>'.$content.'</p>
          </div>
        </div>'.$link_text_end.'</div>';
	}
add_shortcode('image_square_1', 'shortcode_image_square_1');
//image
function shortcode_image($atts, $content=null){
	global $pmc_data;	
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'image' => '',
		'link' => '',
		'animated' => '',
		'icon' => 'fa-picture-o',
		'target' => '',							
	), $atts));
	if($link != ''){$link_text_start = '<a target="'.$target.'" href="'.$link.'">'; $link_text_end = '</a>'; }else{$link_text_start = '<a href="#" onclick="return false;">'; $link_text_end = '</a>';}
	if($animated != '' ){$animated = 'animated '.$animated; $animated_js = 'animated';}
	return '<div class="pmc-image '.$animated.' pmc-'.$animated_js.'" id="rand-'.$rand.'">'.$link_text_start.'<div class="img"><img src="'.$image.'" alt="img"></div><div class="info"><div class="image-over">'.$content.'<div class="image-icon"><i class="fa '.$icon.'"></i></div></div></div>'.$link_text_end.'</div>';
	}
add_shortcode('pmc_image', 'shortcode_image');
//question
function shortcode_question($atts, $content=null){
	extract(shortcode_atts(array(
		'icon' => 'fa-question-circle'
	), $atts));
	return '<div class="question"><i class="fa '.$icon.'"></i>'.$content.'</div>';	
}
add_shortcode('question', 'shortcode_question');
//success
function shortcode_success($atts, $content=null){
	extract(shortcode_atts(array(
		'icon' => 'fa-check-circle'
	), $atts));
	return '<div class="success"><i class="fa '.$icon.'"></i>'.$content.'</div>';	
}
add_shortcode('success', 'shortcode_success');
//info
function shortcode_info($atts, $content=null){
	extract(shortcode_atts(array(
		'icon' => 'fa-info-circle'
	), $atts));
	return '<div class="info"><i class="fa '.$icon.'"></i>'.$content.'</div>';	
}
add_shortcode('info', 'shortcode_info');
//error
function shortcode_error($atts, $content=null){
	extract(shortcode_atts(array(
		'icon' => 'fa-times-circle'
	), $atts));
	return '<div class="error"><i class="fa '.$icon.'"></i>'.$content.'</div>';
}
add_shortcode('error', 'shortcode_error');
//half
function shortcode_half($atts, $content = null){
return '<div class="one_half">' . do_shortcode($content) . '</div>';
}
add_shortcode('half', 'shortcode_half');
//half last
function shortcode_half_last($atts, $content = null){
return '<div class="one_half last">' . do_shortcode($content) . '</div>';
}
add_shortcode('half_last', 'shortcode_half_last');
//one third
function shortcode_onethird($atts, $content=null){
return '<div class="one_third">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_third', 'shortcode_onethird');
//one third last
function shortcode_onethird_last($atts, $content=null){
return '<div class="one_third last">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_third_last', 'shortcode_onethird_last');
//one fourth
function shortcode_onefourth($atts, $content=null){
return '<div class="one_fourth">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fourth', 'shortcode_onefourth');
//one fourth last
function shortcode_onefourth_last($atts, $content=null){
return '<div class="one_fourth last">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fourth_last', 'shortcode_onefourth_last');
//two thirds
function shortcode_twothirds($atts, $content=null){
return '<div class="two_thirds">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_thirds', 'shortcode_twothirds');
function shortcode_twothirds_last($atts, $content=null){
return '<div class="two_thirds last">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_thirds_last', 'shortcode_twothirds_last');
//three fourths
function shortcode_threefourths($atts, $content=null){
return '<div class="three_fourths">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fourths', 'shortcode_threefourths');
//three fourths last 
function shortcode_threefourths_last($atts, $content=null){
return '<div class="three_fourths last">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fourths_last', 'shortcode_threefourths_last');
//one fifth 
function shortcode_onefifth($atts, $content=null){
return '<div class="one_fifth">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fifth', 'shortcode_onefifth');
//one fifth  last 
function shortcode_onefifth_last($atts, $content=null){
return '<div class="one_fifth last">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fifth_last', 'shortcode_onefifth_last');
//four fifths  
function shortcode_fourfifths($atts, $content=null){
return '<div class="four_fifths">' . do_shortcode($content) . '</div>';
}
add_shortcode('four_fifths', 'shortcode_fourfifths');
//four fifths last
function shortcode_fourfifths_last($atts, $content=null){
return '<div class="four_fifths last">' . do_shortcode($content) . '</div>';
}
add_shortcode('four_fifths_last', 'shortcode_fourfifths_last');
//break
function shortcode_break($atts, $content=null){
return '<div class="break clearfix">&nbsp;</div>';
}
add_shortcode('break', 'shortcode_break');
//break with line
function shortcode_break_line($atts, $content=null){
	extract(shortcode_atts(array(
		'border_color' => '#000'
	), $atts));
return '<div style="border-bottom:1px solid '.$border_color.'" class="break_line clearfix">&nbsp;</div>';
}
add_shortcode('break_line', 'shortcode_break_line');
//list arrow
function shortcode_list_arrow($atts, $content=null){
return '<ul class="arrow" >' .$content. '</ul>';
}
add_shortcode('list_arrow', 'shortcode_list_arrow');
//list arrow point
function shortcode_list_arrow_point($atts, $content=null){
return '<ul class="arrow_point" >' .$content. '</ul>';
}
add_shortcode('list_arrow_point', 'shortcode_list_arrow_point');
//list circle
function shortcode_list_circle($atts, $content=null){
return '<ul class="circle" >' .$content. '</ul>';
}
add_shortcode('list_circle', 'shortcode_list_circle');
//list tick
function shortcode_list_tick($atts, $content=null){
return '<ul class="ticklist" >' .$content. '</ul>';
}
add_shortcode('list_tick', 'shortcode_list_tick');
//list comment
function shortcode_list_comment($atts, $content=null){
return '<ul class="commentlistshort" >' .$content. '</ul>';
}
add_shortcode('list_comment', 'shortcode_list_comment');
//list mail
function shortcode_list_mail($atts, $content=null){
return '<ul class="maillist" >' .$content. '</ul>';
}
add_shortcode('list_mail', 'shortcode_list_mail');
//list plus
function shortcode_list_plus($atts, $content=null){
return '<ul class="pluslist" >' .$content. '</ul>';
}
add_shortcode('list_plus', 'shortcode_list_plus');
//list ribbon
function shortcode_list_ribbon($atts, $content=null){
return '<ul class="ribbonlist" >' .$content. '</ul>';
}
add_shortcode('list_ribbon', 'shortcode_list_ribbon');
//list settings
function shortcode_list_settings($atts, $content=null){
return '<ul class="settingslist" >' .$content. '</ul>';
}
add_shortcode('list_settings', 'shortcode_list_settings');
//list star
function shortcode_list_star($atts, $content=null){
return '<ul class="starlist" >' .$content. '</ul>';
}
add_shortcode('list_star', 'shortcode_list_star');
//list image
function shortcode_list_image($atts, $content=null){
return '<ul class="imagelist" >' .$content. '</ul>';
}
add_shortcode('list_image', 'shortcode_list_image');
//list link
function shortcode_list_link($atts, $content=null){
return '<ul class="linklist" >' .$content. '</ul>';
}
add_shortcode('list_link', 'shortcode_list_link');
//text
function shortcode_slogan ($atts, $content=null){
return '<span class="slogan" >' . do_shortcode($content) . '</span>';
}
add_shortcode('slogan', 'shortcode_slogan');
//dropcap
function shortcode_dropcap($atts, $content=null) {
return '<div class="dropcap">' .$content. '</div>';
}
add_shortcode('dropcap', 'shortcode_dropcap');
//progressbar
function shortcode_progressbar($atts, $content = null) {
	extract(shortcode_atts(array(
		"progress" => '',
		"color"=>''
	), $atts));
return '<div class="progressbar ui-widget ui-widget-content ui-corner-all">
   <div style="width: '.$progress.'%; background-color:'.$color.'" class="ui-progressbar-value ui-widget-header ui-corner-left"><div class="progressbar-title">'.do_shortcode($content).'</div></div>
</div>';
}
add_shortcode('progressbar', 'shortcode_progressbar');
//toggle
function shortcode_toggle($atts, $content = null){
	extract(shortcode_atts(array(
		'title' => ''
	), $atts));
return '<div class="block"><h2 class="trigger">'.$title.'</h2>
<div class="toggle_container">' . do_shortcode($content) . '</div></div>';
}
add_shortcode('toggle', 'shortcode_toggle');
//accordion
add_shortcode( 'accordion', 'short_accordions' );
function short_accordions( $atts, $content ){
$GLOBALS['atab_count'] = 0;
do_shortcode( $content );
$content = '';
if( is_array( $GLOBALS['atabs'] ) ){
foreach( $GLOBALS['atabs'] as $tab ){
$content .= '<h3><i class="fa fa-plus"></i><i class="fa fa-minus"></i>'.$tab['title'].'</h3>';
$content .= '<div><p>'.$tab['content'].'</p></div>';
}
$return = '<div class="accordion">'.do_shortcode($content).'</div>'."\n";
}
return $return;
}
add_shortcode( 'atab', 'short_accordion' );
function short_accordion( $atts, $content ){
extract(shortcode_atts(array(
'title' => 'Accordion tab %d'
), $atts));
$x = $GLOBALS['atab_count'];
$GLOBALS['atabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['atab_count'] ), 'content' =>  $content );
$GLOBALS['atab_count']++;
}
//tabs
add_shortcode( 'tabgroup', 'fl_tabs' );
function fl_tabs( $atts, $content ){
$GLOBALS['tab_count'] = 0;
do_shortcode( $content );
if( is_array( $GLOBALS['tabs'] ) ){+$i=0;
foreach( $GLOBALS['tabs'] as $tab ){
$tabs[] = '<li><a href="#tabs-'.$i.'">'.$tab['title'].'</a></li>';
$panes[] = '<div  id="tabs-'.$i.'">'.do_shortcode($tab['content']).'</div>';$i++;
}
$return = "\n".'<!-- the tabs --><div class="tabs"><ul>'.implode( "\n", $tabs ).'</ul>'.implode( "\n", $panes ).'</div>'."\n";
}
return $return;
}
add_shortcode( 'tab', 'fl_tab' );
function fl_tab( $atts, $content ){
extract(shortcode_atts(array(
'title' => 'Tab %d'
), $atts));
$x = $GLOBALS['tab_count'];
$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'content' =>  $content );
$GLOBALS['tab_count']++;
}
//pricing tabels simple
function shortcode_pricing_tabels( $atts, $content ){
	global $pmc_data;
	$rand = rand(0,999);
	extract(shortcode_atts(array(
		'title' => 'Pricing Title',
		'price' => '$999.99',	
		'price_title' => 'per month',	
		'button' => 'SIGN UP',
		'button_link' => 'http://premiumcoding.com',
		'background_color' => $pmc_data['mainColor'],
		'width' => '',
		'highlighted' => 'false'		
	), $atts));
	
	$GLOBALS['options_count'] = 0;
	
	do_shortcode( $content );
	if($width != ''){$width = '<style>.pricing-tabel-'.$rand.'{width:'.$width.';border-radius:0;min-width:0;}</style>';}
	if($highlighted != 'false'){$highlighted = 'highlighted';} else {$highlighted = '';}
	if( is_array( $GLOBALS['options'] ) ){
	+$i=0;
	foreach( $GLOBALS['options'] as $tabel){
	$options[] = '<li class="pricing-options" style="background-color:'.$tabel['background_color_option'].'">'.$tabel['content'].'</li>';
	}
	$return = $width.'<div style="background-color:'.$background_color.'" class="pricing-tabel pricing-tabel-'.$rand.' '.$highlighted.'"><div class="pricing-tabel-title">'.$title.'</div><div class="pricing-tabel-price">'.$price.'<div class="pricing-tabel-price-title">'.$price_title.'</div></div><ul>'.implode( "\n", $options ).'</ul><a href="'.$button_link.'"><div class="pricing-tabel-button">'.$button.'</div></a></div>'."\n";
				
	}
	return $return;
}
add_shortcode( 'pricing_tabels', 'shortcode_pricing_tabels' );
//pricing table options
function shortcode_pricing_options( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
		'background_color' => $pmc_data['mainColor']
	), $atts));
	$x = $GLOBALS['options_count'];
	$GLOBALS['options'][$x] = array('background_color_option' => $background_color ,'content' =>  $content);
	$GLOBALS['options_count']++;
}
add_shortcode( 'pricing_options', 'shortcode_pricing_options' );
//pricing tabels icon
function shortcode_pricing_tabels_icon( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
		'title' => 'Pricing Title',
		'price' => '$999.99',	
		'price_title' => 'per month',	
		'button' => 'SIGN UP',
		'button_link' => 'http://premiumcoding.com',
		'background_color' => $pmc_data['mainColor'],
		'icon' => ''
	
	), $atts));
	
	$GLOBALS['options_count_icon'] = 0;
	do_shortcode( $content );
	if( is_array( $GLOBALS['options_icon'] ) ){
	+$i=0;
	foreach( $GLOBALS['options_icon'] as $tabel){
	$options[] = '<li class="pricing-options" style="background-color:'.$tabel['background_color_option'].'">'.$tabel['content'].'</li>';
	}
	$return = '<div style="background-color:'.$background_color.'" class="pricing-tabel pricing-tabel-icon"><div class="pricing-tabel-price"><div class="pricing-tabel-price price">'.$price.'</div><img src="'.$icon.'" /><div class="pricing-tabel-price-title">'.$price_title.'</div></div><div class="pricing-tabel-title">'.$title.'</div><ul>'.implode( "\n", $options ).'</ul><a href="'.$button_link.'"><div class="pricing-tabel-button">'.$button.'</div></a></div>'."\n";
				
	}
	return $return;
}
add_shortcode( 'pricing_tabels_icon', 'shortcode_pricing_tabels_icon' );
//pricing table options
function shortcode_pricing_options_icon( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
	'background_color' => $pmc_data['mainColor'] 
	), $atts));
	$x = $GLOBALS['options_count_icon'];
	$GLOBALS['options_icon'][$x] = array('background_color_option' => $background_color ,'content' =>  $content );
	$GLOBALS['options_count_icon']++;
}
add_shortcode( 'pricing_options_icon', 'shortcode_pricing_options_icon' );
//pricing tabels cicrle
function shortcode_pricing_tabels_circle( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
		'title' => 'Pricing Title',
		'price' => '$999.99',	
		'price_title' => 'per month',	
		'button' => 'SIGN UP',
		'button_link' => 'http://premiumcoding.com',
		'background_color' => $pmc_data['mainColor'],
		'background_color_circle' => $pmc_data['mainColor'],
		'border_color_circle' => $pmc_data['mainColor'] 		
	), $atts));
	
	$GLOBALS['options_count_circle'] = 0;
	do_shortcode( $content );
	if( is_array( $GLOBALS['options_circle'] ) ){
	+$i=0;
	foreach( $GLOBALS['options_circle'] as $tabel){
	$options[] = '<li class="pricing-options" style="background-color:'.$tabel['background_color_option'].'">'.$tabel['content'].'</li>';
	}
	$return = '<div style="background-color:'.$background_color.'" class="pricing-tabel pricing-tabel-circle"><div class="pricing-tabel-price" style="background: '.$background_color_circle.'; border-color:'.$border_color_circle.'">'.$price.'<div class="pricing-tabel-price-title">'.$price_title.'</div></div><div class="pricing-tabel-title">'.$title.'</div><ul>'.implode( "\n", $options ).'</ul><a href="'.$button_link.'"><div class="pricing-tabel-button">'.$button.'</div></a></div>'."\n";
				
	}
	return $return;
}
add_shortcode( 'pricing_tabels_circle', 'shortcode_pricing_tabels_circle' );
//pricing table options
function shortcode_pricing_options_circle( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
	'background_color' => $pmc_data['mainColor'] 
	), $atts));
	$x = $GLOBALS['options_count_circle'];
	$GLOBALS['options_circle'][$x] = array('background_color_option' => $background_color ,'content' =>  $content );
	$GLOBALS['options_count_circle']++;
}
add_shortcode( 'pricing_options_circle', 'shortcode_pricing_options_circle' );
//pricing tabels white
function shortcode_pricing_tabels_white( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
		'title' => 'Pricing Title',
		'price' => '$999.99',	
		'price_title' => 'per month',	
		'button' => 'SIGN UP',
		'button_link' => 'http://premiumcoding.com',
		'background_color_circle' => $pmc_data['mainColor'],
		'border_color_circle' => $pmc_data['mainColor'],
		'button_color' => $pmc_data['mainColor']
	), $atts));
	
	$GLOBALS['options_count_white'] = 0;
	do_shortcode( $content );
	if( is_array( $GLOBALS['options_white'] ) ){
	+$i=0;
	foreach( $GLOBALS['options_white'] as $tabel){
	$options[] = '<li style = "color:'.$tabel['text_color'].'"class="pricing-options" >'.$tabel['content'].'</li>';
	$text_color = $tabel['text_color'];
	}
	$return = '<div class="pricing-tabel pricing-tabel-white"><div class="pricing-tabel-price" style="background: '.$background_color_circle.'; border-color:'.$border_color_circle.'">'.$price.'<div class="pricing-tabel-price-title" >'.$price_title.'</div></div><div class="pricing-tabel-title" style="color:'.$text_color .'">'.$title.'</div><ul>'.implode( "\n", $options ).'</ul><a href="'.$button_link.'"><div class="pricing-tabel-button" style="background:'.$button_color.'">'.$button.'</div></a></div>'."\n";
				
	}
	return $return;
}
add_shortcode( 'pricing_tabels_white', 'shortcode_pricing_tabels_white' );
//pricing table options
function shortcode_pricing_options_white( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
	'text_color' => '#000' 
	), $atts));	
	$x = $GLOBALS['options_count_white'];
	$GLOBALS['options_white'][$x] = array('text_color' => $text_color, 'content' =>  $content );
	$GLOBALS['options_count_white']++;
}
add_shortcode( 'pricing_options_white', 'shortcode_pricing_options_white' );
/*-----------------------------------------------------------------------------------*/
/* Google Maps Shortcode
/*-----------------------------------------------------------------------------------*/
function get_map_coordinates($address, $force_refresh = false) {
    $address_hash = md5( $address );
    $coordinates = get_transient( $address_hash );
    if ($force_refresh || $coordinates === false) {
    	$args       = array( 'address' => urlencode( $address ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );
     	if( is_wp_error( $response ) )
     		return;
     	$pmc_data = wp_remote_retrieve_body( $response );
     	if( is_wp_error( $pmc_data ) )
     		return;
		if ( $response['response']['code'] == 200 ) {
			$pmc_data = json_decode( $pmc_data );
			if ( $pmc_data->status === 'OK' ) {
			  	$coordinates = $pmc_data->results[0]->geometry->location;
			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $pmc_data->results[0]->formatted_address;
			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$pmc_data = $cache_value;
			} elseif ( $pmc_data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'pmc-themes' );
			} elseif( $pmc_data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'pmc-themes' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'pmc-themes' );
			}
		} else {
		 	return __( 'Unable to contact Google API service.', 'pmc-themes' );
		}
    } else {
       // return cached results
       $pmc_data = $coordinates;
    }
    return $pmc_data;
}
//pricing table options
function shortcode_google_map( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
		'address' => 'Slovenia, Ljubljana',
		'height' => 500,	
		'width' => '',
		'zoom' => 8,
		'style' => '',
		'style' => '',
		'image' => $pmc_data['logo'],
		'bounce' => 'true',
		'full_width' => 'false'
	), $atts));
	wp_print_scripts( 'googlemap' );
	if($width == ''){$width = '100%';}else{$width = $width.'px';}
	$coordinates = get_map_coordinates( $address );
	if( !is_array( $coordinates ) )
		return;
	$map_id = uniqid( 'pw_map_' ); // generate a unique ID for this map
	ob_start(); 
	if($style != ''){ 
		$style = 'styles: '. gogole_style($style);
	 } 
	$animation = $icon = '';
	if($bounce == 'true'){ 
	$animation = 'animation:google.maps.Animation.BOUNCE,';
	 } 
	if($image != ''){ 
	$icon = 'icon: "'.$image.'",';
	} 	
	$full_width_class = '';
	if($full_width == 'false'){ $full_width_class = 'border'; }
	$return = '<div class="pmc-google-map '.$full_width_class.'" style="width: '. $width .'">
		<div class="pw_map_canvas" id="'. esc_attr( $map_id ).'" style="height: '. $height .'px; width: '. $width .'"></div>
		<script type="text/javascript">
			var map_'. $map_id.';
			function pw_run_map_'. $map_id .'(){
				var location = new google.maps.LatLng("'. $coordinates['lat'].'", "'. $coordinates['lng'].'");
				var map_options = {
					zoom: '. $zoom .',
					center: location,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					'.$style.'
				}
				
				map_'. $map_id .' = new google.maps.Map(document.getElementById("'. $map_id .'"), map_options);
				var marker = new google.maps.Marker({
				position: location,		
				'.$animation.'
				'.$icon.'
				map: map_'. $map_id .'
				});
			}
			pw_run_map_'. $map_id .'();
		</script>
	</div>';
	
	return $return;
}
add_shortcode( 'google_map', 'shortcode_google_map' );


function shortcode_google_map_more( $atts, $content ){
	global $pmc_data;
	extract(shortcode_atts(array(
		'address' => '46.0569465,14.5057515|45.9665830,14.2973873',
		'height' => 500,	
		'width' => '',
		'zoom' => 8,
		'style' => '',
		'style' => '',
		'image' => $pmc_data['logo'],
		'bounce' => 'true',
		'full_width' => 'false'
	), $atts));
	$address = explode('|',$address);

	wp_print_scripts( 'googlemap' );
	if($width == ''){$width = '100%';}else{$width = $width.'px';}
	$coordinates = array();
	$map_id = uniqid( 'pw_map_' ); // generate a unique ID for this map
	ob_start(); 
	if($style != ''){ 
		$style = 'styles: '. gogole_style($style);
	 } 
	$animation = $icon = '';
	if($bounce == 'true'){ 
	$animation = 'animation:google.maps.Animation.BOUNCE,';
	 } 
	if($image != ''){ 
	$icon = 'icon: "'.$image.'",';
	} 	
	$full_width_class = '';
	$gogole_map = '';
	$default_address =  explode(',',$address[1]);
	if($full_width == 'false'){ $full_width_class = 'border'; }
	$gogole_map = '<div class="pmc-google-map '.$full_width_class.'" style="width: '. $width .'">
		<div class="pw_map_canvas" id="'. esc_attr( $map_id ).'" style="height: '. $height .'px; width: '. $width .'"></div>
		<script type="text/javascript">
			var LatLng = new google.maps.LatLng('.$default_address[0].', '.$default_address[1].');
			var map_'. $map_id.';
			function pw_run_map_'. $map_id .'(){
				var map_options = {
					zoom: '. $zoom .',
					center: LatLng,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					'.$style.'
				}
				var infowindow = new google.maps.InfoWindow(), marker, i;
				map_'. $map_id .' = new google.maps.Map(document.getElementById("'. $map_id .'"), map_options);';
				foreach($address as $cord){
				$cord_out = explode(',', $cord);
				$gogole_map .=
					'var marker = new google.maps.Marker({
					position:  new google.maps.LatLng('.$cord_out[0].', '.$cord_out[1].'),	
					'.$animation.'
					'.$icon.'
					map: map_'. $map_id .'
					});';
				$i++;
				}
				$gogole_map .= '		
			}
			pw_run_map_'. $map_id .'();
		</script>
	</div>';
	
	return $gogole_map;
}
add_shortcode( 'google_map_more', 'shortcode_google_map_more' );
//scroll link
function shortcode_scroll_link($atts, $content=null){
	extract(shortcode_atts(array(
		'link' => 'fa-check-circle'
	), $atts));
	return '<div class="pmc-button"><a href="'.$link.'">'.$content.'</a></div>';	
}
add_shortcode('scroll_link', 'shortcode_scroll_link');
?>

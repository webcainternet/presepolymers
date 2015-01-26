 <?php
/** 
 * Builder Page
 *
 * @description Main admin UI settings page
 * @package Aqua Page Builder
 *
 */
 
// Debugging
if(isset($_POST) && $this->args['debug'] == true) {
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
}

// Permissions Check
if ( ! current_user_can('edit_theme_options') )
	wp_die( __( 'Cheatin&#8217; uh?' ) );
	
$messages = array();

// Get selected template id
$selected_template_id = isset($_REQUEST['template']) ? (int) $_REQUEST['template'] : 0;

// Actions
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'edit';
$template = isset($_REQUEST['template']) ? $_REQUEST['template'] : 0;

// DEBUG
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

// Template title & layout
$template_name = isset($_REQUEST['template-name']) && !empty($_REQUEST['template-name']) ? htmlspecialchars($_REQUEST['template-name']) : 'No Title';

// Get all templates
$templates = $this->get_templates();

// Get recently edited template
$recently_edited_template = (int) get_user_option( 'recently_edited_template' );

if( ! isset( $_REQUEST['template'] ) && $recently_edited_template && $this->is_template( $recently_edited_template )) {
	$selected_template_id = $recently_edited_template;
} elseif ( ! isset( $_REQUEST['template'] ) && $selected_template_id == 0 && !empty($templates)) {
	$selected_template_id = $templates[0]->ID;
}

//define selected template object
$selected_template_object = get_post($selected_template_id);

// saving action
switch($action) {

	case 'create' :
		
		$new_id = $this->create_template($template_name);
		
		if(!is_wp_error($new_id)) {
			$selected_template_id = $new_id;
		
			//refresh templates var
			$templates = $this->get_templates();
			$selected_template_object = get_post($selected_template_id);
			
			$messages[] = '<div id="message" class="updated"><p>' . __('The ', 'pmc-themes') . '<strong>' . $template_name . '</strong>' . __(' page template has been successfully created', 'pmc-themes') . '</p></div>';
		} else {
			$errors = '<ul>';
			foreach( $new_id->get_error_messages() as $error ) {
				$errors .= '<li><strong>'. $error . '</strong></li>';
			}
			$errors .= '</ul>';
			
			$messages[] = '<div id="message" class="error"><p>' . __('Sorry, the operation was unsuccessful for the following reason(s): ', 'pmc-themes') . '</p>' . $errors . '</div>';
		}
		
		break;
		
	case 'update' :
	
		$blocks = isset($_REQUEST['aq_blocks']) ? $_REQUEST['aq_blocks'] : '';
		
		$this->update_template($selected_template_id, $blocks, $template_name);
		
		//refresh templates var
		$templates = $this->get_templates();
		$selected_template_object = get_post($selected_template_id);
		
		$messages[] = '<div id="message" class="updated"><p>' . __('The ', 'pmc-themes') . '<strong>' . $template_name . '</strong>' . __(' page template has been updated', 'pmc-themes') . '</p></div>';
		break;
		
	case 'delete' :
		
		$this->delete_template($selected_template_id);
		
		//refresh templates var
		$templates = $this->get_templates();
		$selected_template_id =	!empty($templates) ? $templates[0]->ID : 0;
		$selected_template_object = get_post($selected_template_id);
		
		$messages[] = '<div id="message" class="updated"><p>' . __('The template has been successfully deleted', 'pmc-themes') . '</p></div>';
		break;
		
	case 'copy' :
		$blocks = isset($_REQUEST['aq_blocks']) ? $_REQUEST['aq_blocks'] : '';
		$new_id = $this->duplicate_template($selected_template_id,$blocks,$template_name);
		

		
		if(!is_wp_error($new_id)) {
			$selected_template_id = $new_id;
		
			//refresh templates var
			$templates = $this->get_templates();
			$selected_template_object = get_post($selected_template_id);
			
			$messages[] = '<div id="message" class="updated"><p>' . __('The ', 'pmc-themes') . '<strong>' . $template_name . '</strong>' . __(' page template has been successfully created', 'pmc-themes') . '</p></div>';
		} else {
			$errors = '<ul>';
			foreach( $new_id->get_error_messages() as $error ) {
				$errors .= '<li><strong>'. $error . '</strong></li>';
			}
			$errors .= '</ul>';
			
			$messages[] = '<div id="message" class="error"><p>' . __('Sorry, the operation was unsuccessful for the following reason(s): ', 'pmc-themes') . '</p>' . $errors . '</div>';
		}
		
		break;
}

global $current_user;
update_user_option($current_user->ID, 'recently_edited_template', $selected_template_id);

//display admin notices & messages
if(!empty($messages)) foreach($messages as $message) { echo $message; }

//disable blocks archive if no template
$disabled = $selected_template_id === 0 ? 'metabox-holder-disabled' : '';

?>

<div class="wrap">
	<div id="icon-themes" class="icon32"><br/></div>		
	<h2><i class="fa fa-cubes fa-2x"></i><?php echo $this->args['page_title'] ?></h2>	
	<div class="live-preview">
		<div class="live-preview-button">
		<i class="fa fa-chevron-down"></i>
		<i class="fa fa-chevron-up"></i>
		Live Preview
		</div>
		<div class="live-preview-iframe">
			<iframe id="iframe" src="" width="100%" height="800px"></iframe>
		</div>
		<input id="pmc-plugin-url-live" type="hidden" value="<?php echo plugins_url() ?>/page-builder-pmc/view/live-preview.php?ID=<?php echo $selected_template_id ?>">		
	</div>	
	<div id="page-builder-frame">
	
		<div id="page-builder-column" class="metabox-holder <?php echo $disabled ?>">
			<div id="page-builder-archive" class="postbox">
				<h3 class="hndle"><span><?php _e('Available Blocks', 'pmc-themes') ?></span><span id="removing-block"><?php _e('Deleting', 'pmc-themes') ?></span></h3>				<p>* Drag & Drop the blocks to the page builder below to start building. Or select the categories first.</p>
				<div class="inside">
						<?php $this->blocks_archive() ?>
				</div>			
			</div>			
		</div>

		<div id="saving-bar"><i class="fa fa-spinner fa-spin fa-3x"></i><br><span>Saving...</span></div>
		<div id="page-builder-fixed">
			<?php if(has_nav_menu( 'pagebuildermenu' ) ){ ?>
				<div id="headerwrap" class="pagenav">
					<?php						
						wp_nav_menu( array(
						'container' =>false,
						'container_class' => 'menu-builder',
						'theme_location' => 'pagebuildermenu',
						'echo' => true,
						'fallback_cb' => 'opus_fallback_menu',
						'before' => '',
						'after' => '',
						'link_before' => '',
						'link_after' => '',
						'depth' => 0,
						'walker' => new pmc_Walker_Builder()));
										
					?>
				</div>	
			<?php } ?>
			<div id="page-builder">
				<div class="aqpb-tabs-nav">
				
					<div class="aqpb-tabs-arrow aqpb-tabs-arrow-left">
						<a>&laquo;</a>
					</div>
					
					<div class="aqpb-tabs-wrapper">
						<div class="aqpb-tabs">
							
							<?php
							foreach ( (array) $templates as $template ) {
								if($selected_template_id == $template->ID) {
									echo '<span class="aqpb-tab aqpb-tab-active aqpb-tab-sortable">'. htmlspecialchars($template->post_title) .'</span>';
								} else {
									echo '<a class="aqpb-tab aqpb-tab-sortable" data-template_id="'.$template->ID.'" href="' . esc_url(add_query_arg(
										array(
											'page' => $this->args['page_slug'], 
											'action' => 'edit',
											'template' => $template->ID,
										),
										admin_url( 'themes.php' )
									)) . '">'. htmlspecialchars($template->post_title) .'</a>';
								}
							}
							?>
							

							
						</div>
					</div>
					
					<div class="aqpb-tabs-arrow aqpb-tabs-arrow-right">
						<a>&raquo;</a>
					</div>
					
				</div>
				<div class="aqpb-wrap aqpbdiv">
					<form id="update-page-template" action="<?php echo $this->args['page_url'] ?>" method="post" enctype="multipart/form-data">
						<div id="aqpb-header">
							
								<div id="submitpost" class="submitbox">
									<div class="major-publishing-actions cf">
									
										<label class="open-label" for="template-name">
											<span><?php _e('Template Name', 'pmc-themes') ?></span>
											<input name="template-name" id="template-name" type="text" class="template-name regular-text" title="Enter template name here" placeholder="Enter template name here" value="<?php echo is_object($selected_template_object) ? $selected_template_object->post_title : ''; ?>">
										</label>
									
										
										<div class="publishing-action">
											<i class="fa fa-floppy-o"></i>
											<?php submit_button( empty( $selected_template_id ) ? __( 'Create Template', 'pmc-themes' ) : 'Save Template', 'button-primary ', 'save_template', false, array( 'id' => 'save_template_header' ) ); ?>
										</div><!-- END .publishing-action -->
										<!--add new template button-->										
										<?php if(!empty($selected_template_id)) { ?>										
										<a class="aqpb-tab aqpb-tab-add" href="<?php											
										echo esc_url(add_query_arg(												
										array(													
										'page' => $this->args['page_slug'], 													
										'action' => 'edit',													
										'template' => 0,												
										),												
										admin_url( 'themes.php' )											
										));										
										?>" title="'. __('Add Template', 'pmc-themes') .'">											
										<i class="fa fa-plus" ></i>										
										</a>																				
										<?php } ?>		
										<?php if(!empty($selected_template_id)) { ?>
										<div class="delete-action">
											<?php 
											echo '<a class="submitdelete deletion template-delete" href="' . esc_url(add_query_arg(
												array(
													'page' => $this->args['page_slug'], 
													'action' => 'delete',
													'template' => $selected_template_id,
													'_wpnonce' => wp_create_nonce('delete-template'),
												),
												admin_url( 'themes.php' )
											)) . '" title="'. __('Delete Template', 'pmc-themes') .'"><i class="fa fa-times"></i></a>';
											?>
										</div><!-- END .delete-action -->								
										<div class="delete-action">
											<?php 
											echo '<a class="template-duplicate" title="'. __('Duplicate Template', 'pmc-themes') .'"><i class="fa fa-files-o"></i></a>';
											?>
										</div><!-- END .delete-action -->											
										
										
										<?php } ?>
										
									</div><!-- END .major-publishing-actions -->
								</div><!-- END #submitpost .submitbox -->
								
								<?php 
								if($selected_template_id === 0) {
									wp_nonce_field( 'create-template', 'create-template-nonce' ); 
								} else {
									wp_nonce_field( 'update-template', 'update-template-nonce' );
								}
								?>	
								<input type="hidden" id="action" name="action" value="<?php echo empty( $selected_template_id ) ? 'create' : 'update' ?>"/>
								<input type="hidden" name="template" id="template" value="<?php echo $selected_template_id ?>"/>
								<input type="hidden" id="aqpb-nonce" name="aqpb-nonce" value="<?php echo wp_create_nonce('aqpb-settings-page-nonce') ?>"/>
							
						</div>
						
						<div id="aqpb-body">

							<ul class="blocks cf" id="blocks-to-edit">
								<?php 
								if($selected_template_id === 0) {
									echo '<p class="empty-template">';
									echo __('To create a custom page template, give it a name above and click Create Template. Then choose blocks like text, widgets or tabs &amp; toggles from the left column to add to this template.
									<br/><br/>
									You can drag and drop the blocks to put them in the order you want. Click on the small arrow at the corner of each block to reveal additional configuration options. You can also resize each block by clicking on either side of the block (Note that some blocks are not resizable)
									<br/><br/>
									When you have finished building your custom page template, make sure you click the Save Template button.', 'pmc-themes');
									echo '</p>';
									
									
								} else {
									$this->display_blocks($selected_template_id); 
								}
								?>
							</ul>
							
						</div>
						
						<div id="aqpb-footer">
							<div class="major-publishing-actions cf">
								<div class="publishing-action">
									<i class="fa fa-floppy-o"></i>
									<?php if(!empty($selected_template_id)) {
										submit_button( __( 'Save Template' ), 'button-primary ', 'save_template', false, array( 'id' => 'save_template_footer' ) ); 
										
									} ?>
									
								</div><!-- END .publishing-action -->
							</div><!-- END .major-publishing-actions -->
						</div>
						
					</div>
				</form>

			</div>
			<p style="float:left"><small>PremiumCoding Page Builder &copy; 2014 by <a href="http://premiumcoding.com">PremiumCoding</a></small></p>
			<p style="float:right"><small>Version 1.0</small></p>
			
		</div>
		
		
	</div>	
	<div class="show-column">		
		<h2><a target="_blank" href="http://premiumcoding.com">PMC Page Builder</a></h2>		
		<div class = "builder-button"><i class="fa fa-plus"></i>Add new Block</div>		
		<div class="save-button">			
			<i class="fa fa-floppy-o"></i>

			<?php submit_button( empty( $selected_template_id ) ? __( 'Create Template', 'pmc-themes' ) : 'Save Template', 'button-primary ', 'save_template', false, array( 'id' => 'save_template_fixed' ) ); ?>	
	
		</div>					
		<div class = "builder-button close"><i class="fa fa-times"></i>Close Blocks Panel</div>		
	</div>
</div>
<input id="pmc-plugin-url" type="hidden" value="<?php echo plugins_url() ?>/page-builder-pmc/classes/">
<input id="pmc-show-shop" type="hidden" value="<?php echo PMC_SHOP ?>">
<?php pmc_shortcode_generator() ?>
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
	  });/*
	jQuery(window).on('load', function(){
		$container.isotope('reLayout');
	});
	  
	jQuery( window ).resize(function() {
		$container.isotope('reLayout');
	});*/

	});
</script>

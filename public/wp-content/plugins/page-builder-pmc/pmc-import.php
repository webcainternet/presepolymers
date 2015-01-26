<?php

defined( 'ABSPATH' ) or die( 'You cannot access this script directly' );
if(!defined('PMC_SHOP')) define( 'PMC_SHOP', true );

/*importer*/
	
	function pmc_importer() { ?>
		
		<div id="pmc-importer" class="wrap">
	
            <h2>THEME DATA IMPORTER</h2>

            
			<?php 
			
			/*
			|--------------------------------------------------------------------------
			| Notifications
			|--------------------------------------------------------------------------
			*/
			if( file_exists( ABSPATH . 'wp-content/uploads/' ) ) {
				
				/* wp-content upload folder not writeable  */ 
				if( !pmc_check_folder( ABSPATH . 'wp-content/uploads/' ) ) :
				
					echo '<div class="error"><p>';
						
						echo '<strong>Your upload folder is not writeable! The importer won\'t be able to import the placeholder images. Please check the folder permissions for</strong><br />';
						echo ABSPATH . 'wp-content/uploads/';
						
					echo '</p></div>';
					
				endif;
				
			
			} else {
			
				/* wp-content folder not writeable  */ 
				if( !pmc_check_folder( ABSPATH . 'wp-content/uploads/' ) ) :
					
					echo '<div class="error"><p>';
					
						echo '<strong>Your wp-content folder is not writeable! The importer won\'t be able to import the placeholder images. Please check the folder permissions for</strong><br />';
						echo ABSPATH . 'wp-content/';
					
					echo '</p></div>';
					
				endif;
			
			}
			
			
			/* importer has been used before */
			if( get_option('pmc-import-exist') == 'true' ) :
				
				echo '<div class="error"><p>You already have imported the demo content before. Running this operation twice will result in double content!</p></div>';
			
			endif;
			
			/* import was successful */
			if( isset($_GET['pmc-import-success']) && $_GET['pmc-import-success'] == 'success' ) : 
				
				echo '<div class="updated"><p>Import was successful, have fun!</p></div>';
			
			endif; 

			if ( ! class_exists('Widget_Data_PMC') ) { 
				$class_widget_import = AQPB_PATH . 'assets/import/plugins/class-widget-data.php';
				if ( file_exists( $class_widget_import ) ) {
					include $class_widget_import;
				}
				
			}			
			
			?>
			<div class = "pmc-importer-notes">
				<h4>IMPORTANT - READ FIRST</h4>
			</div>
                <ol>
                    <li>We recommend you run our import on a clean WordPress installation to avoid unnecessary complications.</li>
					 <li>All of your posts and pages will be deleted, so use this only on clean install !!!!</li>
                    <li>To reset your installation we recommend the following plugin: <a href="http://wordpress.org/plugins/wordpress-database-reset/">Wordpress Database Reset</a></li>
                    <li>Never run the importer more then once as it will result in extra (double) content.</li>
					<li>If you have any doubts whether you should run importer or not, do not hesitate to <a target="_blank" href = "https://premiumcoding.zendesk.com/anonymous_requests/new">contact us.</a></li>
                </ol>
			<div class = "pmc-importer-notes">
				<h4>IMPORTANT WOOCOMMERCE NOTICE - READ BEFORE IMPORTING</h4>
			</div>
				<ol>
                    <li>If you are importing WooCommerce version you need to install WooCommerce plugin before importing !!!!</li>
                </ol>				
            
            <form id="pmc-import" method="POST" action="?page=pmc_importer">
            
            <div class="import-theme">
                <label class="pmc-choose-demo-img" for="pmc_file_one">
                    <a target="_blank" title="Ecorecycle Theme Preview" href="http://ecorecycle.premiumcoding.com/" ><img src="<?php echo plugins_url() ?>/page-builder-pmc/assets/import/images/pmc-preview.jpg" /></a>                
                </label>
				<div class = "import-theme-title">
					<input type="radio" id="pmc_file_one" name="pmc_file" value="pmc_file_one" checked class="pmc-demo-file-select">
					<h3 span="import-theme-name">Ecorecycle theme</h3>
					<div class="import-theme-actions">
						<a target="_blank" href="http://ecorecycle.premiumcoding.com/" class="button button-primary">Preview</a>
					</div>
				</div>           
            </div>
  
            <div class="import-theme">
                <label class="pcm-choose-demo-img" for="pmc_file_two">
                    <a target="_blank" title="Eco theme Woocommerce version" href="http://ecoshop.premiumcoding.com/"><img src="<?php echo plugins_url() ?>/page-builder-pmc/assets/import/images/pmc-preview-1.jpg" /></a>                 
                </label>
                <div class = "import-theme-title">
					<input type="radio" id="pmc_file_two" name="pmc_file" value="pmc_file_two"  class="pmc-demo-file-select">
					<h3 span="import-theme-name">Eco Woocommerce version</h3>
					<div class="import-theme-actions">
						<a target="_blank" href="http://ecoshop.premiumcoding.com/" class="button button-primary">Preview</a>
					</div>
				</div>
            </div>	  

			
            <div class="pmc-importer-options">
            
            <div class="form-table">
            	
                <div class ="pmc-rev-slider-import-title">
					<h3>REVOLUTION SLIDER</h3>
					<p class="widget-selection-error">*Theme comes with Revolution Slider. Check the box below if you wish to import it.</p>
				</div>
				<div class="pmc-rev-slider-import">  
					 <input type="checkbox" value="yes" checked id="pmc-import-revslider" name="pmc-import-revslider">
						Import Revolution Sliders?
                </div>     
                    <?php
					$file_widget = AQPB_DIR . 'assets/import/widget/widget.json';
					$class_widget_import = new Widget_Data_PMC();
					$class_widget_import->import_settings_page($file_widget);					
					?>
            </div>
            
            </div>
            
            <div class="pmc-import-bar">
                
                <input type="hidden" name="pmc_import_demo" value="true" />
                <input type="submit" value="Import" class="button button-primary" id="submit" name="submit">
                
            </div>
            
           </form>
		
		</div>
		
	<?php }
	

/*import files - let the fun begin :) */
add_action( 'admin_init', 'pmc_file_import' );

	function pmc_file_import() {
		
		global $wpdb;
	
		/* add option flag to wordpress */
		add_option('pmc-import-exist');
		

		
		/* security array for valid filenames */
		$pmc_allowed_files = apply_filters( 'pmc_allowed_files', array( 
		  'pmc_file_one', 'pmc_file_two', 'pmc_file_three', 'pmc_file_four', 'pmc_file_five', 'pmc_file_six' , 'pmc_file_seven' , 'pmc_file_eight' , 'pmc_file_nine', 'pmc_file_ten'
		));
			
		if ( isset( $_POST['pmc_import_demo'] ) && !empty( $_POST['pmc_file'] ) ) {
		


			if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);
			
			if ( ! class_exists( 'WP_Importer' ) ) {
				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
				if ( file_exists( $class_wp_importer ) ) {
					include $class_wp_importer;
				}
		

			}
			

			if ( ! class_exists('PMC_import_WP') ) { 
				$class_wp_import = AQPB_PATH . 'assets/import/plugins/wordpress-importer.php';
				if ( file_exists( $class_wp_import ) ) {
					include $class_wp_import;
				}
				
			}

			if ( ! class_exists('Widget_Data_PMC') ) { 
				$class_widget_import = AQPB_PATH . 'assets/import/plugins/class-widget-data.php';
				if ( file_exists( $class_widget_import ) ) {
					include $class_widget_import;
				}
				
			}
		
			
			if ( class_exists( 'WP_Importer' ) && class_exists( 'PMC_import_WP' ) ) {
			
				
				$pmc_file = sanitize_file_name($_POST['pmc_file']);
				/*import xml*/
				if( get_option('pmc-import-exist') != 'true' ) {
					$wpdb->query( "DELETE FROM $wpdb->posts" );				
					$importer = new PMC_import_WP();
					
					$theme_xml = AQPB_PATH . 'assets/import/xml/' . $pmc_file . '.gz';
									
					if ( file_exists( $class_wp_importer ) && in_array( $pmc_file , $pmc_allowed_files) ) {
											
						$importer->fetch_attachments = true;
						ob_start();
						$importer->import($theme_xml);
						ob_end_clean();					
						
					} else {
						
						wp_redirect( admin_url( 'admin.php?page=pmc_importer&utimport=failed' ) );
						
					}
				}
				


				
				/*navigation*/
				if( get_option('pmc-import-exist') != 'true' ) {								
					$locations = get_theme_mod( 'nav_menu_locations' ); 
					$menus = wp_get_nav_menus(); 
					
					if( is_array($menus) ) {
						foreach($menus as $menu) { // assign menus to theme locations
								$menu_items = wp_get_nav_menu_object($menu->term_id);							
								switch($menu_items->name){
									case 'Single Menu':
										$locations['pmcsinglemenu'] = $menu->term_id;	
										$locations['pmcrespmenu'] = $menu->term_id;
										$locations['pmcrespsinglemenu'] = $menu->term_id;	
										$locations['pmcmainmenu'] = $menu->term_id;
										$locations['pmcscrollmenu'] = $menu->term_id;										
										break;									
								}
								if($pmc_file == 'pmc_file_two'){
									switch($menu_items->name){
									case 'Single Menu':
										$locations['pmcsinglemenu'] = $menu->term_id;	
										$locations['pmcrespmenu'] = $menu->term_id;
										$locations['pmcrespsinglemenu'] = $menu->term_id;	
										$locations['pmcmainmenu'] = $menu->term_id;
										$locations['pmcscrollmenu'] = $menu->term_id;												
									break;										
									case 'Page Builder Menu':
											$locations['pagebuildermenu'] = $menu->term_id;											
											break;		
									}
								}							
							
						}
					}
					
					
					set_theme_mod( 'nav_menu_locations', $locations );
					
					/*set home page*/
					
					$homepage 	= get_page_by_title( 'Home' );
					
					if( isset($homepage->ID) ) {
						update_option('show_on_front', 'page');
						update_option('page_on_front',  $homepage->ID); // Front Page
					}
					
					if($pmc_file == 'pmc_file_two'){
						$pmc_data  = '';
						delete_option('of_options_pmc');
						switch($pmc_file){
							case 'pmc_file_two':
								$pmc_data = 'YTo0NTp7czo0OiJtZW51IjthOjE6e2k6MTthOjI6e3M6NToib3JkZXIiO3M6MToiMSI7czo1OiJ0aXRsZSI7czoxMToiQ3VzdG9tIE1lbnUiO319czo3OiJzaWRlYmFyIjthOjM6e2k6MTthOjI6e3M6NToib3JkZXIiO3M6MToiMSI7czo1OiJ0aXRsZSI7czoxNDoiQ3VzdG9tIFNpZGViYXIiO31pOjI7YToyOntzOjU6Im9yZGVyIjtzOjE6IjIiO3M6NToidGl0bGUiO3M6MTA6IlNlYXJjaCBCYXIiO31pOjM7YToyOntzOjU6Im9yZGVyIjtzOjE6IjMiO3M6NToidGl0bGUiO3M6MTI6IlNob3AgU2lkZWJhciI7fX1zOjExOiJ0b3BfY29udGVudCI7czo0OiI3ODQzIjtzOjE2OiJ0b3BfY29udGVudF9ibG9nIjtzOjQ6Ijc4NDMiO3M6MTQ6ImZvb3Rlcl9jb250ZW50IjtzOjQ6Ijg4NDgiO3M6MTI6ImJsb2dfY29udGVudCI7czo0OiI5MTM0IjtzOjE4OiJ3b29jb21tZXJjZV9oZWFkZXIiO3M6NDoibm9uZSI7czoxODoid29vY29tbWVyY2VfZm9vdGVyIjtzOjQ6Im5vbmUiO3M6MTQ6InNob3dyZXNwb25zaXZlIjtzOjE6IjEiO3M6OToicG9ydF9zbHVnIjtzOjk6InBvcnRmb2xpbyI7czoxOToicG9ydGZvbGlvX2ljb25fbGluayI7czo1MToiaHR0cDovL2NoZXJyeWNvcnBvcmF0ZS5wcmVtaXVtY29kaW5nLmNvbS9wb3J0Zm9saW8vIjtzOjQ6ImxvZ28iO3M6ODE6Imh0dHA6Ly9lY29zaG9wLnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE0LzA2L2Vjb3JlY3ljbGluZy1sb2dvLnBuZyI7czoxMToibG9nb19yZXRpbmEiO3M6ODQ6Imh0dHA6Ly9lY29yZWN5Y2xlLnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE0LzA2L2Vjb3JlY3ljbGluZy1sb2dvLnBuZyI7czoxMToic2Nyb2xsX2xvZ28iO3M6ODQ6Imh0dHA6Ly9lY29yZWN5Y2xlLnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE0LzA2L2Vjb3JlY3ljbGluZy1sb2dvLnBuZyI7czoxMzoiaGVhZGVyX2hlaWdodCI7czoyOiI1NiI7czoxNToibG9nb190b3BfbWFyZ2luIjtzOjI6IjMwIjtzOjE1OiJtZW51X3RvcF9tYXJnaW4iO3M6MjoiMTQiO3M6NzoiZmF2aWNvbiI7czo4NzoiaHR0cDovL2Vjb3JlY3ljbGUucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTQvMDYvZmF2aWNvbi1lY29yZWN5Y2xpbmcucG5nIjtzOjE2OiJnb29nbGVfYW5hbHl0aWNzIjtzOjA6IiI7czoxNDoiYWR2ZXJ0aXNlaW1hZ2UiO2E6Njp7aToxO2E6NDp7czo1OiJvcmRlciI7czoxOiIxIjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgMSI7czozOiJ1cmwiO3M6OTA6Imh0dHA6Ly9jaGVycnljb3Jwb3JhdGUucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMTIvYWR2ZXJ0aXNlcnMtMS1ncmF5LnBuZyI7czo0OiJsaW5rIjtzOjA6IiI7fWk6MjthOjQ6e3M6NToib3JkZXIiO3M6MToiMiI7czo1OiJ0aXRsZSI7czo5OiJTcG9uc29yIDYiO3M6MzoidXJsIjtzOjkwOiJodHRwOi8vY2hlcnJ5Y29ycG9yYXRlLnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDEzLzEyL2FkdmVydGlzZXJzLTItZ3JheS5wbmciO3M6NDoibGluayI7czowOiIiO31pOjM7YTo0OntzOjU6Im9yZGVyIjtzOjE6IjMiO3M6NToidGl0bGUiO3M6OToiU3BvbnNvciA0IjtzOjM6InVybCI7czo5MDoiaHR0cDovL2NoZXJyeWNvcnBvcmF0ZS5wcmVtaXVtY29kaW5nLmNvbS93cC1jb250ZW50L3VwbG9hZHMvMjAxMy8xMi9hZHZlcnRpc2Vycy0zLWdyYXkucG5nIjtzOjQ6ImxpbmsiO3M6MDoiIjt9aTo0O2E6NDp7czo1OiJvcmRlciI7czoxOiI0IjtzOjU6InRpdGxlIjtzOjk6IlNwb25zb3IgMiI7czozOiJ1cmwiO3M6OTA6Imh0dHA6Ly9jaGVycnljb3Jwb3JhdGUucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMTIvYWR2ZXJ0aXNlcnMtNC1ncmF5LnBuZyI7czo0OiJsaW5rIjtzOjA6IiI7fWk6NTthOjQ6e3M6NToib3JkZXIiO3M6MToiNSI7czo1OiJ0aXRsZSI7czo5OiJTcG9uc29yIDMiO3M6MzoidXJsIjtzOjkwOiJodHRwOi8vY2hlcnJ5Y29ycG9yYXRlLnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDEzLzEyL2FkdmVydGlzZXJzLTUtZ3JheS5wbmciO3M6NDoibGluayI7czowOiIiO31pOjY7YTo0OntzOjU6Im9yZGVyIjtzOjE6IjYiO3M6NToidGl0bGUiO3M6OToiU3BvbnNvciA1IjtzOjM6InVybCI7czo5MDoiaHR0cDovL2NoZXJyeWNvcnBvcmF0ZS5wcmVtaXVtY29kaW5nLmNvbS93cC1jb250ZW50L3VwbG9hZHMvMjAxMy8xMi9hZHZlcnRpc2Vycy02LWdyYXkucG5nIjtzOjQ6ImxpbmsiO3M6MDoiIjt9fXM6OToibWFpbkNvbG9yIjtzOjc6IiM5NGJiNTQiO3M6MTQ6ImdyYWRpZW50X2NvbG9yIjtzOjc6IiM4MGE5M2QiO3M6ODoiYm94Q29sb3IiO3M6NzoiI2RkZGRkZCI7czoxNToiU2hhZG93Q29sb3JGb250IjtzOjQ6IiNmZmYiO3M6MjM6IlNoYWRvd09wYWNpdHR5Q29sb3JGb250IjtzOjE6IjAiO3M6MjE6ImJvZHlfYmFja2dyb3VuZF9jb2xvciI7czo3OiIjZmZmZmZmIjtzOjE2OiJpbWFnZV9iYWNrZ3JvdW5kIjtzOjgxOiJodHRwOi8vY2hlcnJ5LnByZW1pdW1jb2RpbmcuY29tL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE0LzA1L2JveGVkLWJhY2tncm91bmQtMi5qcGciO3M6MTI6ImN1c3RvbV9zdHlsZSI7czoxOiIgIjtzOjk6ImJvZHlfZm9udCI7YTozOntzOjQ6InNpemUiO3M6NDoiMTZweCI7czo1OiJjb2xvciI7czo0OiIjMzMzIjtzOjQ6ImZhY2UiO3M6MTE6Ik9wZW4lMjBTYW5zIjt9czoxMjoiaGVhZGluZ19mb250IjthOjI6e3M6NDoiZmFjZSI7czo2OiJPc3dhbGQiO3M6NToic3R5bGUiO3M6NDoiYm9sZCI7fXM6OToibWVudV9mb250IjthOjQ6e3M6NDoic2l6ZSI7czo0OiIxNnB4IjtzOjU6ImNvbG9yIjtzOjQ6IiNmZmYiO3M6NDoiZmFjZSI7czoxMToiT3BlbiUyMFNhbnMiO3M6NToic3R5bGUiO3M6Njoibm9ybWFsIjt9czoxNDoiYm9keV9ib3hfY29sZXIiO3M6NzoiI2ZmZmZmZiI7czoxNToiYm9keV9saW5rX2NvbGVyIjtzOjQ6IiMxMTEiO3M6MTU6ImhlYWRpbmdfZm9udF9oMSI7YToyOntzOjQ6InNpemUiO3M6NDoiNTBweCI7czo1OiJjb2xvciI7czo0OiIjMTExIjt9czoxNToiaGVhZGluZ19mb250X2gyIjthOjI6e3M6NDoic2l6ZSI7czo0OiI0NnB4IjtzOjU6ImNvbG9yIjtzOjQ6IiMxMTEiO31zOjE1OiJoZWFkaW5nX2ZvbnRfaDMiO2E6Mjp7czo0OiJzaXplIjtzOjQ6IjM4cHgiO3M6NToiY29sb3IiO3M6NDoiIzExMSI7fXM6MTU6ImhlYWRpbmdfZm9udF9oNCI7YToyOntzOjQ6InNpemUiO3M6NDoiMzJweCI7czo1OiJjb2xvciI7czo0OiIjMTExIjt9czoxNToiaGVhZGluZ19mb250X2g1IjthOjI6e3M6NDoic2l6ZSI7czo0OiIyNnB4IjtzOjU6ImNvbG9yIjtzOjQ6IiMxMTEiO31zOjE1OiJoZWFkaW5nX2ZvbnRfaDYiO2E6Mjp7czo0OiJzaXplIjtzOjQ6IjIwcHgiO3M6NToiY29sb3IiO3M6NDoiIzExMSI7fXM6MTE6InNvY2lhbGljb25zIjthOjc6e2k6MTthOjQ6e3M6NToib3JkZXIiO3M6MToiMSI7czo1OiJ0aXRsZSI7czo3OiJUd2l0dGVyIjtzOjM6InVybCI7czo5MjoiaHR0cDovL2NoZXJyeWNvcnBvcmF0ZS5wcmVtaXVtY29kaW5nLmNvbS93cC1jb250ZW50L3VwbG9hZHMvMjAxNC8wNS9JTUdfMTQwNTIwMTRfMTYxNzMyMS5wbmciO3M6NDoibGluayI7czowOiIiO31pOjI7YTo0OntzOjU6Im9yZGVyIjtzOjE6IjIiO3M6NToidGl0bGUiO3M6ODoiRmFjZWJvb2siO3M6MzoidXJsIjtzOjA6IiI7czo0OiJsaW5rIjtzOjA6IiI7fWk6MzthOjQ6e3M6NToib3JkZXIiO3M6MToiMyI7czo1OiJ0aXRsZSI7czo2OiJGbGlja3IiO3M6MzoidXJsIjtzOjA6IiI7czo0OiJsaW5rIjtzOjA6IiI7fWk6NDthOjQ6e3M6NToib3JkZXIiO3M6MToiNCI7czo1OiJ0aXRsZSI7czo4OiJEcmliYmJsZSI7czozOiJ1cmwiO3M6MDoiIjtzOjQ6ImxpbmsiO3M6MDoiIjt9aTo1O2E6NDp7czo1OiJvcmRlciI7czoxOiI1IjtzOjU6InRpdGxlIjtzOjk6IlBpbnRlcmVzdCI7czozOiJ1cmwiO3M6MDoiIjtzOjQ6ImxpbmsiO3M6MDoiIjt9aTo2O2E6NDp7czo1OiJvcmRlciI7czoxOiI2IjtzOjU6InRpdGxlIjtzOjc6Ikdvb2dsZSsiO3M6MzoidXJsIjtzOjA6IiI7czo0OiJsaW5rIjtzOjA6IiI7fWk6NzthOjQ6e3M6NToib3JkZXIiO3M6MToiNyI7czo1OiJ0aXRsZSI7czo1OiJFbWFpbCI7czozOiJ1cmwiO3M6MDoiIjtzOjQ6ImxpbmsiO3M6MDoiIjt9fXM6MTQ6ImVycm9ycGFnZXRpdGxlIjtzOjEwOiJPT09QUyEgNDA0IjtzOjk6ImVycm9ycGFnZSI7czozMjY6IlNvcnJ5LCBidXQgdGhlIHBhZ2UgeW91IGFyZSBsb29raW5nIGZvciBoYXMgbm90IGJlZW4gZm91bmQuPGJyLz5UcnkgY2hlY2tpbmcgdGhlIFVSTCBmb3IgZXJyb3JzLCB0aGVuIGhpdCByZWZyZXNoLjwvYnI+T3IgeW91IGNhbiBzaW1wbHkgY2xpY2sgdGhlIGljb24gYmVsb3cgYW5kIGdvIGhvbWU6KQ0KPGJyPjxicj4NCjxhIGhyZWYgPSBcImh0dHA6Ly90ZXJlc2EucHJlbWl1bWNvZGluZy5jb20vXCI+PGltZyBzcmMgPSBcImh0dHA6Ly9idWxsc3kucHJlbWl1bWNvZGluZy5jb20vd3AtY29udGVudC91cGxvYWRzLzIwMTMvMDgvaG9tZUhvdXNlSWNvbi5wbmdcIj48L2E+IjtzOjk6ImNvcHlyaWdodCI7czoxNDU6IkVjbyBSZWN5Y2xpbmcgQDIwMTQgYnkgPGEgaHJlZiA9IFwiaHR0cDovL3ByZW1pdW1jb2RpbmcuY29tL1wiPlBSRU1JVU1DT0RJTkc8L2E+IHwgUG93ZXJlZCBieTogPGEgaHJlZiA9IFwiaHR0cDovL3dvcmRwcmVzcy5vcmdcIj5XT1JEUFJFU1M8L2E+DQoiO3M6OToidXNlX2JveGVkIjtzOjA6IiI7czoyMToiYmFja2dyb3VuZF9pbWFnZV9mdWxsIjtzOjA6IiI7fQ==';
								break;						
						}				
		
						$pmc_data = unserialize(base64_decode($pmc_data)); //100% safe - ignore theme check nag
						update_option(OPTIONS, $pmc_data);
					}					
				}

				global $wp_rewrite;
				$wp_rewrite->set_permalink_structure('/%postname%/');
				$wp_rewrite->flush_rules();				
				
				/*widgets*/
				$class_widget_import = new Widget_Data_PMC();
				$class_widget_import->ajax_import_widget_data();				
					

				
/*revolutin sldier*/
				
				if( isset( $_POST['pmc-import-revslider'] ) && $_POST['pmc-import-revslider'] == 'yes' ) :
				
					
					if( class_exists('UniteFunctionsRev') ) { 
						$rev_directory = AQPB_PATH . 'assets/import/revslider/'.$pmc_file.'/'; 
						$rev_files = array();
						
						foreach( glob( $rev_directory . '*.zip' ) as $filename ) {
							$filename = basename($filename);
							$rev_files[] = AQPB_PATH . 'assets/import/revslider/'.$pmc_file.'/' . $filename ;
							
						}
												
						foreach( $rev_files as $rev_file ) {
							try{
									
								$sliderID = UniteFunctionsRev::getPostVariable("sliderid");
								$sliderExists = !empty($sliderID);
								
								if($sliderExists)
									$this->initByID($sliderID);
									
								$filepath = $rev_file;
								

								
								//check if zip file or fallback to old, if zip, check if all files exist
								if(!class_exists("ZipArchive")){
									$importZip = false;
								}else{
									$zip = new ZipArchive;
									$importZip = $zip->open($filepath, ZIPARCHIVE::CREATE);
								}
								if($importZip === true){ //true or integer. If integer, its not a correct zip file
									
									//check if files all exist in zip
									$slider_export = $zip->getStream('slider_export.txt');
									$custom_animations = $zip->getStream('custom_animations.txt');
									$dynamic_captions = $zip->getStream('dynamic-captions.css');
									$static_captions = $zip->getStream('static-captions.css');
									
									
									$content = '';
									$animations = '';
									$dynamic = '';
									$static = '';
									
									while (!feof($slider_export)) $content .= fread($slider_export, 1024);
									if($custom_animations){ while (!feof($custom_animations)) $animations .= fread($custom_animations, 1024); }
									if($dynamic_captions){ while (!feof($dynamic_captions)) $dynamic .= fread($dynamic_captions, 1024); }
									if($static_captions){ while (!feof($static_captions)) $static .= fread($static_captions, 1024); }

									fclose($slider_export);
									if($custom_animations){ fclose($custom_animations); }
									if($dynamic_captions){ fclose($dynamic_captions); }
									if($static_captions){ fclose($static_captions); }
									
									//check for images!
									
								}else{ //check if fallback
									//get content array
									$content = @file_get_contents($filepath);
								}
								
								if($importZip === true){ //we have a zip
									$db = new UniteDBRev();
									
									//update/insert custom animations
									$animations = @unserialize($animations);
									if(!empty($animations)){
										foreach($animations as $key => $animation){ //$animation['id'], $animation['handle'], $animation['params']
											$exist = $db->fetch(GlobalsRevSlider::$table_layer_anims, "handle = '".$animation['handle']."'");
											if(!empty($exist)){ //update the animation, get the ID
												if($updateAnim == "true"){ //overwrite animation if exists
													$arrUpdate = array();
													$arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
													$db->update(GlobalsRevSlider::$table_layer_anims, $arrUpdate, array('handle' => $animation['handle']));
													
													$id = $exist['0']['id'];
												}else{ //insert with new handle
													$arrInsert = array();
													$arrInsert["handle"] = 'copy_'.$animation['handle'];
													$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
													
													$id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
												}
											}else{ //insert the animation, get the ID
												$arrInsert = array();
												$arrInsert["handle"] = $animation['handle'];
												$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
												
												$id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
											}
											
											//and set the current customin-oldID and customout-oldID in slider params to new ID from $id
											$content = str_replace(array('customin-'.$animation['id'], 'customout-'.$animation['id']), array('customin-'.$id, 'customout-'.$id), $content);	
										}

									}else{

									}
									
									//overwrite/append static-captions.css
									if(!empty($static)){
										if($updateStatic == "true"){ //overwrite file
											RevOperations::updateStaticCss($static);
										}else{ //append
											$static_cur = RevOperations::getStaticCss();
											$static = $static_cur."\n".$static;
											RevOperations::updateStaticCss($static);
										}
									}
									//overwrite/create dynamic-captions.css
									//parse css to classes
									$dynamicCss = UniteCssParserRev::parseCssToArray($dynamic);
									
									if(is_array($dynamicCss) && $dynamicCss !== false && count($dynamicCss) > 0){
										foreach($dynamicCss as $class => $styles){
											//check if static style or dynamic style
											$class = trim($class);
											
											if((strpos($class, ':hover') === false && strpos($class, ':') !== false) || //before, after
												strpos($class," ") !== false || // .tp-caption.imageclass img or .tp-caption .imageclass or .tp-caption.imageclass .img
												strpos($class,".tp-caption") === false || // everything that is not tp-caption
												(strpos($class,".") === false || strpos($class,"#") !== false) || // no class -> #ID or img
												strpos($class,">") !== false){ //.tp-caption>.imageclass or .tp-caption.imageclass>img or .tp-caption.imageclass .img
												continue;
											}
											
											//is a dynamic style
											if(strpos($class, ':hover') !== false){
												$class = trim(str_replace(':hover', '', $class));
												$arrInsert = array();
												$arrInsert["hover"] = json_encode($styles);
												$arrInsert["settings"] = json_encode(array('hover' => 'true'));
											}else{
												$arrInsert = array();
												$arrInsert["params"] = json_encode($styles);
											}
											//check if class exists
											$result = $db->fetch(GlobalsRevSlider::$table_css, "handle = '".$class."'");
											
											if(!empty($result)){ //update
												$db->update(GlobalsRevSlider::$table_css, $arrInsert, array('handle' => $class));
											}else{ //insert
												$arrInsert["handle"] = $class;
												$db->insert(GlobalsRevSlider::$table_css, $arrInsert);
											}
										}
		
									}else{
				
									}
								}
								
								$content = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $content); //clear errors in string
								
								$arrSlider = @unserialize($content);

									
								//update slider params
								$sliderParams = $arrSlider["params"];
								
								if($sliderExists){					
									$sliderParams["title"] = $this->arrParams["title"];
									$sliderParams["alias"] = $this->arrParams["alias"];
									$sliderParams["shortcode"] = $this->arrParams["shortcode"];
								}
								
								if(isset($sliderParams["background_image"]))
									$sliderParams["background_image"] = UniteFunctionsWPRev::getImageUrlFromPath($sliderParams["background_image"]);
								
								$json_params = json_encode($sliderParams);
								
								//update slider or create new
								if($sliderExists){
									$arrUpdate = array("params"=>$json_params);	
									$this->db->update(GlobalsRevSlider::$table_sliders,$arrUpdate,array("id"=>$sliderID));
								}else{	//new slider
									$arrInsert = array();
									$arrInsert["params"] = $json_params;
									$arrInsert["title"] = UniteFunctionsRev::getVal($sliderParams, "title","Slider1");
									$arrInsert["alias"] = UniteFunctionsRev::getVal($sliderParams, "alias","slider1");	
									$sliderID = $wpdb->insert(GlobalsRevSlider::$table_sliders,$arrInsert);
									$sliderID = $wpdb->insert_id;
								}
								
								//-------- Slides Handle -----------
								
								//delete current slides
								if($sliderExists)
									$this->deleteAllSlides();
								
								//create all slides
								$arrSlides = $arrSlider["slides"];
								
								$alreadyImported = array();
								
								foreach($arrSlides as $slide){
									
									$params = $slide["params"];
									$layers = $slide["layers"];
									
									//convert params images:
									if(isset($params["image"])){
										//import if exists in zip folder
										if(strpos($params["image"], 'http') !== false){
										}else{
											if(trim($params["image"]) !== ''){
												if($importZip === true){ //we have a zip, check if exists
													$image = $zip->getStream('images/'.$params["image"]);
													if(!$image){
				

													}else{
														if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]])){
															$importImage = UniteFunctionsWPRev::import_media('zip://'.$filepath."#".'images/'.$params["image"], $sliderParams["alias"].'/');

															if($importImage !== false){
																$alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]] = $importImage['path'];
																
																$params["image"] = $importImage['path'];
															}
														}else{
															$params["image"] = $alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]];
														}


													}
												}
											}
											$params["image"] = UniteFunctionsWPRev::getImageUrlFromPath($params["image"]);
										}
									}
									
									//convert layers images:
									foreach($layers as $key=>$layer){					
										if(isset($layer["image_url"])){
											//import if exists in zip folder
											if(trim($layer["image_url"]) !== ''){
												if(strpos($layer["image_url"], 'http') !== false){
												}else{
													if($importZip === true){ //we have a zip, check if exists
														$image_url = $zip->getStream('images/'.$layer["image_url"]);
														if(!$image_url){
								
														}else{
															if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]])){
																$importImage = UniteFunctionsWPRev::import_media('zip://'.$filepath."#".'images/'.$layer["image_url"], $sliderParams["alias"].'/');
																
																if($importImage !== false){
																	$alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]] = $importImage['path'];
																	
																	$layer["image_url"] = $importImage['path'];
																}
															}else{
																$layer["image_url"] = $alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]];
															}
														}
													}
												}
											}
											$layer["image_url"] = UniteFunctionsWPRev::getImageUrlFromPath($layer["image_url"]);
											$layers[$key] = $layer;
										}
									}
									
									//create new slide
									$arrCreate = array();
									$arrCreate["slider_id"] = $sliderID;
									$arrCreate["slide_order"] = $slide["slide_order"];
									
									$my_layers = json_encode($layers);
									if(empty($my_layers))
										$my_layers = stripslashes(json_encode($layers));
									$my_params = json_encode($params);
									if(empty($my_params))
										$my_params = stripslashes(json_encode($params));
										
										
									$arrCreate["layers"] = $my_layers;
									$arrCreate["params"] = $my_params;
									
									$wpdb->insert(GlobalsRevSlider::$table_slides,$arrCreate);									
								}
								
								//check if static slide exists and import
								if(isset($arrSlider['static_slides']) && !empty($arrSlider['static_slides'])){
									$static_slide = $arrSlider['static_slides'];
									foreach($static_slide as $slide){
										
										$params = $slide["params"];
										$layers = $slide["layers"];
										
										//convert params images:
										if(isset($params["image"])){
											//import if exists in zip folder
											if(strpos($params["image"], 'http') !== false){
											}else{
												if(trim($params["image"]) !== ''){
													if($importZip === true){ //we have a zip, check if exists
														$image = $zip->getStream('images/'.$params["image"]);
														if(!$image){
					

														}else{
															if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]])){
																$importImage = UniteFunctionsWPRev::import_media('zip://'.$filepath."#".'images/'.$params["image"], $sliderParams["alias"].'/');

																if($importImage !== false){
																	$alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]] = $importImage['path'];
																	
																	$params["image"] = $importImage['path'];
																}
															}else{
																$params["image"] = $alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]];
															}


														}
													}
												}
												$params["image"] = UniteFunctionsWPRev::getImageUrlFromPath($params["image"]);
											}
										}
										
										//convert layers images:
										foreach($layers as $key=>$layer){					
											if(isset($layer["image_url"])){
												//import if exists in zip folder
												if(trim($layer["image_url"]) !== ''){
													if(strpos($layer["image_url"], 'http') !== false){
													}else{
														if($importZip === true){ //we have a zip, check if exists
															$image_url = $zip->getStream('images/'.$layer["image_url"]);
															if(!$image_url){
						
															}else{
																if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]])){
																	$importImage = UniteFunctionsWPRev::import_media('zip://'.$filepath."#".'images/'.$layer["image_url"], $sliderParams["alias"].'/');
																	
																	if($importImage !== false){
																		$alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]] = $importImage['path'];
																		
																		$layer["image_url"] = $importImage['path'];
																	}
																}else{
																	$layer["image_url"] = $alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]];
																}
															}
														}
													}
												}
												$layer["image_url"] = UniteFunctionsWPRev::getImageUrlFromPath($layer["image_url"]);
												$layers[$key] = $layer;
											}
										}
										
										//create new slide
										$arrCreate = array();
										$arrCreate["slider_id"] = $sliderID;
										
										$my_layers = json_encode($layers);
										if(empty($my_layers))
											$my_layers = stripslashes(json_encode($layers));
										$my_params = json_encode($params);
										if(empty($my_params))
											$my_params = stripslashes(json_encode($params));
											
											
										$arrCreate["layers"] = $my_layers;
										$arrCreate["params"] = $my_params;
										
										if($sliderExists){
											unset($arrCreate["slider_id"]);
											$this->db->update(GlobalsRevSlider::$table_static_slides,$arrCreate,array("slider_id"=>$sliderID));
										}else{
											$this->db->insert(GlobalsRevSlider::$table_static_slides,$arrCreate);									
										}
									}
								}
								
							}catch(Exception $e){
								$errorMessage = $e->getMessage();
								return(array("success"=>false,"error"=>$errorMessage,"sliderID"=>$sliderID));
							}
						}
					}
				
				endif;
				
				update_option('pmc-import-exist', 'true');
				
				wp_redirect( admin_url( 'admin.php?page=pmc_importer&pmc-import-success=success' ) );
								
				
			}
	
		
		}
		
	}
	
/* folder can be written*/
function pmc_check_folder( $path ) {
	if ( $path{strlen($path)-1}=='/' ) {
		return pmc_check_folder($path.uniqid(mt_rand()).'.tmp');
	}
	
	if (file_exists($path)) {
		if (!($f = @fopen($path, 'r+')))
			return false;
		fclose($f);
		return true;
	}
	
	if (!($f = @fopen($path, 'w')))
		return false;
	fclose($f);
	unlink($path);
	return true;
	
}

?>
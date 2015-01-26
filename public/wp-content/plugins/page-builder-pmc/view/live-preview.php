<?php
    $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
    require_once($root.'/wp-load.php');
?>
<?php wp_head();?>
	<div class="usercontent live">		
		<?php 			
			echo do_shortcode( stripslashes('[template id="'.$_GET['ID'].'"]') );
		?>	
	<input type="hidden" id="root" value="<?php echo get_template_directory_uri() ?>" >	
	</div>
<?php wp_footer();  ?>
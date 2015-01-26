<?php
$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once($root.'/wp-load.php');


global $wpdb;
	
define('__ROOT__', (dirname(dirname(__FILE__)))); 
require_once(__ROOT__.'/page-builder-pmc.php'); 

$posteddata=$_POST['alldata'];
$data= json_decode($posteddata);
$template_id  = $_POST['template'];
$title = $_POST['template-name'];


//first let's check if template id is valid
$template = get_post($template_id);

if($template) {
	if($template->post_type != 'template' || $template->post_status != 'publish') wp_die('Error : Template id is not valid');;
} 

//wp security layer
check_admin_referer( 'update-template', 'update-template-nonce' );

//update the title
$template = array('ID' => $template_id, 'post_title'=> $title);
wp_update_post( $template );


//now let's save our blocks & prepare haystack
$ajax_blocks = array();
$ajax_blocks = $_POST['aq_blocks'];
$blocks = $ajax_blocks;
$haystack = array();
$template_transient_data = array();
$i = 1;


foreach ($blocks as $new_instance) {
	global $aq_registered_blocks;
	
	$old_key = isset($new_instance['number']) ? 'aq_block_' . $new_instance['number'] : 'aq_block_0';
	$new_key = isset($new_instance['number']) ? 'aq_block_' . $i : 'aq_block_0';
	
	$old_instance = get_post_meta($template_id, $old_key, true);
	
	extract($new_instance);
	
	if(class_exists($id_base)) {
		//get the block object
		$block = $aq_registered_blocks[$id_base];
		
		//insert template_id into $instance
		$new_instance['template_id'] = $template_id;
		
		//sanitize instance with AQ_Block::update()
		$new_instance = $block->update($new_instance, $old_instance);
	}
	
	// sanitize from all occurrences of "\r\n" - see bit.ly/Ajav2a
	$new_instance = str_replace("\r\n", "\n", $new_instance);
	//update block
	update_post_meta($template_id, $new_key, $new_instance);
	//store instance into $template_transient_data
	$template_transient_data[$new_key] = $new_instance;
	//prepare haystack
	$haystack[] = $new_key;
	$i++;
}

//update transient
$template_transient = 'aq_template_' . $template_id;
set_transient( $template_transient, $template_transient_data );

//filter post meta to get only blocks data
$blocks = array();
$all = get_post_custom($template_id);
foreach($all as $key => $block) {
	if(substr($key, 0, 9) == 'aq_block_') {
		$block_instance = get_post_meta($template_id, $key, true);
		if(is_array($block_instance)) $blocks[$key] = $block_instance;
	}
}

//sort by order
$sort = array();
foreach($blocks as $block) {
	if(isset($block['order'])){
		$sort[] = $block['order'];
	}
	else{$sort[] = 1;}
}
array_multisort($sort, SORT_NUMERIC, $blocks);

$curr_blocks = $blocks;


$curr_blocks = is_array($curr_blocks) ? $curr_blocks : array();
foreach($curr_blocks as $key => $block){
	if(!in_array($key, $haystack))
		delete_post_meta($template_id, $key);
}



?>
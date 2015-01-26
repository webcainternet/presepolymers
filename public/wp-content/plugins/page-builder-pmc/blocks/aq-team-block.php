<?php
/** A simple rich textarea block **/
class AQ_Team_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Team block',
			'size' => 'span3',
			'icon' => 'fa-list-ul',
			'icon_color' => 'FFF',
			'category' => 'Shortcodes',
			'help' => 'Block for team members.'
		);
			parent::__construct('aq_team_block', $block_options);
			
			add_action('wp_ajax_aq_block_team_add_new', array($this, 'add_team'));
	}
	
function form($instance) {
	
	// default key/values array
	$defaults = array(
		'title' 	=> '', 
		'title_member' 	=> 'John Doe', // the name of the member
		'position'	=> 'Designer', // job position
		'avatar'	=> 'http://cherry.premiumcoding.com/wp-content/uploads/2013/12/team-1.jpg', // profile picture
		'bio'		=> 'Some short info about a member of our small and creative team.', // a little info about the member
		'url'		=> '', // website URL
		'use_social'  => 1,
		'teams' => array(
			1 => array(
				'title' => 'Twitter',
				'link' => 'https://twitter.com/premiumcoding',
				'hover_color' => '#df5148',
				'img' => 'http://cherry.premiumcoding.com/wp-content/uploads/2013/10/twitter-team-social.png',				
			),			
			2 => array(
				'title' => 'Facebook',
				'link' => 'https://www.facebook.com/PremiumCoding',
				'hover_color' => '#df5148',
				'img' => 'http://cherry.premiumcoding.com/wp-content/uploads/2013/10/facebook-team-social.png',				
			),
			3 => array(
				'title' => 'Pinterest',
				'link' => 'http://www.pinterest.com/gljivec/',
				'hover_color' => '#df5148',
				'img' => 'http://cherry.premiumcoding.com/wp-content/uploads/2013/10/pinterest-team-social.png',				
			),	
			4 => array(
				'title' => 'Dribbble',
				'link' => 'https://dribbble.com/gljivec',
				'hover_color' => '#df5148',
				'img' => 'http://cherry.premiumcoding.com/wp-content/uploads/2013/10/dribbble-team-social.png',				
			))

	
	);

	// set default values (if not yet defined)
	$instance = wp_parse_args($instance, $defaults);

	// import each array key as variable with defined values
	extract($instance); ?>
	
	
	<p class="description half">
		<label for="<?php echo $this->get_field_id('title') ?>">
			Member Name (required)<br/>
			<?php echo aq_field_input('title_member', $block_id, $title_member) ?>
			<?php echo aq_field_input('title', $block_id, $title_member, $size = 'full','hidden') ?>
		</label>
	</p>

	
	<p class="description half">
		<label for="<?php echo $this->get_field_id('url') ?>">
			Member link <br/>
			<?php echo aq_field_input('url', $block_id, $url) ?>
		</label>
	</p>	
	<p class="description half last">
		<label for="<?php echo $this->get_field_id('position') ?>">
			Position(required)<br/>
			<?php echo aq_field_input('position', $block_id, $position) ?>
		</label>
	</p>

	<div class="description">
		<label for="<?php echo $this->get_field_id('avatar') ?>">
			Upload an image<br/>
			<?php echo aq_field_upload('avatar', $block_id, $avatar) ?>
		</label>
		<?php if($avatar) { ?>
		<div class="screenshot">
			<img src="<?php echo $avatar ?>" />
		</div>
		<?php } ?>
	</div>
	

	<p class="description">
		<label for="<?php echo $this->get_field_id('bio') ?>">
			Member info
			<?php $rand = rand(0,999); ?>
			<?php echo aq_field_textarea('bio', $block_id, $bio, $size = 'full pmc-editor-'.$rand) ?>
		</label>
	</p>
	
	<p class="description">
		<label for="<?php echo $this->get_field_id('use_social') ?>">
			<?php echo aq_field_checkbox('use_social', $block_id, $use_social); ?>
			Use social icons?
		</label>
	</p>
	
	<div class="description cf social">
		<ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>" alt="teams">
			<?php
			$teams = is_array($teams) ? $teams : $defaults['teams'];
			$count = 1;
			foreach($teams as $team) {	
				$this->team($team, $count);
				$count++;
			}
			?>
		</ul>
		<p></p>
		<a href="#" rel="team" class="aq-sortable-add-new button">Add New</a>
		<p></p>
	</div>
	<script>
		jQuery(document).ready(function() {
			
			if(jQuery('#<?php echo $block_id ?>_use_social').is(":checked")) { jQuery('.social').show(); } else { jQuery('.social').hide();}
			
			jQuery('#<?php echo $block_id ?>_use_social').change(function() {
				if(jQuery(this).is(":checked")) {
					jQuery('.social').show();
				}
				else{
					jQuery('.social').hide();
	
				}
				
			});
		});		
	</script>

	<?php

	
}

	function team($team = array(), $count = 0) {
			$defaults = array (
				'title' => 'My New Social Icon',
				'link' => 'My new social link',
				'img' => '',	
				'hover_color' => ''
			);
			$team = wp_parse_args($team, $defaults);	
			
		?>
		<li id="<?php echo $this->get_field_id('teams') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">
			
			<div class="sortable-head cf">
				<div class="sortable-title">
					<strong><?php echo $team['title'] ?></strong>
				</div>
				<div class="sortable-handle">
					<a href="#">Open / Close</a>
				</div>
			</div>
			
			<div class="sortable-body">
				<p class="teams-desc description">
					<label for="<?php echo $this->get_field_id('teams') ?>-<?php echo $count ?>-title">
						Team social Title (alt text)<br/>
						<input type="text" id="<?php echo $this->get_field_id('teams') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('teams') ?>[<?php echo $count ?>][title]" value="<?php echo $team['title'] ?>" />
					</label>
				</p>
				<p class="teams-desc description">
					<label for="<?php echo $this->get_field_id('teams') ?>-<?php echo $count ?>-link">
						Team social link<br/>
						<input type="text" id="<?php echo $this->get_field_id('teams') ?>-<?php echo $count ?>-link" class="input-full" name="<?php echo $this->get_field_name('teams') ?>[<?php echo $count ?>][link]" value="<?php echo $team['link'] ?>" />
					</label>
				</p>
				<p class="teams-desc description">
					<label for="<?php echo $this->get_field_id('teams') ?>-<?php echo $count ?>-img"">
						Upload an image<br/>
						<input type="text" id="<?php echo $this->get_field_id('teams') ?>-<?php echo $count ?>-img" class="input-full input-upload" name="<?php echo $this->get_field_name('teams') ?>[<?php echo $count ?>][img]" value="<?php echo $team['img'] ?>">
						<a href="#" class="aq_upload_button button" >Upload</a><p></p>					
					</label>
					<?php if(isset($img)) { ?>
					<div class="screenshot">
						<img src="<?php echo $team['img'] ?>" />
					</div>
					<?php } ?>
				</p>	
				<p class="teams-desc description">
				<label for="<?php echo $this->get_field_id('hover_color') ?>">
					Hover background color<br/>
					<div class="aqpb-color-picker">
						<input type="text" id="<?php echo $this->get_field_id('teams') ?>-<?php echo $count ?>-hover_color" class="input-color-picker"  name="<?php echo $this->get_field_name('teams') ?>[<?php echo $count ?>][hover_color]" value="<?php echo $team['hover_color'] ?>" data-default-color="#fff"/>
					</div>						
				</label>	
				</p>
				<p class="teams-desc description"><a href="#" class="sortable-delete">Delete</a></p>
			</div>			
		</li>
		<?php
	}
		
	function add_team() {
		$nonce = $_POST['security'];	
		if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');
		
		$count = isset($_POST['count']) ? absint($_POST['count']) : false;
		$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
		
		//default key/value for the tab
		$team = array(
			'title' => 'My New Social Icon',
			'link' => 'My new social link',
			'img' => '',	
			'hover_color' => ''
		);
		
		if($count) {
			$this->team($team, $count);
		} else {
			die(-1);
		}
		
		die();
	}

		
	function block($instance) {
		
	
		// default key/values array
		$defaults = array(
			'title_member' 	=> '', // the name of the member
			'position'	=> '', // job position
			'avatar'	=> '', // profile picture
			'bio'		=> '', // a little info about the member
			'url'		=> '', // website URL	
			'use_social'  => 1,
			'teams' => array(
				1 => array(
					'title' => '',
					'link' => '',
					'img' => '',	
					'hover_color' => ''					
				))
				
		);

		// set default values (if not yet defined)
		$instance = wp_parse_args($instance, $defaults);

		// import each array key as variable with defined values
		extract($instance); 
		$hover_out = '';
		if(isset($team['hover_color']) && $team['hover_color'] != ''){
			$hover_out = ' style="background:'. $team['hover_color'] .';"';
		}
		
		
		?>


		

		<div class="team-wrapper">
			<div class="team">
				<div class="image">
					<img src="<?php echo $avatar  ?>" alt="team" />
				</div>	
				<?php if(trim($url)){ ?>
					<a href="<?php echo $url ?>">
				<?php } ?>
				<div class="title"><?php echo $title_member;  ?></div>
				<?php if(trim($url)){ ?>
					</a>
				<?php } ?>				
				<div class="role"><?php echo $position;  ?></div>
				<div class="description"><?php echo $bio;  ?></div>
				<?php if(isset($use_social)  && $use_social == 1 ){ ?>
					<div class="social">
						<?php foreach ($teams as $team) { ?>	
							<?php if($team['img'] != '') { ?>
								<div <?php echo $hover_out; ?>>
								<a href = "<?php echo $team['link'] ?>" ><img src ="<?php echo $team['img'] ?>" alt = "<?php echo $team['title'] ?>" ></a>
								</div>
						<?php } } ?>	
					</div>
				<?php } ?>
			</div>
		</div>
			
	<?php
	}
			
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
}
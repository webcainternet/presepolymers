<?php
/**
 * Requires the "Twitter Widget Pro" plugin
 */
 

	class AQ_Twitter_Block extends AQ_Block {
	
		function __construct() {
			$block_options = array(
				'name' => 'Twitter',
				'size' => 'span3',				
				'icon' => 'fa-twitter',				
				'icon_color' => 'FFF',				
				'category' => 'Social',				
				'help' => 'Add Twitter block. You can set several parameters in the block, further adjustments can be set in the admin panel of the plugin.'	
			);
			
			$this->_slug = 'twitter-widget-pro';
			
			parent::__construct('aq_twitter_block', $block_options);
		}
		
		private function _getInstanceSettings ( $instance ) {
			$wpTwitterWidget = wpTwitterWidget::getInstance();
			return $wpTwitterWidget->getSettings( $instance );
		}
		
		function check_plugin() {
			if(!is_plugin_active( 'twitter-widget-pro/wp-twitter-widget.php')) {
				echo __('Sorry, this block requires the <a href="http://wordpress.org/extend/plugins/twitter-widget-pro/">Twitter Widget Pro</a> plugin to be installed & activated. Please install/activate the plugin before using this block', 'pmc-themes');
				return false;
			}
			return true;
		}
		
		function form($instance) {
		
			if(!$this->check_plugin()) return false;
			
			$instance = $this->_getInstanceSettings( $instance );
			extract($instance);
			
			?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Twitter username:', $this->_slug ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php esc_attr_e( $instance['username'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Give the feed a title ( optional ):', $this->_slug ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php esc_attr_e( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'items' ); ?>"><?php _e( 'How many items would you like to display?', $this->_slug ); ?></label>
				<select id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>">
					<?php
						for ( $i = 1; $i <= 20; ++$i ) {
							echo "<option value='$i' ". selected( $instance['items'], $i, false ). ">$i</option>";
						}
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'avatar' ); ?>"><?php _e( 'Display profile image?', $this->_slug ); ?></label>
				<select id="<?php echo $this->get_field_id( 'avatar' ); ?>" name="<?php echo $this->get_field_name( 'avatar' ); ?>">
					<option value=""<?php selected( $instance['avatar'], '' ) ?>><?php _e( 'Do not show', $this->_slug ); ?></option>
					<option value="mini"<?php selected( $instance['avatar'], 'mini' ) ?>><?php _e( 'Mini - 24px by 24px', $this->_slug ); ?></option>
					<option value="normal"<?php selected( $instance['avatar'], 'normal' ) ?>><?php _e( 'Normal - 48px by 48px', $this->_slug ); ?></option>
					<option value="bigger"<?php selected( $instance['avatar'], 'bigger' ) ?>><?php _e( 'Bigger - 73px by 73px', $this->_slug ); ?></option>
					<option value="original"<?php selected( $instance['avatar'], 'original' ) ?>><?php _e( 'Original', $this->_slug ); ?></option>
				</select>
			</p>
			<p>
				<input type="hidden" value="false" name="<?php echo $this->get_field_name( 'showretweets' ); ?>" />
				<input class="checkbox" type="checkbox" value="true" id="<?php echo $this->get_field_id( 'showretweets' ); ?>" name="<?php echo $this->get_field_name( 'showretweets' ); ?>"<?php checked( $instance['showretweets'], 'true' ); ?> />
				<label for="<?php echo $this->get_field_id( 'showretweets' ); ?>"><?php _e( 'Include retweets', $this->_slug ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" value="true" id="<?php echo $this->get_field_id( 'hidereplies' ); ?>" name="<?php echo $this->get_field_name( 'hidereplies' ); ?>"<?php checked( $instance['hidereplies'], 'true' ); ?> />
				<label for="<?php echo $this->get_field_id( 'hidereplies' ); ?>"><?php _e( 'Hide @replies', $this->_slug ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" value="true" id="<?php echo $this->get_field_id( 'hidefrom' ); ?>" name="<?php echo $this->get_field_name( 'hidefrom' ); ?>"<?php checked( $instance['hidefrom'], 'true' ); ?> />
				<label for="<?php echo $this->get_field_id( 'hidefrom' ); ?>"><?php _e( 'Hide sending applications', $this->_slug ); ?></label>
			</p>
			<p>
				<input type="hidden" value="false" name="<?php echo $this->get_field_name( 'showintents' ); ?>" />
				<input class="checkbox" type="checkbox" value="true" id="<?php echo $this->get_field_id( 'showintents' ); ?>" name="<?php echo $this->get_field_name( 'showintents' ); ?>"<?php checked( $instance['showintents'], 'true' ); ?> />
				<label for="<?php echo $this->get_field_id( 'showintents' ); ?>"><?php _e( 'Show Tweet Intents (reply, retweet, favorite)', $this->_slug ); ?></label>
			</p>
			<p>
				<input type="hidden" value="false" name="<?php echo $this->get_field_name( 'showfollow' ); ?>" />
				<input class="checkbox" type="checkbox" value="true" id="<?php echo $this->get_field_id( 'showfollow' ); ?>" name="<?php echo $this->get_field_name( 'showfollow' ); ?>"<?php checked( $instance['showfollow'], 'true' ); ?> />
				<label for="<?php echo $this->get_field_id( 'showfollow' ); ?>"><?php _e( 'Show Follow Link', $this->_slug ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'errmsg' ); ?>"><?php _e( 'What to display when Twitter is down ( optional ):', $this->_slug ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'errmsg' ); ?>" name="<?php echo $this->get_field_name( 'errmsg' ); ?>" type="text" value="<?php esc_attr_e( $instance['errmsg'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'showts' ); ?>"><?php _e( 'Show date/time of Tweet ( rather than 2 ____ ago ):', $this->_slug ); ?></label>
				<select id="<?php echo $this->get_field_id( 'showts' ); ?>" name="<?php echo $this->get_field_name( 'showts' ); ?>">
					<option value="0" <?php selected( $instance['showts'], '0' ); ?>><?php _e( 'Always', $this->_slug );?></option>
					<option value="3600" <?php selected( $instance['showts'], '3600' ); ?>><?php _e( 'If over an hour old', $this->_slug );?></option>
					<option value="86400" <?php selected( $instance['showts'], '86400' ); ?>><?php _e( 'If over a day old', $this->_slug );?></option>
					<option value="604800" <?php selected( $instance['showts'], '604800' ); ?>><?php _e( 'If over a week old', $this->_slug );?></option>
					<option value="2592000" <?php selected( $instance['showts'], '2592000' ); ?>><?php _e( 'If over a month old', $this->_slug );?></option>
					<option value="31536000" <?php selected( $instance['showts'], '31536000' ); ?>><?php _e( 'If over a year old', $this->_slug );?></option>
					<option value="-1" <?php selected( $instance['showts'], '-1' ); ?>><?php _e( 'Never', $this->_slug );?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'dateFormat' ); ?>"><?php echo sprintf( __( 'Format to display the date in, uses <a href="%s">PHP date()</a> format:', $this->_slug ), 'http://php.net/date' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'dateFormat' ); ?>" name="<?php echo $this->get_field_name( 'dateFormat' ); ?>" type="text" value="<?php esc_attr_e( $instance['dateFormat'] ); ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" value="true" id="<?php echo $this->get_field_id( 'targetBlank' ); ?>" name="<?php echo $this->get_field_name( 'targetBlank' ); ?>"<?php checked( $instance['targetBlank'], 'true' ); ?> />
				<label for="<?php echo $this->get_field_id( 'targetBlank' ); ?>"><?php _e( 'Open links in a new window', $this->_slug ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" value="true" id="<?php echo $this->get_field_id( 'showXavisysLink' ); ?>" name="<?php echo $this->get_field_name( 'showXavisysLink' ); ?>"<?php checked( $instance['showXavisysLink'], 'true' ); ?> />
				<label for="<?php echo $this->get_field_id( 'showXavisysLink' ); ?>"><?php _e( 'Show Link to Twitter Widget Pro', $this->_slug ); ?></label>
			</p>
			
			<?php
			
		}
		
		function block($instance) {
		
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if(is_plugin_active( 'twitter-widget-pro/wp-twitter-widget.php')){	
				$args = $this->_getInstanceSettings( $instance );
				$clear = array(
					'before_widget'   => '',
					'after_widget'    => '',
					'before_title'    => '',
					'after_title'     => ''
				);
				
				$defaults = wp_parse_args($clear, $args);
				$wpTwitterWidget = wpTwitterWidget::getInstance();
				
				if($instance['title']) echo '<h4 class="aq-block-title">'.$instance['title'].'</h4>';
				
					echo $wpTwitterWidget->display( wp_parse_args( $instance, $defaults ) );
			}
			else echo 'You need to install Twitter Widget Pro if you wish to use this block!';
		}
		
		function update($new_instance, $old_instance) {
			$new_instance = aq_recursive_sanitize($new_instance);
			return $new_instance;
		}
	
	}

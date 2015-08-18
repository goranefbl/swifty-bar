<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    sb_bar
 * @subpackage sb_bar/admin
 */
class sb_bar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $sb_bar       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $sb_bar, $version ) {

		$this->sb_bar = $sb_bar;
		$this->version = $version;

	}

	/**
	 * Register the Settings page.
	 *
	 * @since    1.0.0
	 */
	public function sb_bar_admin_menu() {

		 add_options_page( __('Swifty Bar', $this->sb_bar), __('Swifty Bar', $this->sb_bar), 'manage_options', $this->sb_bar, array($this, 'display_plugin_admin_page'));

	}

	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page(){	
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sb-bar-admin-display.php';
		
	}

	/**
	 * Plugin Settings Link on plugin page
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function add_settings_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=sb_bar' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}

	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){

		// register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting(
			$this->sb_bar . '_options',
			$this->sb_bar . '_options',
			array( $this, 'sanitize' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->sb_bar . '-display-options', // section
			apply_filters( $this->sb_bar . '-display-section-title', __( '', $this->sb_bar ) ),
			array( $this, 'display_options_section' ),
			$this->sb_bar
		);

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'disable-bar',
			apply_filters( $this->sb_bar . '-disable-bar-label', __( 'Disable Bar', $this->sb_bar ) ),
			array( $this, 'disable_bar_options_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options' // section to add to
		);
		add_settings_field(
			'post-type',
			apply_filters( $this->sb_bar . '-post-type-label', __( 'Show on which post types', $this->sb_bar ) ),
			array( $this, 'post_type' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options' // section to add to
		);
		add_settings_field(
			'ttr-text',
			apply_filters( $this->sb_bar . '-ttr-text-label', __( 'Change "Time to read" text', $this->sb_bar ) ),
			array( $this, 'ttr_input_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'by-text',
			apply_filters( $this->sb_bar . '-author-text-label', __( 'Change Author "by" text', $this->sb_bar ) ),
			array( $this, 'author_input_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'wpm-left',
			apply_filters( $this->sb_bar . '-wpm-left', __( 'Words Per Minute', $this->sb_bar ) ),
			array( $this, 'wpm_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);

		add_settings_field(
			'comment-box-id',
			apply_filters( $this->sb_bar . '-comment-box-label', __( 'Comment box ID', $this->sb_bar ) ),
			array( $this, 'comment_box_id' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'prev-next-posts',
			apply_filters( $this->sb_bar . '-prev-next-posts', __( 'Prev/Next Posts', $this->sb_bar ) ),
			array( $this, 'prev_next_posts' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'custom-color',
			apply_filters( $this->sb_bar . '-custom-color', __( 'Choose Color', $this->sb_bar ) ),
			array( $this, 'custom_color' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'custom-title',
			apply_filters( $this->sb_bar . '-custom-title', __( 'Shorten Big Post Titles', $this->sb_bar ) ),
			array( $this, 'custom_title' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->sb_bar . '-enable-options', // section
			apply_filters( $this->sb_bar . '-display-section-title', __( '', $this->sb_bar ) ),
			array( $this, 'display_options_section' ),
			$this->sb_bar . '-enable'
		);

		add_settings_field(
			'disable-author',
			apply_filters( $this->sb_bar . '-disable-author', __( 'Disable Author', $this->sb_bar ) ),
			array( $this, 'disable_author' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-ttr',
			apply_filters( $this->sb_bar . '-disable-ttr', __( 'Disable Time to Read', $this->sb_bar ) ),
			array( $this, 'disable_ttr' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-comments',
			apply_filters( $this->sb_bar . '-disable-comments', __( 'Disable Comments', $this->sb_bar ) ),
			array( $this, 'disable_comments' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-share',
			apply_filters( $this->sb_bar . '-disable-share', __( 'Disable Share', $this->sb_bar ) ),
			array( $this, 'disable_share' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-pinterest',
			apply_filters( $this->sb_bar . '-disable-pinterest', __( 'Disable Pinterest Button', $this->sb_bar ) ),
			array( $this, 'disable_pinterest' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-linkedin',
			apply_filters( $this->sb_bar . '-disable-linkedin', __( 'Disable LinkedIn Button', $this->sb_bar ) ),
			array( $this, 'disable_linkedin' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-googleplus',
			apply_filters( $this->sb_bar . '-disable-googleplus', __( 'Disable Google Plus Button', $this->sb_bar ) ),
			array( $this, 'disable_googleplus' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-twitter',
			apply_filters( $this->sb_bar . '-disable-twitter', __( 'Disable Twitter Button', $this->sb_bar ) ),
			array( $this, 'disable_twitter' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
		add_settings_field(
			'disable-facebook',
			apply_filters( $this->sb_bar . '-disable-facebook', __( 'Disable Facebook Button (Why would you do that?)', $this->sb_bar ) ),
			array( $this, 'disable_facebook' ),
			$this->sb_bar . '-enable',
			$this->sb_bar . '-enable-options'
		);
	}

	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function sanitize( $input ) {

		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		if(isset($input)) {
			// Loop through the input and sanitize each of the values
			foreach ( $input as $key => $val ) {

				if($key == 'post-type') { // dont sanitize array
					$new_input[ $key ] = $val;
				} else {
					$new_input[ $key ] = sanitize_text_field( $val );
				}
				
			}

		}

		return $new_input;


	} // sanitize()

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {

		echo '<p>' . $params['title'] . '</p>';

	} // display_options_section()


	/**
	 * Enable Bar Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_bar_options_field() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-bar'] ) ) {

			$option = $options['disable-bar'];

		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-bar]" name="<?php echo $this->sb_bar; ?>_options[disable-bar]" value="1" <?php checked( $option, 1 , true ); ?> />
		<p class="description">Disabling bar is also disabling front end loading of scripts css/js.</p> <?php
	} // disable_bar_options_field()

	/**
	 * Enable Bar Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function post_type() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= array();

		if ( ! empty( $options['post-type'] ) ) {
			$option = $options['post-type'];
		}

		$args = array(
		   'public'   => true
		);
		$post_types = get_post_types( $args, 'names' );

		foreach ( $post_types as $post_type ) {
			if($post_type != 'page' && $post_type != 'attachment') {

				$checked = in_array($post_type, $option) ? 'checked="checked"' : ''; ?>
				<p>
					<input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[post-type]" name="<?php echo $this->sb_bar; ?>_options[post-type][]" value="<?php echo esc_attr( $post_type ); ?>" <?php echo $checked; ?> />
		   			<?php echo $post_type; ?>			
		   		</p>
			<?php }
				
		}  ?>
			<p class="description">IMPORTANT: Bar will not show up untill one of these is checked.</p>
	<?php 
	} // post_type()

	/**
	 * Time to read text field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function ttr_input_field() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'time to read:';

		if ( ! empty( $options['ttr-text'] ) ) {
			$option = $options['ttr-text'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[ttr-text]" name="<?php echo $this->sb_bar; ?>_options[ttr-text]" value="<?php echo esc_attr( $option ); ?>">
		<?php
	} // ttr_input_field()

	/**
	 * Author Text Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function author_input_field() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'by';

		if ( ! empty( $options['by-text'] ) ) {
			$option = $options['by-text'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[by-text]" name="<?php echo $this->sb_bar; ?>_options[by-text]" value="<?php echo esc_attr( $option ); ?>">
		<?php
	} // author_input_field()


	/**
	 * Word Per Minute Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function wpm_field() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= '250';

		if ( ! empty( $options['wpm-text'] ) ) {
			$option = $options['wpm-text'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[wpm-text]" name="<?php echo $this->sb_bar; ?>_options[wpm-text]" value="<?php echo esc_attr( $option ); ?>">
		<p class="description">They say 250 words per minute is avarage read time, you can increase/decrease it here. After which plugin will calculate new time to read per article.</p>
		<?php
	} // wpm_field()

	/**
	 * Comments box ID
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function comment_box_id() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'comments';

		if ( ! empty( $options['comment-box-id'] ) ) {
			$option = $options['comment-box-id'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[comment-box-id]" name="<?php echo $this->sb_bar; ?>_options[comment-box-id]" value="<?php echo esc_attr( $option ); ?>">
		<p class="description">(without #) This is needed for comment to scroll to comment box on click. Default one is "comments".</p>
		<?php
	} // comment_box_id()


	/**
	 * Prev/Next Posts
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function prev_next_posts() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= '';

		if ( ! empty( $options['prev-next-posts'] ) ) {
			$option = $options['prev-next-posts'];
		}

		?>
		<select id="<?php echo $this->sb_bar; ?>_options[prev-next-posts]" name="<?php echo $this->sb_bar; ?>_options[prev-next-posts]" >
			<option value="cat" <?php selected( $option, "cat" ); ?> >Posts from Same Category</option>
			<option value="tags" <?php selected( $option, "tags" ); ?> >Posts with same Tags</option>
			<option value="all" <?php selected( $option, "all" ); ?> >All Posts</option>
		</select>
		<p class="description">Do you want prev/next buttons to show posts from all categories or just from current post category?</p>
		<?php

	} // prev_next_posts()

	/**
	 * Custom Color Scheme
	 *
	 * @since 		1.1.0
	 * @return 		mixed 			The settings field
	 */
	public function custom_color() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'default';

		if ( ! empty( $options['custom-color'] ) ) {
			$option = $options['custom-color'];
		}

		?>
		<select id="<?php echo $this->sb_bar; ?>_options[custom-color]" name="<?php echo $this->sb_bar; ?>_options[custom-color]" >
			<option value="default" <?php selected( $option, "default" ); ?> >Default (Everyone loves blue)</option>
			<option value="white" <?php selected( $option, "white" ); ?> >Clouds(White)</option>
			<option value="green" <?php selected( $option, "green" ); ?> >Green</option>
			<option value="orange" <?php selected( $option, "orange" ); ?> >Orange</option>
			<option value="red" <?php selected( $option, "red" ); ?> >Red</option>
			<option value="purple" <?php selected( $option, "purple" ); ?> >Purple</option>
			<option value="asphalt" <?php selected( $option, "asphalt" ); ?> >Wet Asphalt</option>
		</select>
		<p class="description">Choose one of predefined colors. Color picker will come later, remember - keeping plugin fast and light is priority.</p>
		<?php

	} // custom_color()

	/**
	 * Custom Title
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function custom_title() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= '';

		if ( ! empty( $options['custom-title'] ) ) {
			$option = $options['custom-title'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[custom-title]" name="<?php echo $this->sb_bar; ?>_options[custom-title]" value="<?php echo esc_attr( $option ); ?>">
		<p class="description">You can shorten long post titles so that share buttons can fit. Enter maximum number of letters title can have before cutting it and placing three dots. Leave blank to always show full title.</p>
		<?php
	} // custom_title()

	/**
	 * Disable Author Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_author() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-author'] ) ) {
			$option = $options['disable-author'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-author]" name="<?php echo $this->sb_bar; ?>_options[disable-author]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_author()

	/**
	 * Disable TTR Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_ttr() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-ttr'] ) ) {
			$option = $options['disable-ttr'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-ttr]" name="<?php echo $this->sb_bar; ?>_options[disable-ttr]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_ttr()

	/**
	 * Disable Share Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_share() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-share'] ) ) {
			$option = $options['disable-share'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-share]" name="<?php echo $this->sb_bar; ?>_options[disable-share]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_share()

	/**
	 * Disable Comments Box
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_comments() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-comments'] ) ) {
			$option = $options['disable-comments'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-comments]" name="<?php echo $this->sb_bar; ?>_options[disable-comments]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_comments()

	/**
	 * Disable Pinterest Button
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_pinterest() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-pinterest'] ) ) {
			$option = $options['disable-pinterest'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-pinterest]" name="<?php echo $this->sb_bar; ?>_options[disable-pinterest]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_pinterest()

	/**
	 * Disable Linkedin Button
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_linkedin() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-linkedin'] ) ) {
			$option = $options['disable-linkedin'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-linkedin]" name="<?php echo $this->sb_bar; ?>_options[disable-linkedin]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_linkedin()

	/**
	 * Disable Google Plus
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_googleplus() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-googleplus'] ) ) {
			$option = $options['disable-googleplus'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-googleplus]" name="<?php echo $this->sb_bar; ?>_options[disable-googleplus]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_googleplus()

	/**
	 * Disable Twitter
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_twitter() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-twitter'] ) ) {
			$option = $options['disable-twitter'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-twitter]" name="<?php echo $this->sb_bar; ?>_options[disable-twitter]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_twitter()

	/**
	 * Disable Facebook
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function disable_facebook() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-facebook'] ) ) {
			$option = $options['disable-facebook'];
		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-facebook]" name="<?php echo $this->sb_bar; ?>_options[disable-facebook]" value="1" <?php checked( $option, 1 , true ); ?> />

		<?php
	} // disable_facebook()
}

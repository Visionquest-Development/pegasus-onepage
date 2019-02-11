<?php
/*
Plugin Name: Pegasus One Page Plugin
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: This allows you to create a one page template on your website with just a shortcode.
Version:     1.0
Author:      Jim O'Brien
Author URI:  https://visionquestdevelopment.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

	/**
	 * Silence is golden; exit if accessed directly
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	function pegasus_one_page_menu_item() {
		//add_menu_page("One page", "One page", "manage_options", "pegasus_one_page_plugin_options", "pegasus_one_page_plugin_settings_page", null, 99);
		//add_submenu_page("pegasus_one_page_plugin_options", "Shortcode Usage", "Usage", "manage_options", "pegasus_one_page_plugin_shortcode_options", "pegasus_one_page_plugin_shortcode_settings_page" );
	}
	add_action("admin_menu", "pegasus_one_page_menu_item");

	function pegasus_one_page_plugin_settings_page() { ?>
	    <div class="wrap pegasus-wrap">
	    <h1>One page</h1>
		
		<form method="post" action="options.php">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");      
	            submit_button(); 
	        ?>          
			<?php 
				//$selected_page = get_option( 'pegasus_onepage_page_select' );
				//echo $selected_page;
			?>
	    </form>
		
		
			
			<p>Section shortcode Usage: <pre>[section id="services" class="testing"]Sed porttitor lectus nibh. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur aliquet quam id dui posuere blandit. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Donec sollicitudin molestie malesuada.[/section][section id="picture" class="test" bkg_color="#dedede"]<?php echo htmlspecialchars('<img src="http://www.fillmurray.com/960/550">'); ?>[/section][section id="picture" class="test" bkg="http://www.fillmurray.com/960/550" ]Pellentesque in ipsum id orci porta dapibus. Nulla quis lorem ut libero malesuada feugiat. Cras ultricies ligula sed magna dictum porta. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus suscipit tortor eget felis porttitor volutpat. Pellentesque in ipsum id orci porta dapibus.[/section]</pre></p>
		
			<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>
		
		
		</div>
	<?php
	}
	
	/*function pegasus_one_page_plugin_shortcode_settings_page() { ?>
		<div class="wrap pegasus-wrap">
			<h1>Shortcode Usage</h1>
			
			<p>Logo Slider Usage: <pre>[logo_slider the_query="post_type=logo_slider&showposts=100" ]</pre></p>
			
			<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>
		
		</div>
		<?php
	} */
	
	/* add_filter( 'body_class', function( $classes ) {
		return array_merge( $classes, array( 'scroll-active' ) );
	} ); */
	
	function pegasus_one_page_plugin_styles() {

		wp_register_style( 'one-page-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/one-page.css', array(), null, 'all' );
		
	}
	add_action( 'wp_enqueue_scripts', 'pegasus_one_page_plugin_styles' );
	
	
	function onepage_page_select_option() { ?>
		<select name="pegasus_onepage_page_select"> 
			<option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
			<?php
				$selected_page = get_option( 'pegasus_onepage_page_select' );
				$pages = get_pages(); 
				foreach ( $pages as $page ) {
					$option = '<option value="' . $page->ID . '" ';
					$option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
					$option .= '>';
					$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
				}
			?>
		</select>

		
		<?php
	}
	
	function display_onepage_plugin_panel_fields() { 
		add_settings_section("section", "Shortcode Settings", null, "theme-options");
	
		add_settings_field("pegasus_onepage_page_select", "Select page to render on:", "onepage_page_select_option", "theme-options", "section");
		
		/*================
		REGISTER SETTINGS
		=================*/

		register_setting("section", "pegasus_onepage_page_select");
		
	}
	add_action("admin_init", "display_onepage_plugin_panel_fields");
	
	
	
	
	
	/**
	* Proper way to enqueue JS 
	*/
	function pegasus_one_page_plugin_js() {
		
		//wp_enqueue_script( 'one-page-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.onepage-scroll.js', array( 'jquery' ), null, true );
		//wp_enqueue_script( 'snap-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.snapscroll.js', array( 'jquery' ), null, true );
		
		//$selected_page = get_option( 'pegasus_onepage_page_select' );
		
		//$check_page_value = ( isset($selected_page) ? $selected_page : 'home' );
		
		//if ( is_page( $check_page_value ) ) {
		wp_register_script( 'scrollspy-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/scrollspy.js', array( 'jquery' ), null, 'all' );

		wp_register_script( 'scrollify-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/scrollify.js', array( 'jquery' ), null, 'all' );

		wp_register_script( 'pegasus-one-page-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, 'all' );
		//}
	} //end function
	
	add_action( 'wp_enqueue_scripts', 'pegasus_one_page_plugin_js' ); 
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~META BOXES~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/*--- Demo URL meta box ---*/
	/*
	add_action('admin_init','onepage_meta_init');
	 
	function onepage_meta_init() {
		// add a meta box for WordPress 'project' type
		//add_meta_box('onepage_meta', 'onepage Options', 'onepage_meta_setup', 'onepage', 'side', 'high');
		add_meta_box('onepage_meta', 'OnePage plugin Options', 'onepage_meta_setup', 'page', 'normal', 'high');
	  
		// add a callback function to save any data a user enters in
		add_action('save_post','onepage_meta_save');
	}
	 
	function onepage_meta_setup() {
		global $post;
		  
		?>
			<div class="onepage_meta_control">
				<label for="_onepage_option">
					<?php 
						$onepage_option = get_post_meta($post->ID,'_onepage_option',TRUE); 
						echo $onepage_option;
						$echo_chk = 'checked="checked" ';
					?>
					<input type="checkbox" name="_onepage_option" id="onepage-option" value="yes" <?php checked( $onepage_option, 'yes', $echo_chk ); ?> />
					<?php _e( 'Enable Scrollify for this Page', 'pegasus' )?>
				</label>
			</div>
		<?php
	 
		// create for validation
		echo '<input type="hidden" name="meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}
	 
	function onepage_meta_save($post_id) {
		// check nonce
		if (!isset($_POST['meta_noncename']) || !wp_verify_nonce($_POST['meta_noncename'], __FILE__)) {
			return $post_id;
		}
	 
		// check capabilities
		if ('post' == $_POST['post_type']) {
			if (!current_user_can('edit_post', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	 
		// exit on autosave
		if (defined('DOING_AUTOSAVE') == DOING_AUTOSAVE) {
			return $post_id;
		}
	 
		
		
		// Checks for input and saves
		if( isset( $_POST[ '_onepage_option' ] ) ) {
			update_post_meta( $post_id, '_onepage_option', 'yes' );
		} else {
			update_post_meta( $post_id, '_onepage_option', '' );
		}
		
	}
	*/
	/*--- #end  Demo URL meta box ---*/
	
	
	
	
	/*~~~~~~~~~~~~~~~~~~~~
		SECTION
	~~~~~~~~~~~~~~~~~~~~~*/
	// [section id="testimonials"] text [/section]
	function pegasus_section_func( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'id' => '#',
			'class' => '',
			'bkg_color' => '',
			'image' => ""
		), $atts );
		
		$id_chk = "{$a['id']}";
		$class_chk = "{$a['class']}";
		$bkg_color_chk = "{$a['bkg_color']}";
		$bkg_img_chk = "{$a['image']}";
		
		$output = '';
		
		if( $bkg_img_chk ) {
			$output .= "<section id='{$a['id']}' class='side {$a['class']}' style='background: url({$a['image']}) no-repeat center center;' >";
		} elseif ( $bkg_color_chk ) {
			$output .= "<section id='{$a['id']}' class='side {$a['class']}' style='background: {$a['bkg_color']};' >";
		} else {
			$output .= "<section id='{$a['id']}' class='side {$a['class']}'>";
		}
			$output .= "<div class='section-container'>";
				$output .= $content;
			$output .= '</div>';
		$output .= '</section>';


		wp_enqueue_style( 'one-page-css' );
		//wp_enqueue_script( 'scrollspy-js' );
		//wp_enqueue_script( 'scrollify-js' );
		//wp_enqueue_script( 'pegasus-one-page-plugin-js' );

		return $output; 
	}
	add_shortcode( 'section', 'pegasus_section_func' );
	
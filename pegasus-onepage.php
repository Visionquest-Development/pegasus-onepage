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
		add_menu_page("One page", "One page", "manage_options", "pegasus_one_page_plugin_options", "pegasus_one_page_plugin_settings_page", null, 99);
		//add_submenu_page("pegasus_one_page_plugin_options", "Shortcode Usage", "Usage", "manage_options", "pegasus_one_page_plugin_shortcode_options", "pegasus_one_page_plugin_shortcode_settings_page" );
	}
	add_action("admin_menu", "pegasus_one_page_menu_item");

	function pegasus_one_page_plugin_settings_page() { ?>
	    <div class="wrap pegasus-wrap">
	    <h1>One page</h1>
		<?php /* ?>
		<form method="post" action="options.php">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");      
	            submit_button(); 
	        ?>          
	    </form>
		<?php */ ?>
		
			
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
	
	function pegasus_one_page_plugin_styles() {
		
		wp_enqueue_style( 'one-page-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/one-page.css', array(), null, 'all' );
		//wp_enqueue_style( 'slippery-slider-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/slippery-slider.css', array(), null, 'all' );
		
	}
	add_action( 'wp_enqueue_scripts', 'pegasus_one_page_plugin_styles' );
	
	/**
	* Proper way to enqueue JS 
	*/
	function pegasus_one_page_plugin_js() {
		
		//wp_enqueue_script( 'one-page-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.onepage-scroll.js', array( 'jquery' ), null, true );
		//wp_enqueue_script( 'snap-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.snapscroll.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'scrollspy-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/scrollspy.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'scrollify-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/scrollify.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'pegasus-one-page-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, true );
		
	} //end function
	add_action( 'wp_enqueue_scripts', 'pegasus_one_page_plugin_js' );
	
	/*~~~~~~~~~~~~~~~~~~~~
		SECTION
	~~~~~~~~~~~~~~~~~~~~~*/
	// [section id="testimonials"] text [/section]
	function octane_section_func( $atts, $content = null ) {
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
		
		
		return $output; 
	}
	add_shortcode( 'section', 'octane_section_func' );
	
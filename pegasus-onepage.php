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

	function pegasus_onepage_admin_table_css() {
		if ( onepage_check_main_theme_name() == 'Pegasus' || onepage_check_main_theme_name() == 'Pegasus Child' ) {
			//do nothing
		} else {
			//wp_register_style('onepage-admin-table-css', trailingslashit(plugin_dir_url(__FILE__)) . 'css/pegasus-onepage-admin-table.css', array(), null, 'all');
			ob_start();
			?>
				pre {
					background-color: #f9f9f9;
					border: 1px solid #aaa;
					page-break-inside: avoid;
					font-family: monospace;
					font-size: 15px;
					line-height: 1.6;
					margin-bottom: 1.6em;
					max-width: 100%;
					overflow: auto;
					padding: 1em 1.5em;
					display: block;
					word-wrap: break-word;
				}
				input[type="text"].code {
					width: 100%;
				}
				table.pegasus-table {
					width: 100%;
					border-collapse: collapse;
					border-color: #777 !important;
				}
				table.pegasus-table th {
					background-color: #f1f1f1;
					text-align: left;
				}
				table.pegasus-table th,
				table.pegasus-table td {
					border: 1px solid #ddd;
					padding: 8px;
				}
				table.pegasus-table tr:nth-child(even) {
					background-color: #f2f2f2;
				}
				table.pegasus-table thead tr { background-color: #282828; }
				table.pegasus-table thead tr td { padding: 10px; }
				table.pegasus-table thead tr td strong { color: white; }
				table.pegasus-table tbody tr:nth-child(0) { background-color: #cccccc; }
				table.pegasus-table tbody tr td { padding: 10px; }
				table.pegasus-table code { color: #d63384; }

			<?php
			// Get the buffered content
			$inline_css = ob_get_clean();

			wp_register_style('onepage-admin-table-css', false);
			wp_enqueue_style('onepage-admin-table-css');

			wp_add_inline_style('onepage-admin-table-css', $inline_css);
		}
	}

	add_action('admin_enqueue_scripts', 'pegasus_onepage_admin_table_css');

	function onepage_check_main_theme_name() {
		$current_theme_slug = get_option('stylesheet'); // Slug of the current theme (child theme if used)
		$parent_theme_slug = get_option('template');    // Slug of the parent theme (if a child theme is used)

		//error_log( "current theme slug: " . $current_theme_slug );
		//error_log( "parent theme slug: " . $parent_theme_slug );

		if ( $current_theme_slug == 'pegasus' ) {
			return 'Pegasus';
		} elseif ( $current_theme_slug == 'pegasus-child' ) {
			return 'Pegasus Child';
		} else {
			return 'Not Pegasus';
		}
	}

	function pegasus_onepage_menu_item() {
		if ( onepage_check_main_theme_name() == 'Pegasus' || onepage_check_main_theme_name() == 'Pegasus Child' ) {
			//do nothing
		} else {
			//echo 'This is NOT the Pegasus theme';
			add_menu_page(
				"Onepage", // Page title
				"Onepage", // Menu title
				"manage_options", // Capability
				"pegasus_onepage_plugin_options", // Menu slug
				"pegasus_one_page_plugin_settings_page", // Callback function
				null, // Icon
				87 // Position in menu
			);

			add_submenu_page(
				"pegasus_onepage_plugin_options", //parent slug
				"Shortcode Usage", // Menu title
				"Usage", // Menu title
				"manage_options", // Capability
				"pegasus_onepage_plugin_shortcode_options", // Menu slug
				"pegasus_onepage_plugin_settings_page" // Callback function
			);
		}
	}
	add_action("admin_menu", "pegasus_onepage_menu_item");

	//function pegasus_one_page_menu_item() {
		//add_menu_page("One page", "One page", "manage_options", "pegasus_one_page_plugin_options", "pegasus_one_page_plugin_settings_page", null, 99);
		//add_submenu_page("pegasus_one_page_plugin_options", "Shortcode Usage", "Usage", "manage_options", "pegasus_one_page_plugin_shortcode_options", "pegasus_one_page_plugin_shortcode_settings_page" );
	//}
	//add_action("admin_menu", "pegasus_one_page_menu_item");

	function pegasus_one_page_plugin_settings_page() { ?>
	    <div class="wrap pegasus-wrap">
			<h1>One page</h1>

			<form method="post" action="options.php">
				<?php
					settings_fields("onepage_section");
					do_settings_sections("onepage-theme-options");
					submit_button();
				?>
				<?php
					//$selected_page = get_option( 'pegasus_onepage_page_select' );
					//echo $selected_page;
				?>
			</form>
		</div>
	<?php
	}


	function pegasus_onepage_plugin_settings_page() { ?>
	    <div class="wrap pegasus-wrap">
			<h1>onepage Usage</h1>

			<div>
				<h3>onepage Usage 1:</h3>

				<pre>[section][/section]</pre>

				<input
					type="text"
					readonly
					value="<?php echo esc_html('[section][/section]'); ?>"
					class="regular-text code"
					id="my-shortcode"
					onClick="this.select();"
				>
			</div>

			<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>

			<div>
				<?php echo pegasus_onepage_settings_table(); ?>
			</div>
		</div>
	<?php
	}

	function pegasus_onepage_settings_table() {

		$data = json_decode( file_get_contents( plugin_dir_path( __FILE__ ) . 'settings.json' ), true );

		if (json_last_error() !== JSON_ERROR_NONE) {
			return '<p style="color: red;">Error: Invalid JSON provided.</p>';
		}

		// Start building the HTML
		$html = '<table border="0" cellpadding="1" class="table pegasus-table" align="left">
		<thead>
		<tr style="background-color: #282828;">
		<td <span><strong>Name</strong></span></td>
		<td <span><strong>Attribute</strong></span></td>
		<td <span><strong>Options</strong></span></td>
		<td <span><strong>Description</strong></span></td>
		<td <span><strong>Example</strong></span></td>
		</tr>
		</thead>
		<tbody>';

		// Iterate over the data to populate rows
		if (!empty($data['rows'])) {
			foreach ($data['rows'] as $section) {
				// Add section header
				$html .= '<tr >';
				$html .= '<td colspan="5">';
				$html .= '<span>';
				$html .= '<strong>' . htmlspecialchars($section['section_name']) . '</strong>';
				$html .= '</span>';
				$html .= '</td>';
				$html .= '</tr>';

				// Add rows in the section
				foreach ($section['rows'] as $row) {
					$html .= '<tr>
						<td >' . htmlspecialchars($row['name']) . '</td>
						<td >' . htmlspecialchars($row['attribute']) . '</td>
						<td >' . nl2br(htmlspecialchars($row['options'])) . '</td>
						<td >' . nl2br(htmlspecialchars($row['description'])) . '</td>
						<td ><code>' . htmlspecialchars($row['example']) . '</code></td>
					</tr>';
				}
			}
		}

		$html .= '</tbody></table>';

		// Return the generated HTML
		return $html;
	}

	add_filter( 'body_class', function( $classes ) {
		return array_merge( $classes, array( 'scroll-active' ) );
	} );

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
		add_settings_section("onepage_section", "Shortcode Settings", null, "onepage-theme-options");

		add_settings_field("pegasus_onepage_page_select", "Select page to render on:", "onepage_page_select_option", "onepage-theme-options", "onepage_section");

		//================
		//REGISTER SETTINGS
		//=================

		register_setting("onepage_section", "pegasus_onepage_page_select");

	}
	add_action("admin_init", "display_onepage_plugin_panel_fields");




	/**
	* Proper way to enqueue JS
	*/
	function pegasus_one_page_plugin_js() {

		//wp_enqueue_script( 'one-page-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.onepage-scroll.js', array( 'jquery' ), null, true );
		//wp_enqueue_script( 'snap-scroll-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.snapscroll.js', array( 'jquery' ), null, true );

		$selected_page = get_option( 'pegasus_onepage_page_select' );

		$check_page_value = ( isset($selected_page) ? $selected_page : 'home' );

		//convert to int
		$check_page_value = intval( $check_page_value );

		var_dump( $check_page_value);


		if ( is_page( $check_page_value ) ) {
			wp_register_script( 'scrollspy-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/scrollspy.js', array( 'jquery' ), null, 'all' );

			wp_register_script( 'scrollify-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/scrollify.js', array( 'jquery' ), null, 'all' );

			wp_register_script( 'pegasus-one-page-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, 'all' );
		}
	} //end function

	add_action( 'wp_enqueue_scripts', 'pegasus_one_page_plugin_js' );


	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~META BOXES~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/*--- Demo URL meta box ---*/

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
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}



		// Checks for input and saves
		if( isset( $_POST[ '_onepage_option' ] ) ) {
			update_post_meta( $post_id, '_onepage_option', 'yes' );
		} else {
			update_post_meta( $post_id, '_onepage_option', '' );
		}

	}

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
				$output .= do_shortcode( $content );
			$output .= '</div>';
		$output .= '</section>';


		wp_enqueue_style( 'one-page-css' );
		wp_enqueue_script( 'scrollspy-js' );
		wp_enqueue_script( 'scrollify-js' );
		wp_enqueue_script( 'pegasus-one-page-plugin-js' );

		return $output;
	}
	add_shortcode( 'section', 'pegasus_section_func' );

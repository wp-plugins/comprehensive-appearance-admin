<?php
 /**
  * Plugin Name: Comprehensive Appearance Admin
  * Plugin URI:  http://wpmulti.org/comprehensive-appearance-admin
  * Description: Display a better, comprehensive Appearance Menu in the Dashboard and in the front-end Toolbar.
  * Version:     0.1.1
  * Author:      Martin Robbins
  * Author URI:  http://wpmulti.org
  * License:     GPL2 or later
  * License URI: https://www.gnu.org/licenses/gpl-2.0.html
  */


// Show all the items in the Dashboard Appearance Menu
add_action( 'admin_enqueue_scripts', 'caa_style', 999 );
function caa_style() {
	if ( is_admin() ) {
		wp_enqueue_style( 'caa-style' , plugins_url( 'caa-style.css', __FILE__ ) , false );
	}
}

// Show all the items in the front-end Toolbar Appearance Menu
add_action( 'wp_enqueue_scripts', 'caa_toolbar_style', 999 );
function caa_toolbar_style() {
	if ( is_user_logged_in() && !is_admin() ) {
		wp_enqueue_style( 'caa-toolbar-style' , plugins_url( 'caa-toolbar-style.css', __FILE__ ) , false );
	}
}

// Modify the titles for Dashboard Customize items Header and Background
add_action ( '_admin_menu', 'caa_modify_submenus', 999 );
function caa_modify_submenus() {
	
	global $submenu;
		
	if ( current_theme_supports( 'custom-header' ) && current_user_can( 'customize') ) {
		$submenu['themes.php'][15][0] =  __( 'Customize Header' );
	}

	if ( current_theme_supports( 'custom-background' ) && current_user_can( 'customize') ) {
		$submenu['themes.php'][20][0] =  __( 'Customize Background' );
	}
}

// Add Dashboard Customize items for Widgets, Menus, and Themes
add_action ( '_admin_menu', 'caa_add_submenus', 999 );
function caa_add_submenus() {

	global $submenu;
	global $customize_url;
	
	$customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
	//$customize_url = site_url() . '/wp-admin/customize.php' ;	
	
	// Add a Customize Widgets menu item at index position 8 (Widgets menu is at 7)
	if ( current_theme_supports( 'widgets' ) ):
		$customize_widgets_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'widgets' ) ), $customize_url );
		$submenu['themes.php'][8] = array( __( 'Customize Widgets' ), 'customize', esc_url( $customize_widgets_url ), '', 'hide-if-no-customize' );
	endif;

	// Add a Customize Menus menu item at index position 11 (Menus menu is at 10)
	if ( current_theme_supports( 'menus' ) ) {
		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'menus' ) ), $customize_url );
		$submenu['themes.php'][11] = array( __( 'Customize Menus' ), 'customize', esc_url( $customize_menus_url ), '', 'hide-if-no-customize' );	
	}

	// add a Customize Themes menu item at index position 21 (Themes is at 5 and Customize is at 6)
	$customize_themes_url = add_query_arg( array( 'autofocus' => array( 'section' => 'themes' ) ), $customize_url );
	$submenu['themes.php'][21] = array( __( 'Customize Themes' ), 'customize', esc_url( $customize_themes_url ), '', 'hide-if-no-customize' );
}

// Add a Toolbar Customize Menus item
add_action( 'admin_bar_menu', 'caa_add_nodes', 999 );
function caa_add_nodes( $wp_admin_bar ) {
	
	global $customize_url;

	//$customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
	$customize_url = site_url() . '/wp-admin/customize.php' ;	
	
	if ( current_theme_supports( 'menus' ) ) {
		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'menus' ) ), $customize_url );
		$args = array(
			'parent'=> 'appearance',
			'id'    => 'caa-customize-menus',
			'title' => 'Customize Menus',
			'href'  => $customize_menus_url,
			'meta'  => array( 'class' => 'hide-if-no-customize caa-customize-menus' )
		);		
		$wp_admin_bar->add_node( $args );
	}
}

// Modify the titles for Toolbar Appearance Menu customize-blahblah items
add_action( 'admin_bar_menu', 'caa_modify_nodes', 999 );
function caa_modify_nodes( $wp_admin_bar ) {

	$caa_modify_nodes = $wp_admin_bar->get_nodes();

	foreach ( $caa_modify_nodes as $node ) {
		
		// use the same node's properties
		$args = $node;

		// prepend the title of some nodes only where id = customize-x
		$customize_x = array (
			'customize-themes',
			'customize-widgets',
			'customize-menus',
			'customize-background',
			'customize-header',		
		);
		$prepend = 'Customize ';
		if ( in_array ( $node->id ,  $customize_x ) ) {			
//			$args->title = '<span class="customize-x">' . $prepend . '</span>' . $node->title;
			$args->title = $prepend . $node->title;
		}

		// update the Toolbar node
		$wp_admin_bar->add_node( $args );
	}
}


?>
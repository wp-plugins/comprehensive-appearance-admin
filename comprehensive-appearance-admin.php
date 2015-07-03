<?php
 /**
  * Plugin Name: Comprehensive Appearance Admin
  * Plugin URI:  http://wpmulti.org/comprehensive-appearance-admin
  * Description: Display a better, comprehensive Appearance Menu in the Dashboard and in the front-end Toolbar.
  * Version:     0.1.3
  * Author:      Martin Robbins
  * Author URI:  http://wpmulti.org
  * License:     GPL2 or later
  * License URI: https://www.gnu.org/licenses/gpl-2.0.html
  */


// Add Dashboard Customize items for Themes, Widgets, Menus,
add_action ( '_admin_menu', 'caa_add_customize_submenus', 999 );
function caa_add_customize_submenus() {
	

	global $submenu;
	global $customize_url;
	
	$customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
	//$customize_url = site_url() . '/wp-admin/customize.php' ;	

	// add a Customize Themes menu item
	$customize_themes_url = add_query_arg( array( 'autofocus' => array( 'section' => 'themes' ) ), $customize_url );
	$submenu['themes.php']['21.1'] = array( __( 'Customize Themes' ), 'customize', esc_url( $customize_themes_url ), '', 'hide-if-no-customize' );
	
	// Add a Customize Widgets menu item
	if ( current_theme_supports( 'widgets' ) ):
		$customize_widgets_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'widgets' ) ), $customize_url );
		$submenu['themes.php']['21.2'] = array( __( 'Customize Widgets' ), 'customize', esc_url( $customize_widgets_url ), '', 'hide-if-no-customize' );
	endif;

	// Add a Customize Menus menu item
	// as of 3 jul7 2015 the core url is
	// http://beta.wpmulti.org/wp-admin/customize.php?autofocus[panel]=nav_menus&return=%2Fwp-admin%2Fnav-menus.php%3Faction%3Dedit%26menu%3D0

	if ( current_theme_supports( 'menus' ) ) {
//		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'menus' ) ), $customize_url );
//		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'section' => 'menus' ) ), $customize_url );
		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'nav_menus' ) ), $customize_url );
		$submenu['themes.php']['21.3'] = array( __( 'Customize Menus' ), 'customize', esc_url( $customize_menus_url ), '', 'hide-if-no-customize' );	
	}

}


// Add Dashboard Old School items for Header, Background
add_action ( '_admin_menu', 'caa_add_old_school_submenus', 999 );
function caa_add_old_school_submenus() {

	global $submenu;

	// Add a Custom Header menu item
	if ( current_theme_supports( 'custom-header' ) && current_user_can( 'edit_theme_options') ) {
		$submenu['themes.php']['21.4'] = array( __( 'Old-School Custom Header' ), 'edit_theme_options', site_url() . '/wp-admin/themes?page=custom-header', '', '' );
	}

	// Add a Custom Background menu item 
	if ( current_theme_supports( 'custom-background' ) && current_user_can( 'edit_theme_options') ) {
		$submenu['themes.php']['21.5'] = array( __( 'Old-School Custom Background' ), 'edit_theme_options', site_url() . '/wp-admin/themes?page=custom-background', '', '' );	
	}

}


// Add Toolbar Appearance Old-School container node
add_action( 'admin_bar_menu', 'caa_add_old_school_node', 999 );
function caa_add_old_school_node( $wp_admin_bar ) {
	
	$args = array(
		'parent'    => 'appearance',
		'id'    => 'caa-old-school',
		'title' => 'Old-School Admin Pages',
		'meta'  => array( 'class' => 'caa-old-school' )
	);
	$wp_admin_bar->add_node( $args );

}


// Add Toolbar Old School items
add_action( 'admin_bar_menu', 'caa_add_old_school_nodes', 999 );
function caa_add_old_school_nodes( $wp_admin_bar ) {

	$args = array(
		'parent'=> 'caa-old-school',
		'id'    => 'caa-os-themes',
		'title' => 'Themes',
		'href'  => admin_url( 'themes.php' ),
		'meta'  => array( 'class' => 'caa-os-themes' )
	);		
	$wp_admin_bar->add_node( $args );

	if ( current_theme_supports( 'widgets' ) && current_user_can( 'edit_theme_options') ) {
		$args = array(
			'parent'=> 'caa-old-school',
			'id'    => 'caa-os-widgets',
			'title' => 'Widgets',
			'href'  => admin_url( 'widgets.php' ),
			'meta'  => array( 'class' => 'caa-os-widgets' )
		);		
		$wp_admin_bar->add_node( $args );
	}

	if ( current_theme_supports( 'menus' ) && current_user_can( 'edit_theme_options') ) {
		$args = array(
			'parent'=> 'caa-old-school',
			'id'    => 'caa-os-menus',
			'title' => 'Menus',
			'href'  => admin_url( 'nav-menus.php' ),
			'meta'  => array( 'class' => 'caa-os-menus' )
		);		
		$wp_admin_bar->add_node( $args );
	}

	if ( current_theme_supports( 'custom-header' ) && current_user_can( 'edit_theme_options') ) {
		$args = array(
			'parent'=> 'caa-old-school',
			'id'    => 'caa-os-header',
			'title' => 'Header',
			'href'  => admin_url( 'themes.php?page=custom-header' ),
			'meta'  => array( 'class' => 'caa-os-header' )
		);		
		$wp_admin_bar->add_node( $args );
	}

	if ( current_theme_supports( 'custom-background' ) && current_user_can( 'edit_theme_options') ) {
		$args = array(
			'parent'=> 'caa-old-school',
			'id'    => 'caa-os-background',
			'title' => 'Background',
			'href'  => admin_url( 'themes.php?page=custom-background' ),
			'meta'  => array( 'class' => 'caa-os-background' )
		);		
		$wp_admin_bar->add_node( $args );
	}

}


// Add a Toolbar Appearance Customize Menus node
add_action( 'admin_bar_menu', 'caa_add_customize_menus_node', 999 );
function caa_add_customize_menus_node( $wp_admin_bar ) {
	
	global $customize_url;

//	$customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
//	$customize_url = site_url() . '/wp-admin/customize.php' ;
	$customize_url = admin_url( 'customize.php' ) ;
	
	if ( current_theme_supports( 'menus' ) && current_user_can( 'customize')  ) {
//		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'menus' ) ), $customize_url );
//		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'section' => 'menus' ) ), $customize_url );
		$customize_menus_url = add_query_arg( array( 'autofocus' => array( 'panel' => 'nav_menus' ) ), $customize_url );
		$args = array(
			'parent'=> 'appearance',
//			'parent'=> 'caa-customize',
			'id'    => 'caa-customize-menus',
			'title' => 'Customize Menus',
			'href'  => $customize_menus_url,
			'meta'  => array( 'class' => 'hide-if-no-customize caa-customize-menus' )
		);		
		$wp_admin_bar->add_node( $args );
	}
}


?>
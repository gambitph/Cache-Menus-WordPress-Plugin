<?php
/*
Plugin Name: Gambit Cache Menus
Description: Shave off a few milliseconds on your site loading time by automatically caching your menus (around 18ms per theme location). Just activate and that's it. This is also an excellent plugin tutorial for those who want to learn how to use the WordPress Transients API.
Author: Benjamin Intal - Gambit Technologies
Version: 1.0
Author URI: http://gambit.ph
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class GambitCacheMenus {

	// We use this to prefix all our transients
    const TRANSIENT_PREFIX = 'cached_menu_';


	/**
	 * Hook into WordPress
	 *
	 * @return	void
	 * @since	1.0
	 */
	function __construct() {
		// Save menus as transients right after it's rendered
		add_filter( 'wp_nav_menu', array( $this, 'saveMenuBeforeDisplay' ), 10, 2 );

		// Check & use transients before it's rendered
		add_filter( 'pre_wp_nav_menu', array( $this, 'useTransientMenu' ), 10, 2 );

		// Force expire our transients on create, save, change menu locations
		add_action( 'wp_update_nav_menu', array( $this, 'deleteTransientMenu' ) );
		add_action( 'wp_create_nav_menu', array( $this, 'deleteTransientMenu' ) );
		add_filter( 'pre_set_theme_mod_nav_menu_locations', array( $this, 'deleteTransientMenu' ) );

		// Extra stuff (plugin links)
		add_filter( 'plugin_row_meta', array( $this, 'pluginLinks' ), 10, 2 );
	}


	/**
	 * Right before we display the menu, check if our transient expired,
	 * if it's gone, save a new one
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function saveMenuBeforeDisplay( $navMenu, $args ) {

		$themeLocations = get_nav_menu_locations();

		if ( empty( $themeLocations[ $args->theme_location ] ) ) {
			return $nav_menu;
		}

		$menu = get_term( $themeLocations[ $args->theme_location ], 'nav_menu' );
		$menuID = $menu->term_id;

		if ( get_transient( self::TRANSIENT_PREFIX . $menuID ) === false ) {
			set_transient( self::TRANSIENT_PREFIX . $menuID, $navMenu, WEEK_IN_SECONDS );
		}

		return $navMenu;
	}


	/**
	 * If we have a saved menu, return it (hence using it instead of recreating it the
	 * normal way)
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function useTransientMenu( $null, $args ) {

		$themeLocations = get_nav_menu_locations();

		if ( empty( $themeLocations[ $args->theme_location ] ) ) {
			return null;
		}

		$menu = get_term( $themeLocations[ $args->theme_location ], 'nav_menu' );
		$menuID = $menu->term_id;

		$transientMenu = get_transient( self::TRANSIENT_PREFIX . $menuID );

		if ( ! empty( $transientMenu ) ) {
			return $transientMenu;
		}
		return null;
	}


	/**
	 * Force delete our transients
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function deleteTransientMenu( $menuID ) {

		if ( ! empty( $menuID ) ) {
			delete_transient( self::TRANSIENT_PREFIX . $menuID );
		}

		return $menuID;
	}


	/****************************************************************
	 * Everything below doesn't have anything to do with caching
	 ****************************************************************/


	/**
	 * Adds plugin links
	 *
	 * @access	public
	 * @param	array $plugin_meta The current array of links
	 * @param	string $plugin_file The plugin file
	 * @return	array The current array of links together with our additions
	 * @since	1.0
	 **/
	public function pluginLinks( $plugin_meta, $plugin_file ) {
		if ( $plugin_file == plugin_basename( __FILE__ ) ) {
			$pluginData = get_plugin_data( __FILE__ );

			$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
				"http://codecanyon.net/user/GambitTech/portfolio?utm_source=" . urlencode( $pluginData['Name'] ) . "&utm_medium=plugin_link",
				__( "Get More Plugins", 'default' )
			);
		}
		return $plugin_meta;
	}
}

new GambitCacheMenus();
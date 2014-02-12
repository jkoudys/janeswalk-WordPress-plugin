<?php
/**
 * Jane's Walk
 *
 * @package   JanesWalk_Admin
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2014 Joshua Koudys, Qaribou
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package JanesWalk_Admin
 * @author  Joshua Koudys <josh@qaribou.com>
 */
class JanesWalk_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = JanesWalk::get_instance();
    $this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
    add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

    // Load up any options and whitelist
    add_action( 'admin_init', array( $this, 'add_plugin_options' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
    add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
    add_filter('query_vars', array($this, 'add_query_vars'));

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
/*  add_action( '@TODO', array( $this, 'action_method_name' ) );
    add_filter( '@TODO', array( $this, 'filter_method_name' ) ); */
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url("{$this->plugin_slug}/admin/assets/css/admin.css"), array(), JanesWalk::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js'), array( 'jquery' ), JanesWalk::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
     */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Jane\'s Walk Settings', $this->plugin_slug ),
			__( 'Jane\'s Walk', $this->plugin_slug ),
			'edit_plugins',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

  }

  /**
	 * Register all the options this plugin will set
	 *
	 * @since    1.0.0
	 */
  public function add_plugin_options() {
    register_setting($this->plugin_slug, 'janeswalk_walkpage');
    register_setting($this->plugin_slug, 'janeswalk_map_height');
    register_setting($this->plugin_slug, 'janeswalk_map_width');

    // Setup the rewrites
    add_action('generate_rewrite_rules', array($this, 'generate_rewrite_rules') );
    add_action('update_option_janeswalk_walkpage', array($this, 'janeswalk_walkpage_option_update'));
  }

  /**
	 * Set the rewrite rules to pass in path as variable
	 *
	 * @since    1.0.0
	 */
  public function generate_rewrite_rules($wp_rewrite) {
    if($janeswalk_walkpage = get_option('janeswalk_walkpage')) { 
      // TODO Confirm if this approach should be used, as add_rewrite_rule() wasn't doing anything
      $wp_rewrite->rules = (array_merge(array('^' . get_page_uri($janeswalk_walkpage) . '/(.*)?' => 'index.php?page_id=' . $janeswalk_walkpage . '&janeswalk_link=http://janeswalk.org/$matches[1]'), $wp_rewrite->rules));
    }
  }

  /**
	 * Refresh the rewrite rules on update
	 *
	 * @since    1.0.0
	 */
  public function janeswalk_walkpage_option_update() {
    flush_rewrite_rules();
  }

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
  public function display_plugin_admin_page() {
    $logo = plugins_url("{$this->plugin_slug}/admin/assets/images/logo.png");
    $pages = get_pages(array('post_status' => 'publish,private'));
    $permalinks_enabled = get_option('permalink_structure') ? true : false;
    $janeswalk_walkpage = get_option('janeswalk_walkpage');
    $janeswalk_map_height = get_option('janeswalk_map_height');
    $janeswalk_map_width = get_option('janeswalk_map_width');
    
    include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

}

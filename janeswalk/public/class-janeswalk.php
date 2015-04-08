<?php
/**
 * Jane's Walk
 *
 * @package   JanesWalk
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2014 Joshua Koudys, Qaribou
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * @package JanesWalk
 * @author  Joshua Koudys <josh@qaribou.com>
 */

require_once 'helpers/theme.php';

class JanesWalk {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	const VERSION = '0.0.3';

	/**
	 * Unique identifier for your plugin.
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $plugin_slug = 'janeswalk';

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected $cache_timeout = 60;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Load plugin text domain
		add_action('init', array( $this, 'load_plugin_textdomain' ));
		add_action('init', array( $this, 'add_rewrite_tags' ));

		// Activate plugin when new blog is added
		add_action('wpmu_new_blog', array( $this, 'activate_new_site' ));

		// Load public-facing style sheet and JavaScript.
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_styles' ));
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));

		add_shortcode('janeswalk', array( $this, 'shortcode_janeswalk' ));
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog. "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 * "Network Activate" action, false if
	 *  WPMU is disabled or plugin is
	 *  activated on an individual blog.
	 */
	public static function activate($network_wide) {
		// TODO: support multi-site activation hooks via REST
	}

	/**
	 * Tags go here, need init to load
	 *
	 * @since 1.0.0
	 */
	public function add_rewrite_tags() {
		add_rewrite_tag('%janeswalk_link%', '([^&/]+)');
	}


	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog. "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 *        "Network Deactivate" action, false if
	 *        WPMU is disabled or plugin is
	 *        deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: support multi-site via RESTful deactivation
	}

	public static function sanitize_links($link) {
		return $link; // TODO: verify this actually links to a real page
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since 1.0.0
	 *
	 * @param int $blog_id ID of the new blog.
	 */
	public function activate_new_site($blog_id) {
		if ( 1 !== did_action('wpmu_new_blog') ) {
			return;
		}
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since 1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since 1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters('plugin_locale', get_locale(), $domain);

		load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
		load_plugin_textdomain($domain, false, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), self::VERSION);
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_slug . '-plugin-script',
			plugins_url('janeswalk/public/assets/js/public.js', ''),
			array(),
			self::VERSION
		);
	}

	/**
	 * Render information fetched from a Jane's Walk server
	 *
	 * @since 1.0.0
	 */
	public function shortcode_janeswalk($atts = array()) {
		global $wp;

		// Silly sanitization needed for WP passing in a blank string instead of a null array
		if ( ! is_array($atts) ) {
			$atts = explode(' ', (string) $atts);
		}

		$link = '';
		if ( array_key_exists('link', $atts) ) {
			$link = $atts['link'];
		} elseif ( array_key_exists('janeswalk_link', $wp->query_vars) ) {
			$link = $wp->query_vars['janeswalk_link'];
		}

		// Get the JSON for this page
		$json = $this->fetch_json($link);
		if ( $json ) {
			try {
				// If you specifically set the 'show', only show those details.
				$show = array_key_exists('show', $atts) ? $atts['show'] : null;
				switch ( $atts['type'] ) {
					case 'map':
						return $this->render_map($link . '?format=kml');
						break;
					case 'city':
						return $this->render_city($json, $show);
						break;
					case 'ward':
						return $this->render_ward($json, $show);
						break;
					case 'walk':
					default:
						return $this->render_walk($json, $show);
						break;
				}
			} catch (Exception $e) {
				echo '<div style="display:none" class="janeswalk-widget-loadfailure">', $e, '</div>';
			}
		} else {
			echo '<div style="display:none" class="janeswalk-widget-loadfailure">', 'JanesWalk cannot load URL ', $link, '?format=json at this time.', var_export($json), '</div>';
			return 'Sorry, we took to long to load this page for you. Please wait a minute or so and try again.';
		}
	}

	/**
	 * Grab the remote JSON for this walk
	 *
	 * @param  string $url The URL on the JanesWalk.org site
	 * @return array
	 */
	private function fetch_json($url) {
		// Check if we've already cached this JSON on this request
		$json = wp_cache_get('janeswalk_' . $url);
		if ( false === $json ) {
			$response = wp_remote_get($url . '?format=json', array( 'timeout' => 45 ));
			if ( is_wp_error((object) $response) ) {
				$json = null;
				echo '<div style="display:none" class="janeswalk-widget-response">', $response->get_error_message(), '</div>';
			} else {
				$json = json_decode($response['body'], true);
				wp_cache_set('janeswalk_' . $url, $json);
				echo '<div style="display:none" class="janeswalk-widget-cachemiss" data-janeswalk-cache="', $url, '"></div>';
			}
		}

		// WP functions use false, but PHP-FIG implies a null response on lookup failure
		return $json ?: null;
	}

	/**
	 * Render the view for a City
	 *
	 * @param  array  $args Variables to extract to the view
	 * @param  string $show Config parameter stating what city info to show
	 * @return string
	 */
	private function render_city(array $args, $show) {
		// Parse the config string
		if ( $show ) {
			$show = explode(' ', $show);
		} else {
			// Default display settings
			$show = array(
				'title',
				'shortdescription',
				'longdescription',
				'cityorganizer',
				'walktitle',
				'walkleaders',
				'walkdate',
				'walkdescription',
			);
		}

		// Option to set the domain, if serving walks outside janeswalk.org
		$walkpage = get_permalink(get_option('janeswalk_walkpage'));

		ob_start();
		if ( in_array('mas', $show) ) {
			include 'views/nyc/city.php';
		} else {
			include 'views/city.php';
		}
		return ob_get_clean();
	}

	/**
	 * Render the view for a Walk
	 *
	 * @param  array  $args Variables to extract to the view
	 * @param  string $show Config parameter stating what walk info to show
	 * @return string
	 */
	private function render_walk(array $args, $show) {
		// Parse config string
		if ( $show ) {
			$show = explode(' ', $show);
		} else {
			// Load walk show defaults
			$show = array(
				'title',
				'leaders',
				'date',
				'description',
				'accessibility',
				'themes',
			);
		}

		// Theme-helper, to map theme shortnames to long names
		// TODO: remove and do this processing server-side
		$th = new JanesWalk_ThemeHelper();

		// TODO: Remove this sloppy 'extract' and pass $args to view instead
		extract($args);
		// Load descriptive versions of walk role shortnames
		$team = array_map(
			function($mem) {
				if ( 'you' === $mem['type'] ) {
					$mem['type'] = ('walk-organizer' === $mem['role']) ? 'organizer' : 'leader';
				}
				switch ( $mem['type'] ) {
					case 'leader':
						$mem['title'] = 'Walk Leader';
						break;
					case 'organizer':
						$mem['title'] = 'Walk Organizer';
						break;
					case 'community':
						$mem['title'] = 'Community Voice';
						break;
					case 'volunteer':
						$mem['title'] = 'Volunteer';
						break;
					default:
						break;
				}
				return $mem;
			},
				$args['team']
			);

		// Build first-names of leaders
		$walk_leaders = array_map(
			function($mem) {
				return trim($mem['name-first'] . ' ' . $mem['name-last']);
			},
			array_filter(
				$team,
				function($mem) {
					return strpos($mem['type'], 'leader') !== false;
				}
			)
		);

		// Accessibility messages
		$accessible = array_filter(
			array_keys($args['checkboxes'] ?: array()),
			function($check) {
				return strpos($check, 'accessible-') === 0;
			}
		);

		// Go through accessibility messages and map their shortnames
		array_walk(
			$accessible,
			function(&$val, $key) use ($th) {
				$val = $th->getName(substr($val, 11));
			}
		);

		// The meeting place is simply the first marker on the map
		$first_marker = $args['map']['markers'][0];
		$meeting = implode(
			', ',
			array_filter(
				array(
					trim($first_marker['title']),
					trim($first_marker['description']),
				)
			)
		);

		// Render view
		// Check for custom views
		if ( in_array('mas', $show) ) {
			ob_start();
			include 'views/nyc/walk.php';
			return ob_get_clean();
		}
		else {
			return include 'views/walk.php';
		}
	}

	/**
	 * Render the view for a Ward
	 *
	 * @param  array  $args Variables to extract to the view
	 * @param  string $show Config parameter stating what ward info to show
	 * @return string
	 */
	private function render_ward(array $args, $show) {
	}

	/**
	 * Render a google-map containing a walk's map
	 * @param $url The URL to this walk's KML
	 * @return null
	 */
	private function render_map($url) {
		// Build a hash to reference this specific map
		$keyName = md5($url);
		$width = get_option('janeswalk_map_width') ?: '425px';
		$height = get_option('janeswalk_map_height') ?: '300px';

		$containerTag = '<div id="' . $keyName . '" style="width:' . $width . ';height:' . $height . '"></div>';
		$scriptTag = '<script type="text/javascript">(function() { function loadMap(url, id) { var myOptions = { zoom: 8, mapTypeId: google.maps.MapTypeId.ROADMAP }; var map = new google.maps.Map(document.getElementById("' . $keyName . '"), myOptions); var walkLayer = new google.maps.KmlLayer({url: url}); walkLayer.setMap(map);} loadMap("' . $url . ',' . $keyName . '"); )()</script>';

		return $containerTag . $scriptTag;
	}
}

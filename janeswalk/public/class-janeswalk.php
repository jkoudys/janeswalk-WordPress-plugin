<?php
/**
 * Jane's Walk
 *
 * @package   janeswalk
 * @author    Joshua Koudys <josh@qaribou.com>
 * @license   GPL-2.0+
 * @link      http://janeswalk.org
 * @copyright 2014 Joshua Koudys, Qaribou
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @package janeswalk 
 * @author    Joshua Koudys <josh@qaribou.com>
 */
class JanesWalk {

  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   0.0.1
   *
   * @var     string
   */
  const VERSION = '0.0.2';

  /**
   *
   * Unique identifier for your plugin.
   *
   *
   * The variable name is used as the text domain when internationalizing strings
   * of text. Its value should match the Text Domain file header in the main
   * plugin file.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_slug = 'janeswalk';

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      int
   */
  protected $cache_timeout = 60;

  /**
   * Initialize the plugin by setting localization and loading public scripts
   * and styles.
   *
   * @since     1.0.0
   */
  private function __construct() {

    // Load plugin text domain
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    // Activate plugin when new blog is added
    add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

    // Load public-facing style sheet and JavaScript.
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    /* Define custom functionality.
     * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
     */
    /*add_action( '@TODO', array( $this, 'action_method_name' ) );
    add_filter( '@TODO', array( $this, 'filter_method_name' ) ); */

    add_shortcode ( 'janeswalk', array($this, 'shortcode_janeswalk') );

  }

  /**
   * Return the plugin slug.
   *
   * @since    1.0.0
   *
   * @return    Plugin slug variable.
   */
  public function get_plugin_slug() {
    return $this->plugin_slug;
  }

  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   *
   * @return    object    A single instance of this class.
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
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public static function activate( $network_wide ) {

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {

      if ( $network_wide  ) {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {

          switch_to_blog( $blog_id );
          self::single_activate();
        }

        restore_current_blog();

      } else {
        self::single_activate();
      }

    } else {
      self::single_activate();
    }

  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
  public static function deactivate( $network_wide ) {

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {

      if ( $network_wide ) {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {

          switch_to_blog( $blog_id );
          self::single_deactivate();

        }

        restore_current_blog();

      } else {
        self::single_deactivate();
      }

    } else {
      self::single_deactivate();
    }

  }

  /**
   * Fired when a new site is activated with a WPMU environment.
   *
   * @since    1.0.0
   *
   * @param    int    $blog_id    ID of the new blog.
   */
  public function activate_new_site( $blog_id ) {

    if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
      return;
    }

    switch_to_blog( $blog_id );
    self::single_activate();
    restore_current_blog();

  }

  /**
   * Get all blog ids of blogs in the current network that are:
   * - not archived
   * - not spam
   * - not deleted
   *
   * @since    1.0.0
   *
   * @return   array|false    The blog ids, false if no matches.
   */
  private static function get_blog_ids() {

    global $wpdb;

    // get an array of blog ids
    $sql = "SELECT blog_id FROM $wpdb->blogs
      WHERE archived = '0' AND spam = '0'
      AND deleted = '0'";

    return $wpdb->get_col( $sql );

  }

  /**
   * Fired for each blog when the plugin is activated.
   *
   * @since    1.0.0
   */
  private static function single_activate() {
    // @TODO: Define activation functionality here
  }

  /**
   * Fired for each blog when the plugin is deactivated.
   *
   * @since    1.0.0
   */
  private static function single_deactivate() {
    // @TODO: Define deactivation functionality here
  }

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    $domain = $this->plugin_slug;
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
    load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

  }

  /**
   * Register and enqueue public-facing style sheet.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
  }

  /**
   * Register and enqueues public-facing JavaScript files.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
  }

  /**
   * NOTE:  Actions are points in the execution of a page or process
   *        lifecycle that WordPress fires.
   *
   *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
   *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
   *
   * @since    1.0.0
   */
  public function action_method_name() {
    // @TODO: Define your action hook callback here
  }

  /**
   * NOTE:  Filters are points of execution in which WordPress modifies data
   *        before saving it or sending it to the browser.
   *
   *        Filters: http://codex.wordpress.org/Plugin_API#Filters
   *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
   *
   * @since    1.0.0
   */
  public function filter_method_name() {
    // @TODO: Define your filter hook callback here
  }

  /**
   * Render information fetched from a Jane's Walk server
   *
   * @since    1.0.0
   */
  public function shortcode_janeswalk($atts) {
    // create the plugin container
    $template = "<div class='janeswalk-widget' data-janeswalk-type='{$atts['type']}'>";

    // Get the JSON for this page
    if($json = $this->fetch_json($atts['link'])) { 
      // If you specifically set the 'show', only show those details. Otherwise, we show a most-common case
      $show = array_key_exists('show', $atts) ? $atts['show'] : null;
      switch( $atts['type'] ) {
      case "map":
        $template .= $this->render_map( $atts['link'] . "?format=kml" );
        break;
      case "city":
        if(isset($show)) {
          $template .= $this->render_city( $json, $show );
        } else {
          $template .= $this->render_city( $json );
        }
        break;
      default:
        if(isset($show)) {
          $template .= $this->render_walk( $json, $show );
        } else {
          $template .= $this->render_walk( $json );
        }
        break;
      }
      $template .= "</div>";
      return $template;
    } else {
      return $template . "JanesWalk: Cannot load URL {$atts['link']}?format=json";
    }
  }

  private function fetch_json($url) {
    if(false === ($json = wp_cache_get('janeswalk_' . $url))) {
      $response = file_get_contents($url . "?format=json");
      $json = json_decode($response, true);
      wp_cache_set('janeswalk_' . $url, $json);
      echo "<div style='display:none' class='janeswalk-widget-cachemiss' data-janeswalk-cache='$url'></div>";
    }
    return $json;
  }

  private function render_city( $json, $show = "title shortdescription longdescription cityorganizer walktitle walkleaders walkdate walkdescription" ) {
    $return = "";
    foreach(explode(" ", $show) as $section) {
      switch($section) {
      case 'title':
        $return .=  ($json['url'] ? "<a href='{$json['url']}'>" : null) . "<h2 class='janeswalk-widget-title'>{$json['title']}</h2>" . ($json['url'] ? "</a>" : null);
        break;
      case 'shortdescription':
        $return .= "<div class='janeswalk-widget-shortdescription'>{$json['short_description']}</div>";
        break;
      case 'longdescription':
        $return .= "<div class='janeswalk-widget-longdescription'>{$json['long_description']}</div>";
        break;
      case 'cityorganizer':
        $return .= "<p class='janeswalk-widget-cityorganizer'>{$json['city_organizer']['first_name']} {$json['city_organizer']['last_name']} </p>";
        break;
      default:
        break;
      }
    }

    if(!empty($json['walks'])) {
      foreach($json['walks'] as $walk) {
        foreach(explode(" ", $show) as $section) {
          switch($section) {
          case "walktitle":
            $return .=  "<h3>" . ($walk['url'] ? "<a href='{$walk['url']}'>":'') . $walk['title'] . ($walk['url'] ?'</a>':'') . "</h3>";
            break;
          case "date":
            $scheduled = $walk->time;
            $slots = (Array)$scheduled->slots; 
            if($scheduled->open) {
              $return .= '<h4 class="available-time"><i class="icon-calendar"></i> Open schedule</h4>';
            } else if(isset($slots[0]['date'])) {
              $return .= "<h4 class='available-time'><i class='icon-calendar'></i> Next available day: <span class='highlight'>{$slots[0]["date"]}</span></h4>";
            }
            break;
          case "leaders":
            $return .= "<h5>$walk->team</h5>";
            break;
          case "description":
            // Load up the return with the data from the walk
            $return .= "<p style='font-size:1.2em' class='janeswalk-widget-shortdescription'>{$walk['short_description']}</p>"
              . "<p style='font-size:1.2em' class='janeswalk-widget-longdescription'>{$walk['long_description']}</p>";
            break; 
          default:
            break;
          }
        }
      }
    }
    return $return;
  }

  private function render_walk( $json, $show = "title leaders date description accessibility themes" ) {
    $th = new JanesWalk_ThemeHelper(); // TODO: remove and do this processing server-side
    $return = "";
    foreach(explode(" ", $show) as $section) {
      switch($section) {
      case "title":
        $return .=  "<h2 class='janeswalk-widget-title'>" . $json['title'] . "</h2>";
        break;
      case "date":
        $scheduled = $json['time'];
        $slots = (Array)$scheduled['slots']; 
        if(array_key_exists('open',$scheduled) && $scheduled['open']) {
          $return .= '<h4 class="available-time"><i class="icon-calendar"></i> Open schedule</h4>';
        } else if(isset($slots[0]['date'])) {
          $return .= "<h4 class='available-time'><i class='icon-calendar'></i> Next available day: <span class='highlight'>{$slots[0]['date']}</span></h4>";
        }
        break;
      case "leaders":
        $teamCount = 0;
        foreach($json['team'] as $mem) {
          if(!empty($mem->{'name-first'})) { $teamCount++; }
        }
        if($teamCount > 0) {
          if($teamCount == 1) {
            $return .= "<h5>Walk Leader: ";
          }
          else {
            $return .= "<h5>Walk Leaders: ";
          }
          foreach($json['team'] as $key=>$mem) {
            $return .= empty($mem->{'name-first'}) ? null : (($key > 0 ? ', ' : null) . "{$mem->{'name-first'}} {$mem->{'name-last'}}");
          }
          $return .= "</h5>";
        }
        break;
      case "themes":
        $return .= "<h4>Themes</h4>";
        $return .= "<ul class='janeswalk-widget-themes'>";
        foreach($json['checkboxes'] as $key=>$theme) {
          if(substr($key, 0, 6) == "theme-") {
            $return .= "<li data-key='$key'>{$th->getName(substr($key,6))}</li>";
          }
        }
        $return .= "</ul>";
        break; 
      case "accessibility":
        $return .= "<h4>Accessibility</h4>";
        $return .= "<ul class='janeswalk-widget-accessibility'>";
        foreach($json['checkboxes'] as $key=>$theme) {
          if(substr($key, 0, 11) == "accessible-") {
            $return .= "<li>{$th->getName(substr($key,11))}</li>";
          }
        }
        $return .= "</ul>";

        break; 
      case "description":
        // Load up the return with the data from the json
        $return .= "<p style='font-size:1.2em' class='janeswalk-widget-shortdescription'>{$json['shortdescription']}</p><p>{$json['longdescription']}</p>";
        break; 
      case "register":
        $eid = $json['eventbrite'];
        if(!empty($eid)) {
          $return .= '<a data-eid="<?=$eid?>" href="<?php echo "http://eventbrite.ca/event/" . $eid ?>" id="register-btn" class="btn btn-primary btn-large">Register For This Walk</a>';
        }
        break; 
      default:
        $return .= "<p>Warning: show '$section' not recognized.</p>";
        break;
      }
    }
    return $return;
  }

  private function render_map ( $url ) {
    return '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=' . urlencode($url) . '&amp;output=embed"></iframe>';
  }
}

class JanesWalk_ThemeHelper {
  private $attributeNameMap;
  private $attributeIconMap;

  public function __construct() {
    $themePath = 'http://janeswalk.org/themes/janeswalk';
    $this->attributeIconMap = array(
      'nature-naturelover' => "<i class='icon-bug'></i>",
      'nature-greenthumb' => "<i class='icon-leaf'></i>",
      'nature-petlover' => "<i class='icon-heart'></i>",
      'urban-suburbanexplorer' => "<img src='$themePath/images/icons-explorer.png' />",
      'urban-architecturalenthusiast' => "<i class='icon-building'></i>",
      'urban-moversandshakers' => "<i class='icon-rocket'></i>",
      'culture-historybuff' => "<img src='$themePath/images/icons-historian.png' />",
      'culture-artist' => "<img src='$themePath/images/icons-artist.png' />",
      'culture-aesthete' => "<i class='icon-picture'></i>",
      'culture-bookworm' => "<i class='icon-book'></i>",
      'culture-foodie' => "<img src='$themePath/images/icons-foodie.png' />",
      'culture-nightowl' => "<i class='icon-moon'></i>",
      'culture-techie' => "<i class='icon-gears'></i>",
      'culture-writer' => "<i class='icon-edit'></i>",
      'civic-activist' => "<img src='$themePath/images/icons-activist.png' />",
      'civic-truecitizen' => "<i class='icon-flag-alt'></i>",
      'civic-goodneighbour' => "<img src='$themePath/images/icon-goodneighbour.png' />",
    );
    $this->attributeNameMap = array(
      'nature-naturelover' => 'The Nature Lover',
      'nature-greenthumb' => 'The Green Thumb',
      'nature-petlover' => 'The Pet Lover',
      'urban-suburbanexplorer' => 'The Suburban Explorer',
      'urban-architecturalenthusiast' => 'The Architectural Enthusiast',
      'urban-moversandshakers' => 'The Movers & Shakers (Transportation)',
      'culture-historybuff' => 'The History Buff',
      'culture-artist' => 'The Artist',
      'culture-aesthete' => 'The Aesthete',
      'culture-bookworm' => 'The Bookworm',
      'culture-foodie' => 'The Foodie',
      'culture-nightowl' => 'The Night Owl',
      'culture-techie' => 'The Techie',
      'culture-writer' => 'The Writer',
      'civic-activist' => 'The Activist',
      'civic-truecitizen' => 'The True Citizen',
      'civic-goodneighbour' => 'The Good Neighbour',
      // Accessibility
      'familyfriendly' => 'Family friendly',
      'wheelchair' => 'Wheelchair accessible',
      'dogs' => 'Dogs welcome',
      'strollers' => 'Strollers welcome',
      'bicycles' => 'Bicycles welcome',
      'steephills' => 'Steep hills',
      'uneven' => 'Wear sensible shoes (uneven terrain)',
      'busy' => 'Busy sidewalks',
      'bicyclesonly' => 'Bicycles only',
    );
  }

  public function getName($handle) {
    return $this->attributeNameMap[(string)$handle];
  }
  public function getIcon($handle) {
    return $this->attributeIconMap[(string)$handle];
  }
}

<?php
defined('ABSPATH') or die();

define( 'BASEMENT_MAP_TEXTDOMAIN', 'basement_map' );
	
class Basement_Map {
	
	private static $instance = null;
	private static $url = null;

	public function __construct() {
		add_filter( 'basement_shortcodes_config', array( &$this, 'add_shortcodes' ) );
		add_filter( 'basement_shortcodes_groups_config', array( &$this, 'add_shortcodes_groups' ) );
	}

	public static function init() {
		self::instance();
		Basement_Asset::add_footer_script(
			BASEMENT_MAP_TEXTDOMAIN . '_google_js', 
			'https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false'
		);
		Basement_Asset::add_footer_script(
			BASEMENT_BOOTSTRAP_TEXTDOMAIN . '_js', 
			self::url() . '/assets/javascript/production.min.js',
			array( BASEMENT_MAP_TEXTDOMAIN . '_google_js' )
		);
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new Basement_Map();
		}
		return self::$instance;
	}

	public function no_basement_notice() { ?>
		<div class="error">
			<p><?php _e( 'Your theme doesn\'t support Basement Framework. Basement Map plugin will not be available.', BASEMENT_MAP_TEXTDOMAIN ); ?></p>
		</div>
	<?php }

	public function add_shortcodes( $config ) {
		$config[ 'map' ] = array(
			'group' => BASEMENT_MAP_TEXTDOMAIN,
			'class' => 'Basement_Map_Google',
			'title' => __( 'Google Map', BASEMENT_MAP_TEXTDOMAIN ),
			'path' => dirname( __FILE__ ) . '/../shortcodes/google.php'
		);
		return $config;
	} 

	public function add_shortcodes_groups( $config ) {
		$config[ BASEMENT_MAP_TEXTDOMAIN ] = __( 'Map', BASEMENT_MAP_TEXTDOMAIN );
		return $config;
	} 

	public static function url() {
		if ( null === self::$url ) {
			self::$url = Basement_Url::of_file( realpath( dirname( __FILE__ ) . '/../../' ) );
		}
		return self::$url;
	}

}

Basement_Map::init();

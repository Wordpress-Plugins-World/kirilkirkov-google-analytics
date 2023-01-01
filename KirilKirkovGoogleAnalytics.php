<?php
/*
Plugin Name: GA - Easy Google Analytics Integration
Description: This plugin integrates google analytics code to your theme.
Version: 1.0
Author: Kiril Kirkov
Author URI: https://github.com/kirilkirkov/
License: GPLv2 or later
Text Domain: kirilkirkov-google-analytics
*/

if(!class_exists('\KirilKirkov\GoogleAnalytics\Config')) {
	require 'Includes/Admin/Config/Config.php';
}

use KirilKirkov\GoogleAnalytics\Config;

if(!class_exists('KirilKirkovGoogleAnalytics')) {
	class KirilKirkovGoogleAnalytics 
	{
		private static $instance;

		private function __construct()
		{
			$this->init(); // Sets up all the actions and filters
		}

		public static function getInstance()
		{
			if ( !self::$instance ) {
				self::$instance = new KirilKirkovGoogleAnalytics();
			}

			return self::$instance;
		}

		private function init()
		{
			// Register the options with the settings API
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// Public init
			add_action( 'init', array( $this, 'public_init' ) );

			// Add the menu page
			add_action( 'admin_menu', array( $this, 'setup_admin' ) );

			// admin scripts
			add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));
		}

		public function public_init()
		{
			// add tracking code to footer or head
			$load_html_part = get_option( Config::INPUTS_PREFIX.'load_html_part');
			if(!$load_html_part) {
				return;
			}
			if( $load_html_part === 'footer') {
				add_action( 'wp_footer', array($this, 'add_analytics'), 10 );
			} else if( $load_html_part === 'head' ) {
				add_action( 'wp_head', array($this, 'add_analytics'), 10 );
			}
		}

		/**
		 * Load assets for administration
		 */
		public function load_admin_assets($hook)
		{
			$current_screen = get_current_screen();
			if (strpos($current_screen->base, Config::SETTINGS_GET_PARAM) === false) {
				return;
			}
			wp_enqueue_style(Config::SCRIPTS_PREFIX.'boot_core_css', plugins_url('Includes/Admin/Assets/core.css', __FILE__ ));
			wp_enqueue_style(Config::SCRIPTS_PREFIX.'boot_admin_css', plugins_url('Includes/Admin/Assets/admin.css', __FILE__ ));
			wp_enqueue_script(Config::SCRIPTS_PREFIX.'boot_admin_js', plugins_url('Includes/Admin/Assets/admin.js', __FILE__ ), array(), false, true);
		}

		/**
		 * Add GA code if has in settings
		 * escape entered text to prevent errors
		 */
		public function add_analytics()
		{
			$google_analytics_code = get_option( Config::INPUTS_PREFIX.'google_analytics_code' );
			if(!$google_analytics_code) {
				return;
			}
			// clean code before insert to main html, leave only letters, numbers and -
			$google_analytics_code = preg_replace("/[^a-zA-Z0-9\-]+/", "", $google_analytics_code);
			$google_analytics_code = trim($google_analytics_code);

			if($google_analytics_code !== '' 
				&& !$this->is_excluded() 
				&& !$this->is_disabled_for_ip() 
				&& !$this->is_disabled_by_role()) {
			?>
				<!-- Global site tag (gtag.js) - Google Analytics -->
				<script async src="https://www.googletagmanager.com/gtag/js?id=<?php esc_attr_e($google_analytics_code); ?>"></script>
				<script>
					window.dataLayer = window.dataLayer || [];
					function gtag(){dataLayer.push(arguments);}
					gtag('js', new Date());

					gtag('config', '<?php esc_html_e($google_analytics_code); ?>');
				</script>
			<?php 
			}
		}

		/**
		 * Disabled for ip addresses func
		 * IPv4 supported
		 */
		private function is_disabled_for_ip()
		{
			$ip = $this->get_visitor_ip();

			// check is in disabled
			$disabled_ips = get_option(Config::INPUTS_PREFIX.'disabled_ips');
			if($disabled_ips) {
				$disabled_ips = explode(',', trim($disabled_ips));
				if(count($disabled_ips)) {
					$disabled_ips = array_map(function($v) {
						if(filter_var(trim($v), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
							return trim($v);
						}
					}, $disabled_ips);

					return in_array($ip, $disabled_ips);
				}
			}

			return false;
		}

		/**
		 * Get Visitor Ip Address (ipV4)
		 */
		private function get_visitor_ip()
		{
			if (!empty( $_SERVER['HTTP_CLIENT_IP'])) {
				// check ip from share internet
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty( $_SERVER['HTTP_X_FORWARDED_FOR'])) {
				// to check ip is pass from proxy
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}

			return $ip;
		}

		/**
		 * @param string $page_id - mostly called from admin settings in loop for checked pages
		 */
		private function is_excluded($page_id = null)
		{	
			if($page_id === null) {
				$page_id = get_queried_object_id();
			}
			
			$exclude_pages = get_option(Config::INPUTS_PREFIX.'exclude_pages');

			if(is_array($exclude_pages)
				&& count($exclude_pages)
				&& in_array($page_id, $exclude_pages)) {
				return true;
			}
			return false;
		}

		/**
		 * Is disabled by role/permission
		 */
		private function is_disabled_by_role()
		{
			if(!is_user_logged_in()) {
				return false;
			}

			$logged_user = wp_get_current_user();
			if(!property_exists($logged_user, 'roles')) {
				return false;
			}
			
			// If wants to disable track only for specific roles
			$track_roles = get_option(Config::INPUTS_PREFIX.'track_roles');

			if(is_array($track_roles) && count($track_roles)) {
				$user_roles = (array)$logged_user->roles; // obtaining the roles
				foreach($user_roles as $ur) {
					if(in_array($ur, $track_roles)) {
						return true;
					}
				}
			}
			return false;
		}

		public function admin_init()
		{
			if (!is_admin()) {
				wp_die( esc_html__( 'This code is for admin area only', 'kirilkirkov-login-defender' ) );
			}
			
			// init group inputs
			foreach(Config::get_groups_input_fieds() as $group => $inputs) {
				foreach($inputs as $input) {
					register_setting($group, Config::INPUTS_PREFIX.$input);
				}
			}
		}

		public function setup_admin()
		{
			// add settings page
			add_options_page( esc_html__( 'Google Analytics Plugin', 'kirilkirkov-google-analytics' ), esc_html__( 'Google Analytics', 'kirilkirkov-google-analytics' ), 'administrator', Config::SETTINGS_GET_PARAM, array( $this, 'admin_page' ) );		
		}

		/**
		 * Admin settings page
		 */
		public function admin_page() 
		{
			$Config = Config::class;

			ob_start();
			require 'Includes/Admin/SettingsForm.php';
			echo ob_get_clean();
		}
	}

	$ga = KirilKirkovGoogleAnalytics::getInstance();
}
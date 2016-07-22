
<?php

/**
*
*/
if ( get_option( 'agenda_pro_activated' ) != 'Activated' ) {
    add_action( 'admin_notices', 'API_License::agenda_inactive_notice' );
}

class API_License
{
 	private static $instance;
 
	    public static function instance() {
	        if(self::$instance == null) {
	            self::$instance = new self();
	        }
	        return self::$instance;
	    }

	    public $plugin_url;

		/**
		 * @var string
		 * used to defined localization for translation, but a string literal is preferred
		 *
		 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/issues/59
		 * http://markjaquith.wordpress.com/2011/10/06/translating-wordpress-plugins-and-themes-dont-get-clever/
		 * http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
		 */
		public $text_domain = 'Agenda-Pro';

		/**
		 * Data defaults
		 * @var mixed
		 */
		private $ap_software_product_id;

		public $ap_data_key;
		public $ap_api_key;
		public $ap_api_test_mode;
		public $ap_activation_email;
		public $ap_product_id_key;
		public $ap_instance_key;
		public $ap_deactivate_checkbox_key;
		public $ap_activated_key;

		public $ap_deactivate_checkbox;
		public $ap_activation_tab_key;
		public $ap_deactivation_tab_key;
		public $ap_settings_menu_title;
		public $ap_settings_title;
		public $ap_menu_tab_activation_title;
		public $ap_menu_tab_deactivation_title;

		public $ap_options;
		public $ap_plugin_name;
		public $ap_product_id;
		public $ap_renew_license_url;
		public $ap_instance_id;
		public $ap_domain;
		public $ap_software_version;
		public $ap_plugin_or_theme;

		public $ap_update_version;

		/**
		 * Used to send any extra information.
		 * @var mixed array, object, string, etc.
		 */
		public $ap_extra;

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.2
		 */
		public function __clone() {}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.2
		 */
		public function __wakeup() {}

		function __construct(){

			register_activation_hook( SPEAKER_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'activation' ) );

	        if ( is_admin() ) {

	            // Check for external connection blocking
	            add_action( 'admin_notices', array( $this, 'check_external_blocking' ) );

	            /**
	             * Software Product ID is the product title string
	             * This value must be unique, and it must match the API tab for the product in WooCommerce
	             */
	            $this->ap_software_product_id = 'Agenda Pro';

	            /**
	             * Set all data defaults here
	             */
	            $this->ap_data_key                 = 'agenda_pro';
	            $this->ap_api_key                  = 'api_key';
	            $this->ap_api_test_mode            = 'api_test_mode';
	            $this->ap_activation_email         = 'activation_email';
	            $this->ap_product_id_key           = 'agenda_pro_product_id';
	            $this->ap_instance_key             = 'agenda_pro_instance';
	            $this->ap_deactivate_checkbox_key  = 'agenda_pro_deactivate_checkbox';
	            $this->ap_activated_key            = 'agenda_pro_activated';

	            /**
	             * Set all admin menu data
	             */
	            $this->ap_deactivate_checkbox          = 'agenda_pro_checkbox';
	            $this->ap_deactivate_posttype          = 'agenda';
	            $this->ap_activation_tab_key           = 'config_page';
	            $this->ap_deactivation_tab_key         = 'agenda_pro_deactivation';
	            $this->ap_settings_menu_title          = 'Agenda Pro';
	            $this->ap_settings_title               = 'Agenda Pro ';
	            $this->ap_menu_tab_activation_title    = __( 'License Activation', 'agenda_pro' );
	            $this->ap_menu_tab_deactivation_title  = __( 'License Deactivation', 'agenda_pro' );

	            /**
	             * Set all software update data here
	             */
	            $this->asp_options              = get_option( $this->ap_data_key );
	            $this->asp_plugin_name          = untrailingslashit( plugin_basename( SPEAKER_PLUGIN_ACTIVE_FILE_NAME ) ); // same as plugin slug. if a theme use a theme name like 'twentyeleven'
	            $this->ap_product_id           = get_option( $this->ap_product_id_key ); // Software Title
	            $this->ap_renew_license_url    = 'http://localhost/toddlahman/my-account'; // URL to renew a license. Trailing slash in the upgrade_url is required.

	            $this->ap_instance_id          = get_option( $this->ap_instance_key ); // Instance ID (unique to each blog activation)
	            /*var_dump($this->sp_instance_id);
	            exit();*/
	            /**
	             * Some web hosts have security policies that block the : (colon) and // (slashes) in http://,
	             * so only the host portion of the URL can be sent. For example the host portion might be
	             * www.example.com or example.com. http://www.example.com includes the scheme http,
	             * and the host www.example.com.
	             * Sending only the host also eliminates issues when a client site changes from http to https,
	             * but their activation still uses the original scheme.
	             * To send only the host, use a line like the one below:
	             *
	             * $this->ame_domain = str_ireplace( array( 'http://', 'https://' ), '', home_url() ); // blog domain name
	             */

	            $this->ap_domain               = str_ireplace( array( 'http://', 'https://' ), '', home_url() ); // blog domain name
	            $this->ap_software_version     = $this->version; // The software version
	            $this->ap_plugin_or_theme      = 'plugin'; // 'theme' or 'plugin'

	            // Performs activations and deactivations of API License Keys
	            //require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wc-key-api.php' );

	            // Checks for software updatess
	            //require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wc-plugin-update.php' );

	            // Admin menu with the license key and license email form
	            //require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wc-api-manager-menu.php' );

	            $options = get_option( $this->ap_data_key );

	            /**
	             * Check for software updates
	             */
	            /*if ( ! empty( $options ) && $options !== false ) {

	                $this->update_check(
	                    $this->upgrade_url,
	                    $this->sp_plugin_name,
	                    $this->sp_product_id,
	                    $this->sp_options[$this->sp_api_key],
	                    $this->sp_options[$this->sp_activation_email],
	                    $this->sp_renew_license_url,
	                    $this->sp_instance_id,
	                    $this->sp_domain,
	                    $this->sp_software_version,
	                    $this->sp_plugin_or_theme,
	                    $this->text_domain
	                    );

	            }*/
	        }
	        register_deactivation_hook( AGENDA_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'uninstall' ) );
	    }

	    public function key() {
			return API_Licensekey::instance();
		}
		
	    public function activation() {
        $global_options = array(
            $this->ap_api_key              => '',
            $this->ap_api_test_mode        => '',
            $this->ap_activation_email     => '',
                    );

        update_option( $this->ap_data_key, $global_options );

        $single_options = array(
            $this->ap_product_id_key           => $this->ap_software_product_id,
            $this->ap_instance_key             => wp_generate_password( 12, false ),
            $this->ap_deactivate_checkbox_key  => 'on',
            $this->ap_activated_key            => 'Deactivated',
            );

        foreach ( $single_options as $key => $value ) {
            update_option( $key, $value );
        }

        $curr_ver = get_option( $this->agenda_pro_version_name );

        // checks if the current plugin version is lower than the version being installed
        if ( version_compare( $this->version, $curr_ver, '>' ) ) {
            // update the version
            update_option( $this->agenda_pro_version_name, $this->version );
        }

    }

    public function uninstall() {
        global $blog_id;

        $this->license_key_deactivation();

        // Remove options
        if ( is_multisite() ) {

            switch_to_blog( $blog_id );

            foreach ( array(
                    $this->ap_data_key,
                    $this->ap_product_id_key,
                    $this->ap_instance_key,
                    $this->ap_deactivate_checkbox_key,
                    $this->ap_activated_key,
                    ) as $option) {

                    delete_option( $option );

                    }

            restore_current_blog();

        } else {

            foreach ( array(
                    $this->ap_data_key,
                    $this->ap_product_id_key,
                    $this->ap_instance_key,
                    $this->ap_deactivate_checkbox_key,
                    $this->ap_activated_key
                    ) as $option) {

                    delete_option( $option );

                    }

        }

    }
    /**
     * Deactivates the license on the API server
     * @return void
     */
    public function license_key_deactivation() {

        $activation_status = get_option( $this->ap_activated_key );

        $api_email = $this->ap_options[$this->ap_activation_email];
        $api_key = $this->ap_options[$this->ap_api_key];
        $api_test_mode = $this->ap_options[$this->ap_api_test_mode];

        $args = array(
            'email' => $api_email,
            'licence_key' => $api_key,
            'test_mode' => $api_test_mode,
            );

        if ( $activation_status == 'Activated' && $api_key != '' && $api_email != '' ) {
            $this->key()->deactivate( $args ); // reset license key activation
        }
    }

    public static function agenda_inactive_notice() { ?>
        <?php if ( ! current_user_can( 'manage_options' ) ) return; ?>
        <?php if ( isset( $_GET['page'] ) && 'config_page' == $_GET['page'] ) return; ?>
        <div id="message" class="error">
            <p><?php printf( __( 'The Agenda Pro API License Key has not been activated, so the plugin is inactive! %sClick here%s to activate the license key and the plugin.', 'agenda_pro' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=agenda&page=config_page' ) ) . '">', '</a>' ); ?></p>
        </div>
        <?php
    }

    /**
     * Check for external blocking contstant
     * @return string
     */
    public function check_external_blocking() {
        // show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant
        if( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {

            // check if our API endpoint is in the allowed hosts
            $host = parse_url( $this->upgrade_url, PHP_URL_HOST );

            if( ! defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
                ?>
                <div class="error">
                    <p><?php printf( __( '<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get %s updates. Please add %s to %s.', 'speaker_pro' ), $this->sp_software_product_id, '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>'); ?></p>
                </div>
                <?php
            }

        }
    }
		

	// API Key URL
	public function create_software_api_url( $args ) {

		$api_url = add_query_arg( 'wc-api', 'am-software-api', AP()->upgrade_url );

		return $api_url . '&' . http_build_query( $args );
	}

	public function activate( $args ) {

		$defaults = array(
			'request' 			=> 'activation',
			'product_id' 		=> AP()->sp_product_id,
			'instance' 			=> AP()->sp_instance_id,
			'platform' 			=> AP()->sp_domain,
			'software_version' 	=> AP()->sp_software_version
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

		$request = wp_safe_remote_get( $target_url );

		// $request = wp_remote_post( AME()->upgrade_url . 'wc-api/am-software-api/', array( 'body' => $args ) );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	public function deactivate( $args ) {

		$defaults = array(
			'request' 		=> 'deactivation',
			'product_id' 	=> AP()->sp_product_id,
			'instance' 		=> AP()->sp_instance_id,
			'platform' 		=> AP()->sp_domain
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

		$request = wp_safe_remote_get( $target_url );

		// $request = wp_remote_post( AME()->upgrade_url . 'wc-api/am-software-api/', array( 'body' => $args ) );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	/**
	 * Checks if the software is activated or deactivated
	 * @param  array $args
	 * @return array
	 */
	public function status( $args ) {

		$defaults = array(
			'request' 		=> 'status',
			'product_id' 	=> AP()->ap_product_id,
			'instance' 		=> AP()->ap_instance_id,
			'platform' 		=> AP()->ap_domain
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

		$request = wp_safe_remote_get( $target_url );

		// $request = wp_remote_post( AME()->upgrade_url . 'wc-api/am-software-api/', array( 'body' => $args ) );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}



}
function AP() {
        return API_License::instance();
    }

    // Initialize the class instance only once
    AP();
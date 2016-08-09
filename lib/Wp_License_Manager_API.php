<?php
//if ( ! class_exists( 'Wp_License_Manager_Client' ) ) {
//require ('init.php');
 
    class Wp_License_Manager_API  {  
    	/**
		 * The API endpoint. Configured through the class's constructor.
		 *
		 * @var String  The API endpoint.
		 */
		private $api_endpoint;
		 
		/**
		 * The product id (slug) used for this product on the License Manager site.
		 * Configured through the class's constructor.
		 *
		 * @var int     The product id of the related product in the license manager.
		 */
		private $product_id;
		 
		/**
		 * The name of the product using this class. Configured in the class's constructor.
		 *
		 * @var int     The name of the product (plugin / theme) using this class.
		 */
		private $product_name;
		 
		/**
		 * The type of the installation in which this class is being used.
		 *
		 * @var string  'theme' or 'plugin'.
		 */
		private $type;
		 
		/**
		 * The text domain of the plugin or theme using this class.
		 * Populated in the class's constructor.
		 *
		 * @var String  The text domain of the plugin / theme.
		 */
		private $text_domain;
		 
		/**
		 * @var string  The absolute path to the plugin's main file. Only applicable when using the
		 *              class with a plugin.
		 */
		private $plugin_file;
		/**
		 * Initializes the license manager client.
		 *
		 * @param $product_id   string  The text id (slug) of the product on the license manager site
		 * @param $product_name string  The name of the product, used for menus
		 * @param $text_domain  string  Theme / plugin text domain, used for localizing the settings screens.
		 * @param $api_url      string  The URL to the license manager API (your license server)
		 * @param $type         string  The type of project this class is being used in ('theme' or 'plugin')
		 * @param $plugin_file  string  The full path to the plugin's main file (only for plugins)
		 */
		public function __construct( $product_id='agenda', $product_name='Symposium Agenda Pro', $text_domain='Easily build and display agendas and timetables for meetings, conferences, workshops, training days and events', $api_url='http://www.blackandwhitedigital.eu?wc-api=am-software-api', $plugin_file='',$type = 'plugin' ) {
		        // Store setup data

		        $this->product_id = $product_id;
		        $this->product_name = $product_name;
		        $this->text_domain = $text_domain;
		        $this->api_endpoint = $api_url;
		        $this->type = $type;
		        $this->plugin_file = $plugin_file;

         		add_action( 'admin_menu', array( $this, 'add_license_settings_page' ) );
        		add_action( 'admin_init', array( $this, 'add_license_settings_fields' ) );
        		add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );
        		add_action('admin_init', array( $this,'edd_sample_activate_license'));
		       
		    }

		/**
     * Creates the settings items for entering license information (email + license key).
     */
    public function add_license_settings_page() {
        $title = sprintf( __( '%s License', $this->text_domain ), $this->product_name );

            
        add_options_page(
            $title,
            $title,
            'read',
            $this->get_settings_page_slug(),
            array( $this, 'render_licenses_menu' )
        );
    }
     
    /**
     * Creates the settings fields needed for the license settings menu.
     */
    public function add_license_settings_fields() {
        $settings_group_id = $this->product_id . '-license-settings-group';
        $settings_section_id = $this->product_id . '-license-settings-section';
     
        register_setting( $settings_group_id, $this->get_settings_field_name() );
     
        add_settings_section(
            $settings_section_id,
            __( 'License', $this->text_domain ),
            array( $this, 'render_settings_section' ),
            $settings_group_id
        );
     
        add_settings_field(
            $this->product_id . '-license-email',
            __( 'License e-mail address', $this->text_domain ),
            array( $this, 'render_email_settings_field' ),
            $settings_group_id,
            $settings_section_id
        );
     
        add_settings_field(
            $this->product_id . '-license-key',
            __( 'License key', $this->text_domain ),
            array( $this, 'render_license_key_settings_field' ),
            $settings_group_id,
            $settings_section_id
        );
    }

    /**
     * Renders the description for the settings section.
     */
    public function render_settings_section() {
        _e( 'Insert your license information to enable updates.', $this->text_domain);
    }
     
    /**
     * Renders the settings page for entering license information.
     */
    public function render_licenses_menu($text_domain) {
    	//$this->text_domain= $text_domain
        $title = sprintf( __( '%s License', $this->text_domain ), $this->product_name );
        $settings_group_id = $this->product_id . '-license-settings-group';
     
        ?>
            <div class="wrap">
                <form action='options.php' method='post'>
     
                    <h2><?php echo $title; ?></h2>
     
                    <?php
                        settings_fields( $settings_group_id );
                        do_settings_sections( $settings_group_id );
                        submit_button();

                    ?>
     
                </form>
            </div>
        <?php
        //if ( !empty( $options['email'] ) || !empty( $options['license_key'] ) ) {
        $this->get_license_info();
    	//}
    }
     
    /**
     * Renders the email settings field on the license settings page.
     */
    public function render_email_settings_field() {
        $settings_field_name = $this->get_settings_field_name();
        $options = get_option( $settings_field_name );
        ?>
            <input type='text' name='<?php echo $settings_field_name; ?>[email]'
               value='<?php echo $options['email']; ?>' class='regular-text'>
        <?php
    }
     
    /**
     * Renders the license key settings field on the license settings page.
     */
    public function render_license_key_settings_field() {
        $settings_field_name = $this->get_settings_field_name();
        $options = get_option( $settings_field_name );
        ?>
            <input type='text' name='<?php echo $settings_field_name; ?>[license_key]'
               value='<?php echo $options['license_key']; ?>' class='regular-text'>
        <?php
    }

		 /**
		 * @return string   The name of the settings field storing all license manager settings.
		 */
		protected function get_settings_field_name() {
		    return $this->product_id . '-license-settings';
		}
		 
		/**
		 * @return string   The slug id of the licenses settings page.
		 */
		protected function get_settings_page_slug() {
		    return $this->product_id . '-licenses';
		}


    /**
		 * If the license has not been configured properly, display an admin notice.
		 */
		public function show_admin_notices() {
			/*echo($product_id);
			exit();*/
		    $options = get_option( $this->get_settings_field_name() );
		 
		    if ( !$options || ! isset( $options['email'] ) || ! isset( $options['license_key'] ) ||
		        $options['email'] == '' || $options['license_key'] == '' ) {
		 
		        $msg = __( 'The Symposium Agenda Pro API License Key has not been activated, so the plugin is inactive! Click here to activate the license key and the plugin.', $this->text_domain );
		        $msg = sprintf( $msg, $this->product_name );
		        ?>
		            <div class="update-nag">
		                <p>
		                    <?php echo $msg; ?>
		                </p>
		 
		                <p>
		                    <a href="<?php echo admin_url( 'options-general.php?page=' . $this->get_settings_page_slug() ); ?>">
		                        <?php _e( 'Complete the setup now.', $this->text_domain ); ?>
		                    </a>
		                </p>
		            </div>
		        <?php
		    }
		}

            

		//
		// API HELPER FUNCTIONS
		//
		 
		/**
		 * Makes a call to the WP License Manager API.
		 *
		 * @param $method   String  The API action to invoke on the license manager site
		 * @param $params   array   The parameters for the API call
		 * @return          array   The API response
		 */


		/*private function call_api( $action, $params ) {
		    $url = $this->api_endpoint . '/' . $action;
		 
		    // Append parameters for GET request
		    $url .= '?' . http_build_query( $params );
		 
		    // Send the request
		    $response = wp_remote_get( $url );
		    if ( is_wp_error( $response ) ) {
		        return false;
		    }
		         
		    $response_body = wp_remote_retrieve_body( $response );
		    $result = json_decode( $response_body );
		     
		    return $result;
		}*/

		/**
		 * Checks the API response to see if there was an error.
		 *
		 * @param $response mixed|object    The API response to verify
		 * @return bool     True if there was an error. Otherwise false.
		 */
		/*private function is_api_error( $response ) {
		    if ( $response === false ) {
		        return true;
		    }
		 
		    if ( ! is_object( $response ) ) {
		        return true;
		    }
		 
		    if ( isset( $response->error ) ) {
		        return true;
		    }
		 
		    return false;
		}*/

		
		/**
         * A function for the WordPress "plugins_api" filter. Checks if
         * the user is requesting information about the current plugin and returns
         * its details if needed.
         *
         * This function is called before the Plugins API checks
         * for plugin information on WordPress.org.
         *
         * @param $res      bool|object The result object, or false (= default value).
         * @param $action   string      The Plugins API action. We're interested in 'plugin_information'.
         * @param $args     array       The Plugins API parameters.
         *
         * @return object   The API response.
         */
        public function plugins_api_handler( $res, $action, $args ) {
        	
            if ( $action == 'plugin_information' ) {
                // If the request is for this plugin, respond to it
                if ( isset( $args->slug ) && $args->slug == plugin_basename( $this->plugin_file ) ) {
                    $info = $this->get_license_info();
                    $res = (object) array(
                        'name'          => isset( $info->name ) ? $info->name : '',
                        'version'       => $info->version,
                        'slug'          => $args->slug,
                        'download_link' => $info->package_url,
                        'tested'        => isset( $info->tested ) ? $info->tested : '',
                        'requires'      => isset( $info->requires ) ? $info->requires : '',
                        'last_updated'  => isset( $info->last_updated ) ? $info->last_updated : '',
                        'homepage'      => isset( $info->description_url ) ? $info->description_url : '',
                        'sections'      => array(
                            'description' => $info->description,
                        ),
                        'banners'       => array(
                            'low'  => isset( $info->banner_low ) ? $info->banner_low : '',
                            'high' => isset( $info->banner_high ) ? $info->banner_high : ''
                        ),
                        'external'      => true
                    );
                    // Add change log tab if the server sent it
                    if ( isset( $info->changelog ) ) {
                        $res['sections']['changelog'] = $info->changelog;
                    }
                    return $res;
                }
            }
            // Not our request, let WordPress handle this.
            return false;
        }
        //
        // HELPER FUNCTIONS FOR ACCESSING PROPERTIES
        //
        /**
         * @return string   The name of the settings field storing all license manager settings.
         */
        /*protected function get_settings_field_name() {
            return $this->product_id . '-license-settings';
        }
        *
         * @return string   The slug id of the licenses settings page.
         
        protected function get_settings_page_slug() {
            return $this->product_id . '-licenses';
        }*/
        /**
         * A shorthand function for checking if we are in a theme or a plugin.
         *
         * @return bool True if this is a theme. False if a plugin.
         */
        private function is_theme() {
            return $this->type == 'theme';
        }
        /**
         * @return string   The theme / plugin version of the local installation.
         */
        private function get_local_version() {
            if ( $this->is_theme() ) {
                $theme_data = wp_get_theme();
                return $theme_data->Version;
            } else {

                $plugin_data = get_plugin_data( $this->plugin_file, false );

                return $plugin_data['Version'];
            }
        }
        //
        // API HELPER FUNCTIONS
        //
        /**
         * Makes a call to the WP License Manager API.
         *
         * @param $action   String  The API method to invoke on the license manager site
         * @param $params   array   The parameters for the API call
         *
         * @return          array   The API response
         */
        public function call_api( $action, $params, $dir ) {
            $array = [
            'request' => 'activation',
            'email' => $params['email'],
            'licence_key' => $params['license_key'],
            'product_id' => 'Symposium Agenda Pro',
            'platform' => home_url(),
            'instance' => uniqid(),
            'software_version' => '0.1'

        ];

        $url = 'http://www.blackandwhitedigital.eu?wc-api=am-software-api&email=' . urlencode($array['email']) . '&licence_key=' . urlencode($array['licence_key']) . '&request=' . urlencode($array['request']) . '&product_id=' . urlencode($array['product_id']) . '&instance=' . urlencode($array['instance']) . '&platform=' . urlencode($array['platform']) . '&software_version=' . urlencode($array['software_version']);

         $response = wp_remote_get( $url  );

            if ( is_wp_error( $response ) ) {
                return false;
            }
            $response_body = wp_remote_retrieve_body( $response );
            $result = json_decode( $response_body,true );

            if (!isset($result['error'])){
              
                if (!file_exists($dir)) return;

                $classes = array();

                foreach (scandir($dir) as $item) {
                    if( preg_match( "/.php$/i" , $item ) ) {
                        require_once( $dir . $item );
                        $className = str_replace( ".php", "", $item );
                        $classes[] = new $className;
                    }
                }

                if($classes){
                    foreach( $classes as $class )
                        $this->objects[] = $class;
                    //unset($this->objects[3]);
                   
                }
       
                
            }else{
                $msg = __( 'The Symposium Agenda Pro API License Key and Email is invalid, so the plugin is inactive! Please enter the correct API License Key and Email.');
                $msg = sprintf( $msg, 'Symposium Agenda Pro' );
                ?>
                
                <div class="update-nag" style="float:right;margin-right: 10%;
    width: 73.5%;">
               
                        <p>
                            <?php echo $msg; ?>
                        </p>
                        <p>
                            <a href="<?php echo admin_url( 'options-general.php?page=' . $this->get_settings_page_slug() ); ?>">
                                <?php _e( 'Complete the setup now.', $this->text_domain ); ?>
                            </a>
                        </p>
        
                </div>
                </div>
                <?php
                //echo $msg;
            }
        ///echo $rev;
           /* $url = $this->api_endpoint;

            // Append parameters for GET request
            $url .= '&' . http_build_query( $params );
           
            // Send the request
            $response = wp_remote_get( $url  );

            if ( is_wp_error( $response ) ) {
                return false;
            }

            $response_body = wp_remote_retrieve_body( $response );
            $result = json_decode( $response_body );
            //$tinfo= new Wp_License_Manager_Public('WooCommerce API Manager','1.4.6.3');
            //$tinfo->handle_request($action, $params);
            $this->is_api_error($response);
            var_dump($result);
            exit();*/
            //return $response;

            
        }
        /**
         * Checks the API response to see if there was an error.
         *
         * @param $response mixed|object    The API response to verify
         *
         * @return bool     True if there was an error. Otherwise false.
         */
        private function is_api_error( $response ) {
        	
            if ( $response === false ) {
            
                return true;
            }
            if ( ! is_object( $response ) ) {
                
                return true;
            }
            if ( isset( $response->error ) ) {
                return true;
            }
            return false;
        }
        /**
		 * Calls the License Manager API to get the license information for the
		 * current product.
		 *
		 * @return object|bool   The product data, or false if API call fails.
		 */
		public function get_license_info(){

		    $options = get_option( $this->get_settings_field_name() );
           //$options = get_option('speaker-license-settings' );
		    if ( ! isset( $options['email'] ) || ! isset( $options['license_key'] ) ) {
		        // User hasn't saved the license to settings yet. No use making the call.
	        return false;

		    }

		    /*$info = $this->call_api('info',array(
		            
		            'email' => $options['email'],
		            'license_key' => $options['license_key'],
                    'request' => 'activation',
                    'product_id' => 'Symposium Speaker Profiles Pro',
                    'instance' => uniqid(),
                    'platform' => home_url(),          
                    'software_version' => '0.1'
		        )
		    );

		 
		    return $info;*/
		}

		function edd_sample_activate_license() {
			// listen for our activate button to be clicked
			if( isset( $_POST['edd_license_activate'] ) ) {
				// run a quick security check 
			 	if( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) ) 	
					return; // get out if we didn't click the Activate button
				// retrieve the license from the database
				$license = trim( $_POST[ 'edd_sample_license_key'] );
					
				// data to send in our API request
				$api_params = array( 
					'edd_action'=> 'activate_license', 
					'license' 	=> $license, 
					'item_name' => urlencode( EDD_SL_ITEM_NAME ), // the name of our product in EDD,
					'url'       => home_url()
				);
				
				// Call the custom API.
				$response = wp_remote_post( EDD_SL_STORE_URL, array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params
				) );
				// make sure the response came back okay
				if ( is_wp_error( $response ) )
					return false;
				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				
				// $license_data->license will be either "active" or "inactive"
				update_option( 'edd_sample_license_status', $license_data->license );
			}
		}


    

	}
  

    //$taskinfo= new Wp_License_Manager_Public('WooCommerce API Manager','1.4.6.3');
 

<?php

class Agenda
{
    public $options;

	function __construct(){

        $this->options = array(
            'settings' => 'agenda_settings',
            'version'  => '1.8',
            'feature_img_size' => 'agenda-thumb',
            'installed_version' => 'agenda_installed_version'
        );

        $this->post_type = 'agenda';
        $settings = get_option($this->options['settings']);
        $this->post_type_slug = isset($settings['slug']) ? ($settings['slug'] ? sanitize_title_with_dashes($settings['slug']) : 'agenda' ) : 'agenda';
        $this->incPath       = dirname( __FILE__ );
        $this->functionsPath    = $this->incPath . '/functions/';
        $this->classesPath		= $this->incPath . '/classes/';
        $this->widgetsPath		= $this->incPath . '/widgets/';
        $this->viewsPath		= $this->incPath . '/views/';
        $this->templatePath     = $this->incPath . '/template/';

        $this->assetsUrl        = AGENDA_PLUGIN_URL  . '/assets/';
        $this->TPLloadClass( $this->classesPath );

        $this->defaultSettings = array(
            'primary_color' => '#0367bf',
            'feature_img' => array(
                'width' => 400,
                'height'=> 400
            ),
            'slug' => 'agenda',
            'link_detail_page' => 'yes',
            'custom_css' => null
        );


        register_activation_hook(SYMPOAGENDA_PLUGIN_ACTIVE_FILE_NAME, array($this, 'activate'));
        register_activation_hook( SYMPOAGENDA_PLUGIN_ACTIVE_FILE_NAME, 'my_plugin_create_db' );

        function my_plugin_create_db() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->prefix . 'session_info';

            $sql = "CREATE TABLE $table_name (
                id int(9) NOT NULL AUTO_INCREMENT,
                session_title varchar(255) NOT NULL,
                session_timefrom varchar(255) NOT NULL,
                session_timeto varchar(255) NOT NULL,
                session_speaker varchar(255) NOT NULL,
                speaker_id int(9) NOT NULL,
                session_desc LONGTEXT NOT NULL,
                session_speakerrole varchar(255) NOT NULL,
                session_room varchar(255) NOT NULL,
                session_speakerorg varchar(255) NOT NULL,
                session_orglogo varchar(255) NOT NULL,
                post_id int(9) NOT NULL,
                eventname varchar(255) NOT NULL,
                sort_order int(9) NOT NULL,
                PRIMARY KEY id (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        register_deactivation_hook(SYMPOAGENDA_PLUGIN_ACTIVE_FILE_NAME, array($this, 'deactivate'));

	}

    function verifyNonce( ){
        $nonce      = isset($_REQUEST['agenda_nonce']) ? $_REQUEST['agenda_nonce'] : '';
        $nonceText  = $this->nonceText();
        if( !wp_verify_nonce( $nonce, $nonceText ) ) return false;
        return true;
    }

    function nonceText(){
        return "agenda_nonce";
    }

    public function activate() {

        flush_rewrite_rules();
        $this->insertDefaultData();

    }

    public function deactivate() {
        flush_rewrite_rules();
    }


	function TPLloadClass($dir){
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
            }
	}

    function loadWidget($dir){
        if (!file_exists($dir)) return;
        foreach (scandir($dir) as $item) {
            if( preg_match( "/.php$/i" , $item ) ) {
                require_once( $dir . $item );
                $class = str_replace( ".php", "", $item );

                if (method_exists($class, 'register_widget')) {
                    $caller = new $class;
                    $caller->register_widget();
                }
                else {
                    register_widget($class);
                }
            }
        }
    }


	 function render( $viewName, $args = array()){
        global $Agenda;

        $viewPath = $Agenda->viewsPath . $viewName . '.php';
        if( !file_exists( $viewPath ) ) return;

        if( $args ) extract($args);
        $pageReturn = include $viewPath;
        if( $pageReturn AND $pageReturn <> 1 )
            return $pageReturn;
        if( @$html ) return $html;
    }
	/**
     * Dynamicaly call any  method from models class
     * by pluginFramework instance
     */
    function __call( $name, $args ){
        if( !is_array($this->objects) ) return;
        foreach($this->objects as $object){
            if(method_exists($object, $name)){
                $count = count($args);
                if($count == 0)
                    return $object->$name();
                elseif($count == 1)
                    return $object->$name($args[0]);
                elseif($count == 2)
                    return $object->$name($args[0], $args[1]);
                elseif($count == 3)
                    return $object->$name($args[0], $args[1], $args[2]);
                elseif($count == 4)
                    return $object->$name($args[0], $args[1], $args[2], $args[3]);
                elseif($count == 5)
                    return $object->$name($args[0], $args[1], $args[2], $args[3], $args[4]);
                elseif($count == 6)
                    return $object->$name($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
            }
        }
    }

    private function insertDefaultData()
    {
        global $Agenda;
        update_option($Agenda->options['installed_version'],$Agenda->options['version']);
        if(!get_option($Agenda->options['settings'])){
            update_option( $Agenda->options['settings'], $Agenda->defaultSettings);
        }
    }

}

global $Agenda;
if( !is_object( $Agenda ) ) $Agenda = new Agenda;

function wpb_change_title_name( $title ){
     $screen = get_current_screen();
     if  ( 'agenda' == $screen->post_type ) {
          $title = 'Event Name';
     }
     return $title;
}
add_filter( 'enter_title_here', 'wpb_change_title_name' );



// visual composer compatibility
add_action( 'vc_before_init', 'visualcomposer_compatibilityagenda' );
function visualcomposer_compatibilityagenda() {
    global $Agenda;
    vc_map( array(
        "name" => __( "Agenda", "Event-info" ),
        "base" => "agenda",
        "class" => "",
        "category" => __( "Content", "Event-info"),
        "params" => array(
         array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Shortcode", "Event-info" ),
            "param_name" => "agenda",
            "value" => '[agenda col="2"  orderby="title" order="DESC" layout="1"]',
            "description" => __( "Shortcode for Agenda Plugin.", "Agenda-info" )
         )
      )
   ) );
}



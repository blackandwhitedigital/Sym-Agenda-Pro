<?php

/**
*
*/
class Agendasettings
{

    function __construct()
    {
        add_action( 'init', array($this, 'agenda_pluginInit') );
        add_action( 'wp_ajax_agendaSettings', array($this, 'agendaSettings'));
        add_action( 'admin_menu' , array($this, 'agenda_menu_register'));
    }

    /**
     *  Ajax response for settings update
     */
    function agendaSettings(){
        global $Agenda;
        $error = true;
        if($Agenda->verifyNonce()){
            
            unset($_REQUEST['action']);
            unset($_REQUEST['agenda_nonce']);
            unset($_REQUEST['_wp_http_referer']);
            update_option( $Agenda->options['settings'], $_REQUEST);
            flush_rewrite_rules();
            $response = array(
                    'error'=> $error,
                    'msg' => __('Settings successfully updated',AGENDA_SLUG)
                );
        }else{

            $response = array(
                    'error'=> true,
                    'msg' => __('Security Error!!',AGENDA_SLUG)
                );
        }
        wp_send_json( $response );
        die();
    }


    /**
     *  Text domain + image size register
     */
    function agenda_pluginInit(){
        $this->load_plugin_textdomain();

        global $Agenda;
        $settings = get_option($Agenda->options['settings']);
        /*$width = isset($settings['feature_img']['width']) ? ($settings['feature_img']['width'] ? (int) $settings['feature_img']['width'] : 400) : 400;
        $height = isset($settings['feature_img']['height']) ? ($settings['feature_img']['height'] ? (int) $settings['feature_img']['height'] : 400) : 400;
        add_image_size( $Agenda->options['feature_img_size'], $width, $height, true );*/

    }
    /**
     *  agenda menu addition
     */
    function agenda_menu_register() {
        global $Agenda;
        $page = add_submenu_page( 'edit.php?post_type=agenda', __('Agenda Settings', AGENDA_SLUG), __('Settings', AGENDA_SLUG), 'administrator', 'Agenda_settings', array($this, 'Agenda_settings') );

        add_action('admin_print_styles-' . $page, array( $this,'agenda_style'));
        add_action('admin_print_scripts-'. $page, array( $this,'agenda_script'));
        wp_enqueue_style( 'agenda_css_settings', $Agenda->assetsUrl . 'css/agendasettings.css');
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'agenda_js_settings',  $Agenda->assetsUrl. 'js/agendasettings.js', array('jquery','wp-color-picker'), '', true );
        $nonce = wp_create_nonce( $Agenda->nonceText() );
        wp_localize_script( 'agenda_js_settings', 'agenda_var', array('agenda_nonce' => $nonce) );

    }

    /**
     *  Agenda Style addition
     */
    function agenda_style(){
        global $Agenda;
        wp_enqueue_style( 'agenda_css_settings', $Agenda->assetsUrl . 'css/agendasettings.css');
    }

    /**
     *  Agenda script addition
     */
    function agenda_script(){
        global $Agenda;
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'agenda_js_settings',  $Agenda->assetsUrl. 'js/agendasettings.js', array('jquery','wp-color-picker'), '', true );
        $nonce = wp_create_nonce( $Agenda->nonceText() );
        wp_localize_script( 'agenda_js_settings', 'agenda_var', array('agenda_nonce' => $nonce) );
    }


    /**
     * Render agenda settings page
     */
    function Agenda_settings(){
        global $Agenda;
        $Agenda->render('settings');
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 0.1.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( AGENDA_SLUG, FALSE,  AGENDA_LANGUAGE_PATH );
    }

}

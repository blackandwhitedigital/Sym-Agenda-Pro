<?php
if (!class_exists('AgendaPostMeta')):
 
    /**
     * Add meta Box for Custom Event information Values 
     */
    class AgendaPostMeta
    {    

        function __construct() {
            add_action('add_meta_boxes', array($this, 'agenda_meta_boxs'));
            add_action('save_post', array($this, 'save_agenda_meta_data'), 10, 3);
            add_action('admin_print_scripts-post-new.php', array($this, 'agenda_script'), 11);
            add_action('admin_print_scripts-post.php', array($this, 'agenda_script'), 11);
            add_action( 'edit_form_after_title', array($this, 'agenda_after_title') );
            add_action( 'admin_enqueue_scripts', array($this, 'calendar_enqueue'));
        }

        function agenda_after_title($post){
            global $Agenda;
            if( $Agenda->post_type !== $post->post_type) {
                return;
            }
        }

        function agenda_meta_boxs() {
            add_meta_box(
                'agenda_meta',
                __('Agenda Info', AGENDA_SLUG ),
                array($this,'agenda_meta'),
                'agenda',
                'normal',
                'high');
        }

        function agenda_meta($post){
                global $Agenda;
                wp_nonce_field( $Agenda->nonceText(), 'agenda_nonce' );
                $meta = get_post_meta( $post->ID );
                
?>
            <div class="member-field-holder">

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="short_bio"><?php _e('Short Description:', AGENDA_SLUG); ?></label>
                    </div>
                    <div class="tlp-field">
                        <textarea name="short_bio" rows="5" class="tlpfield" value=""><?php echo (@$meta['short_bio'][0] ? @$meta['short_bio'][0] : null) ?></textarea>
                        <span class="desc"><?php _e('Add some short description', AGENDA_SLUG); ?></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="event_date"><?php _e('Agenda Date', AGENDA_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" id="event_date" name="event_date" class="tlpfield datepicker" value="<?php echo (@$meta['event_date'][0] ? @$meta['event_date'][0] : null) ?>">
                        <span class="event_date"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="location"><?php _e('Location', AGENDA_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                       <input type="text" name="location" class="tlpfield" value="<?php echo (@$meta['location'][0] ? @$meta['location'][0] : null) ?>">
                        <span class="location"></span>
                    </div>
                </div>
            </div>
        <?php
        }

        function save_agenda_meta_data($post_id, $post, $update) {
              
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

            global $Agenda;

            if ( !wp_verify_nonce( @$_REQUEST['agenda_nonce'], $Agenda->nonceText() ) )return;


            if ( 'agenda' != $post->post_type ) return;

            if ( isset( $_REQUEST['short_bio'] ) ) {
                update_post_meta( $post_id, 'short_bio', sanitize_text_field( $_REQUEST['short_bio'] ) );
            }

            if ( isset( $_REQUEST['event_date'] ) ) {
                update_post_meta( $post_id, 'event_date', sanitize_text_field( $_REQUEST['event_date'] ) );
            }

            if ( isset( $_REQUEST['location'] ) ) {
                update_post_meta( $post_id, 'location', sanitize_text_field( $_REQUEST['location'] ) );
            }

        }

        function agenda_script() {
            global $post_type,$Agenda;
            if($post_type == $Agenda->post_type){
                $Agenda->tlp_style();
                $Agenda->tlp_script();
            }
        }
        function calendar_enqueue() {
            // Registers and enqueues the required javascript.
            global $Agenda;
                wp_enqueue_media();         
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_script( 'meta-box-image', $Agenda->assetsUrl. 'js/calendar.js', array( 'jquery' ) );
                wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
                
        }
    }
endif;


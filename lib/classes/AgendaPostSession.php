<?php
if (!class_exists('AgendaPostSession')) {
    /**
     * Add meta Box for Custom Event Session information
     */
    class AgendaPostSession {


        function __construct() {
            add_action('add_meta_boxes', array($this, 'some_custom_meta'));
            add_action( 'edit_form_after_title', array($this, 'agenda_after_title') );
            add_action( 'admin_enqueue_scripts', array($this, 'prfx_calendar_enqueue'));
            add_action('wp_ajax_test_response',  array($this,'test_response'));
            add_action('wp_ajax_nopriv_test_response',  array($this,'test_response'));
            add_action('wp_ajax_editquery',  array($this,'editquery'));
            add_action('wp_ajax_nopriv_editquery',  array($this,'editquery'));
            add_action('save_post', array($this, 'save_session_meta_data'), 10, 3);
            add_filter( 'wp_default_editor', create_function('', 'return "html";') );
            
        }

        function agenda_after_title($post){
            global $Agenda;

            if( $Agenda->post_type !== $post->post_type) {
                return;
            }
        }

        function some_custom_meta() {
            add_meta_box(
                'agenda_metas',
                __('Session Info', AGENDA_SLUG ),
                array($this,'agenda_metas'),
                'agenda',
                'normal',
                'high');
        }
        
        function agenda_metas($post){
            
                global $Agenda;
                wp_nonce_field( $Agenda->nonceText(), 'agenda_nonce' );
                $meta = get_post_meta( $post->ID );  
                 $id = get_the_ID();

                global $wpdb;
                $table_name = $wpdb->prefix . 'session_info';
                $active_rows = 
                        "SELECT * FROM  $table_name WHERE post_id=$id ORDER BY STR_TO_DATE(session_timefrom,'%h:%i%p');";
                $pageposts = $wpdb->get_results($active_rows, OBJECT);
                    
                if($pageposts){
            ?>
            <div class="tscroll">
                <table class="session_details" id="session_details">
                    <tr>
                        <th>Title</th>
                        <th> From</th>
                        <th>To</th>
                        <th>Brief Description</th>
                        <th>Speaker</th>
                        <!-- <th>Role</th>
                        <th>Organisation</th> -->
                        <th>Room/Location</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        foreach ($pageposts as $key=>$value){ ?>
                        <tr>
                            <td><?php echo $value->session_title; ?></td>
                            <td><?php echo $value->session_timefrom; ?></td>
                            <td><?php echo $value->session_timeto; ?></td>
                            <td><?php echo $value->session_desc; ?></td>
                            <td><?php echo $value->session_speaker; ?></td>
                            <!-- <td><?php echo $value->session_speakerrole; ?></td>
                            <td><?php echo $value->session_speakerorg; ?></td> -->
                            <td><?php echo $value->session_room; ?></td>
                            <td>
                                <button type="button" id="dele_value" string="<?php echo $value->id; ?>" value="" onclick="deleteme(<?php echo $value->id; ?>)">
                                <?php global $Agenda; $watchSrc = $Agenda->assetsUrl.'images/trash.png'; 
                                ?>
                                <img src="<?php echo $watchSrc ?>">
                               </button>

                                <button type="button" id="edit_value" string="<?php echo $value->id; ?>" value="" onclick="editeme(<?php echo $value->id; ?>)">
                                <?php global $Agenda; $homeSrc = $Agenda->assetsUrl.'images/edit.png'; 
                                ?>
                                <img src="<?php echo $homeSrc ?>"></button> 
                            </td>
                        </tr>

                    <?php } ?>

                </table>
                </div>
            <?php } ?>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="session_title"><?php _e('Session Title', AGENDA_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="hidden" id="session_id" name="session_id" class="tlpfield" value="">
                        <input type="text" id="session_title" name="session_title" class="tlpfield" value="">
                        <span class="session_title"></span>
                    </div>
                </div>
                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="session_time"><?php _e('Session Time', AGENDA_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                    From:
                        <input type="text" id="session_timefrom" name="session_timefrom" class="tlptime tmepicker1" style= "width: 20%;" value="">
                    To:
                        <input type="text" id="session_timeto" name="session_timeto" class="tlptime timepicker2" style= "width: 20%;" value="">
                        <span class="session_time"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="session_desc"><?php _e('Brief Description', AGENDA_SLUG); ?>:</label>
                    </div>
                    <?php
                        $field_value = get_post_meta( $post->ID, 'session_desc', false );
                        wp_editor( $field_value[0], 'session_desc' );
                    ?>
                </div>
        
                <?php 
                    $argss= query_posts( array( 'post_type' => 'speaker') );
                    $agenda= new WP_Query( $argss );

                    if ($agenda->have_posts()) {  
                    ?>
                        <div class="tlp-field-holder">
                            <div class="tplp-label">
                                <label for="session_speaker"><?php _e('Speaker', AGENDA_SLUG); ?>:</label>
                            </div>
                            <div class="tlp-field">                  
                                    <select name="session_speaker" class="speak_name" id="session_speaker" onchange ="displayVals()">
                                    <option value="0" class="speakerajax">Select Option</option>
                                        <?php  while (have_posts()) : the_post();  
                                        $organisation = get_post_meta( get_the_ID(), 'organisation', true );
                                        $designation = get_post_meta( get_the_ID(), 'designation', true );
                                        $speaker_id= get_the_ID();
                                       if (has_post_thumbnail()){
                                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $Speaker->options['feature_img_size']);
                                        $imgSrc = $image[0];
                                        }else{
                                            $imgSrc = get_post_meta( get_the_ID(), 'meta-image', true );
                                            
                                        }
                                        ?>
                                            <option value="<?php echo get_the_title(); 
                                                ?>**<?php  echo $designation ?>**<?php  echo $organisation ?>**<?php  echo $imgSrc ?>**<?php echo $speaker_id ?>">
                                            <?php echo get_the_title(); 
                                            ?>
                                            </option>
                                        <?php endwhile;  ?>
                                    </select>
                                <span class="name"></span>
                            </div>
                        </div>
                        <input type="hidden" id="session_speakerrole" name="session_speakerrole" class="tlpfield" value="<?php $designation ?>">
                                 <input type="hidden" id="speaker_id" name="speaker_id" class="tlpfield" value="<?php $speaker_id ?>">
                        <input type="hidden" name="meta_image" id="meta_image" value="<?php if ( isset ( $prfx_stored_meta['meta_image'] ) ) echo $prfx_stored_meta['meta_image'][0]; ?>" />
                        <input type="hidden" id="session_speakerorg" name="session_speakerorg" class="tlpfield" value="<?php $organisation ?>">
                              

                <div class="tlp-field-holder">
                    <div class="tplp-label">
                        <label for="session_room"><?php _e('Room/Location', AGENDA_SLUG); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" id="session_room" name="session_room" class="tlpfield" value="">
                        <span class="session_room"></span>
                    </div>
                </div>
                <div class="tlp-field-holder">
                    <input type="checkbox"  value="" name="row" id="checkbox">Is Leisure?
                </div>
                <div class="tlp-field-holder">
                    <input type="submit" value="Save" name="submitb" id="submitb">
                    <input type='submit' name='update' value='Update' >
                </div>
                <div id="update"></div>
            </form>
                <?php

            }else{
                ?>
                        <div class="tlp-field-holder">
                            <div class="tplp-label">
                                <label for="session_speaker"><?php _e('Please add Speaker in Speaker Pro Plugin', AGENDA_SLUG); ?></label><br><br>
                            </div>
                        </div>
                        <div class="tlp-field-holder">
                            <div class="tplp-label">
                                <label for="session_room"><?php _e('Room/Location', AGENDA_SLUG); ?>:</label>
                            </div>
                            <div class="tlp-field">
                                <input type="text" id="session_room" name="session_room" class="tlpfield" value="">
                                <span class="session_room"></span>
                            </div>
                        </div>
                        <div class="tlp-field-holder">
                            <input type="checkbox"  value=""  name="row" id="checkbox" >Is Leisure?
                        </div>
                        <div class="tlp-field-holder">
                            <input type="submit" value="Save" name="submitbut" id="submitb">
                            <input type='submit' name='update' value='Update' >
                        </div>
                        <div id="update"></div>
                </form>  
                <?php  
            }
            }


            function save_session_meta_data($post_id, $post, $update) {
            
                if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

                    global $Agenda;

                    if ( !wp_verify_nonce( @$_REQUEST['agenda_nonce'], $Agenda->nonceText() ) )return;


                        if ( 'agenda' != $post->post_type ) return;
                
                            if (isset($_REQUEST['submitb'])){
                                
                                if (!empty($_REQUEST['session_speaker'])){
                                    $str = $_REQUEST['session_speaker'];
                                    $strr = $_REQUEST['session_speaker'];
                                    $test=(explode('**',$str,-1));
                                    $plink=(explode('**',$str,-1));                
                                    $date=(explode('**',$str,3));
                                    $testino= $test[0];
                                    $session_speakerrole=$_REQUEST['session_speakerrole'];
                                    $session_speakerorg=$_REQUEST['session_speakerorg'];
                                    $meta_image=$_POST[ 'meta_image' ];
                                }
                                else{
                                    $testino='';
                                    $session_speakerrole='';
                                    $session_speakerorg='';
                                    $meta_image='';
                                }
                                if(isset($_REQUEST['row']))
                                {
                                    $row = 1; 
                                }else{
                                    $row = 0;
                                }
                               
                                global $Agenda;
                                $id = get_the_ID();
                                $event = get_the_title();


                                    global $wpdb;
                                    $table_name = $wpdb->prefix .  'session_info' ;
                                    $sql = $wpdb->insert($table_name,
                                    array('session_title' => $_REQUEST['session_title'],
                                        'session_timefrom'=>$_REQUEST['session_timefrom'],
                                        'session_timeto'=>$_REQUEST['session_timeto'],
                                        'session_desc'=>$_REQUEST['session_desc'],
                                        'session_speaker'=>$testino,
                                        'speaker_id'=>$_REQUEST['speaker_id'],
                                        'session_speakerrole'=>$_REQUEST['session_speakerrole'],
                                        'session_speakerorg'=>$_REQUEST['session_speakerorg'],
                                        'session_room'=>$_REQUEST['session_room'],
                                        'session_orglogo'=>$_POST[ 'meta_image' ],
                                        'sort_order'=> $row,
                                        'post_id'=> $id,
                                        'eventname'=> $event,));

                                    $wpdb->query($sql);
                            }
                            if (isset($_REQUEST['submitbut'])){
                               
                                global $Agenda;
                                $id = get_the_ID();
                                $event = get_the_title();
                                if(isset($_REQUEST['row']))
                                {
                                    $row = 1; 
                                }else{
                                    $row = 0;
                                }

                                    global $wpdb;
                                    $table_name = $wpdb->prefix .  'session_info' ;
                                    $sql = $wpdb->insert($table_name,
                                    array('session_title' => $_REQUEST['session_title'],
                                        'session_timefrom'=>$_REQUEST['session_timefrom'],
                                        'session_timeto'=>$_REQUEST['session_timeto'],
                                        'session_desc'=>$_REQUEST['session_desc'],
                                        'session_room'=>$_REQUEST['session_room'],
                                        'sort_order'=> $row,
                                        'post_id'=> $id,
                                        'eventname'=> $event,));

                                    $wpdb->query($sql);
                                    
                            }


                            if (isset($_REQUEST['update'])){
                                $str = $_REQUEST['session_speaker'];
                                $strr = $_REQUEST['session_speaker'];
                                $test=(explode('**',$str,-2));
                                if(isset($_REQUEST['row']))
                                {
                                    $row = 1; 
                                }else{
                                    $row = 0;
                                }
                                $session_id=$_REQUEST['session_id'];
                                $session_title=$_REQUEST['session_title'];
                                $session_timefrom=$_REQUEST['session_timefrom'];
                                $session_timeto=$_REQUEST['session_timeto'];
                                $session_desc=$_REQUEST['session_desc'];
                                $session_speaker=$test[0];
                                $speaker_id=$_REQUEST['speaker_id'];
                                $session_speakerrole=$_REQUEST['session_speakerrole'];
                                $session_speakerorg=$_REQUEST['session_speakerorg'];
                                $session_room=$_REQUEST['session_room'];
                                $session_orglogo=$_POST[ 'meta_image' ];

                                global $wpdb;
                                $table_name = $wpdb->prefix .  'session_info' ;
                                $wpdb->query($wpdb->prepare("UPDATE $table_name SET 
                                    session_title=' $session_title', 
                                    session_timefrom=' $session_timefrom',
                                    session_timeto=' $session_timeto',
                                    session_desc=' $session_desc',
                                    session_speaker=' $session_speaker',
                                    speaker_id = '$speaker_id',
                                    session_speakerrole=' $session_speakerrole',
                                    session_speakerorg=' $session_speakerorg',
                                    session_room=' $session_room',
                                    session_orglogo=' $session_orglogo',
                                    sort_order = '$row'
                                    WHERE id=$session_id"));
                        }
               

                }

            function test_response() {
                $id = $_POST['id'];
                    global $wpdb;
                    $table_name = $wpdb->prefix .  'session_info' ;
                    $my=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = $id",$_POST['id']));
                    
                }
            function editquery(){
                $id = $_POST['id'];
                global $wpdb;
                $table_names = $wpdb->prefix .  'session_info' ;
                $active_user="SELECT * FROM  $table_names WHERE id = $id " ;
                $my = $wpdb->get_results($active_user, OBJECT);
                $session_title=$my[0]->session_title;
                $session_timefrom=$my[0]->session_timefrom;
                $session_timeto=$my[0]->session_timeto;
                $session_speaker=$my[0]->session_speaker;
                $session_desc=$my[0]->session_desc;
                $session_speakerrole=$my[0]->session_speakerrole;
                $session_room=$my[0]->session_room;
                $session_speakerorg=$my[0]->session_speakerorg;
                $session_orglogo=$my[0]->session_orglogo;
                $sort_order=$my[0]->sort_order;
                $speaker_id=$my[0]->speaker_id;

                $session_info= $id."**".$session_title."**".$session_timefrom."**".$session_timeto."**".$session_desc."**".$session_speaker."**".$session_speakerrole."**".$session_speakerorg."**".$session_orglogo."**".$session_room."**".$sort_order.'**'.$speaker_id;
                echo $speaker_id;
                echo $session_info;
                die();
             
            }

            // Registers and enqueues the required javascript.
            function prfx_calendar_enqueue() {

                global $Agenda;
                wp_enqueue_media();
                wp_enqueue_script( 'meta_image', $Agenda->assetsUrl. 'js/image_upload.js', array( 'jquery' ) );
                wp_localize_script( 'meta_image', 'meta_image',
                        array(
                            'title' => __( 'Upload an Image', 'prfx-textdomain' ),
                            'button' => __( 'Use this image', 'prfx-textdomain' ),
                        )
                    );
                wp_enqueue_script( 'meta_image' );
                wp_enqueue_style('jquery-timepicker', $Agenda->assetsUrl. 'css/agendastyle.css');
                wp_enqueue_script( 'jquery-ui-timepicker' );
                wp_enqueue_script('timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js'); 
                wp_enqueue_script( 'timepicker_function', $Agenda->assetsUrl. 'js/timepicker.js', array( 'jquery' ) );
                wp_localize_script( 'ajax-testo', 'ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
                wp_enqueue_script( 'delete_function', $Agenda->assetsUrl. 'js/deletequery.js', array( 'jquery' ) );
                wp_enqueue_script( 'edit_function', $Agenda->assetsUrl. 'js/editquery.js', array( 'jquery' ) );
                wp_localize_script( 'ajax-test', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
                wp_enqueue_script( 'select_function', $Agenda->assetsUrl. 'js/selectbox.js', array( 'jquery' ) );
                
            
       
            }
        
    }
}
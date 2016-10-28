<?php

if (!class_exists('AgendashortCodes')):

    /**
     *
     */
    class AgendashortCodes
    {

        function __construct()
        {
            add_shortcode('agenda', array($this, 'agenda_shortcode'));
            add_action('wp_ajax_eventinfo', array($this, 'eventinfo'));
            add_action('wp_ajax_nopriv_eventinfo', array($this, 'eventinfo'));
            add_action('wp_enqueue_scripts', array($this, 'scriptsjs'));
        }

        function agenda_shortcode($atts, $content = "")
        {
            $col_A = array(1, 2, 3, 4, 6);
            global $Agenda;
            global $wpdb;
            $atts = shortcode_atts(array(
                'eventid' => '',
                'col' => 3,
                'orderby' => 'event_date',
                'order' => 'DESC',
                'layout' => 1
            ), $atts, 'agenda');

            $pagination = $atts['pagination'] == 'on' ? true : false;

            if (!in_array($atts['col'], $col_A)) {
                $atts['col'] = 3;
            }
            if (!in_array($atts['layout'], array(1, '2'))) {
                $atts['layout'] = 1;
            }

            $paged = get_query_var('paged') ? get_query_var('paged') : 1;

            $html = null;

            $mypostidss = $wpdb->get_col("SELECT ID from $wpdb->posts where ID LIKE '" . $atts['eventid'] . "%' ");
            //var_dump($mypostidss);
            $args = array(
                'post__in' => $mypostidss,
                'post_type' => 'agenda',
                'post_status' => 'publish',
                'no_found_rows' => $pagination,
                'paged' => $paged,
                'posts_per_page' => 1,
                'orderby' => $atts['orderby'],
                'order' => $atts['order']
            );

            $agendaQuery = new WP_Query($args);

            if ($agendaQuery->have_posts()) {
                $html .= '<div class="container-fluid agenda">';
                if ($atts['layout'] == '2') {
                    $html .= '<div class="agenda-layout2">';
                }
                if ($atts['layout'] != '2') {
                    $html .= '<div class="row layout' . $atts['layout'] . '">';
                }
                while ($agendaQuery->have_posts()) : $agendaQuery->the_post();

                    $title = get_the_title();
                    $pLink = get_permalink();
                    $short_bio = get_post_meta(get_the_ID(), 'short_bio', true);
                    $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                    $date = date_create($event_date);
                    $event_d = date_format($date, "d");
                    $event_day = date_format($date, "Y");
                    $event_month = date_format($date, "F");
                    $location = get_post_meta(get_the_ID(), 'location', true);
                    $id = get_the_ID();
                    $sessiont = $wpdb->prefix . 'session_info';
                    $active_rows = "SELECT * FROM  $sessiont WHERE post_id=$id ORDER BY session_timefrom";
                    $pageposts = $wpdb->get_results($active_rows, OBJECT);
                    $this->processSession($pageposts, $event_date);

                    switch ($atts['orderby']) {
                        case 'time':
                            $sessiont = $wpdb->prefix . 'session_info';
                            $active_rows = "SELECT * FROM  $sessiont WHERE post_id=$id ORDER BY STR_TO_DATE(session_timefrom,'%h:%i%p') ";
                            $pageposts = $wpdb->get_results($active_rows, OBJECT);
                            break;

                        case 'speaker':
                            $sessiont = $wpdb->prefix . 'session_info';
                            $active_rows = "SELECT * FROM  $sessiont WHERE post_id=$id ORDER BY session_speaker";
                            $pageposts = $wpdb->get_results($active_rows, OBJECT);
                            break;

                        case 'session':
                            $sessiont = $wpdb->prefix . 'session_info';
                            $active_rows = "SELECT * FROM  $sessiont WHERE post_id=$id ORDER BY session_title, binary(session_title)";
                            $pageposts = $wpdb->get_results($active_rows, OBJECT);
                            break;

                    }

                    if (has_post_thumbnail()) {
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $Agenda->options['feature_img_size']);
                        $imgSrc = $image[0];
                    } else {
                        $imgSrc = $Agenda->assetsUrl . 'images/default.jpg';
                    }
                    $grid = 12 / $atts['col'];

                    if ($atts['col'] == 2) {
                        $image_area = "tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-6 tlp-col-xs-12 ";
                        $content_area = "tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-6 tlp-col-xs-12 ";
                    } else {
                        $image_area = "tlp-col-lg-3 tlp-col-md-3 tlp-col-sm-6 tlp-col-xs-12 ";
                        $content_area = "tlp-col-lg-9 tlp-col-md-9 tlp-col-sm-6 tlp-col-xs-12 ";
                    }

                    //$sLink = unserialize(get_post_meta( get_the_ID(), 'social' , true));

                    if ($atts['layout'] != '2') {
                        $html .= "<div class='tlp-col-lg-12 tlp-col-md-12 tlp-col-sm-12 tlp-col-xs-12 tlp-equal-height'>";
                    }
                    switch ($atts['layout']) {
                        case 1:
                            $html .= $this->layoutOne($id, $title, $pLink, $imgSrc, $short_bio, $event_date, $event_d, $event_day, $event_month, $location, $pageposts);
                            break;

                        case '2':
                            $html .= $this->layoutlayout2($id, $title, $pLink, $imgSrc, $short_bio, $event_date, $location, $pageposts, $grid);
                            break;

                        default:
                            # code...
                            break;
                    }
                    if ($atts['layout'] != '2') {
                        $html .= '</div>'; //end col

                    }

                endwhile;
                if ($atts['layout'] != '2') {
                    $html .= '</div>'; // End row
                }
                wp_reset_postdata();
                // end row
                if ($atts['layout'] == '2') {
                    $html .= '</div>'; // end tlp-team-layout2
                }
                $html .= '</div>'; // end container
            } else {
                $html .= "<p>" . __('No Event found', AGENDA_SLUG) . "</p>";
            }

            /*Pagination start*/
            if ($agendaQuery->max_num_pages > 1 && is_page()) {
                $html .= '<nav class="prev-next-posts">';
                $html .= '<div call="nav-previous" class="nav-previous">';
                $html .= get_previous_posts_link(__('<span class="meta-nav"></span>Previous'));
                $html .= '</div>';
                $html .= '<div class="next-post-links">';
                $html .= get_next_posts_link(__('<span class="meta-nav"></span>Next'), $agendaQuery->max_num_pages);
                $html .= '</div>';
                $html .= '</nav>';
            }
            /*Pagination end*/

            return $html;
        }

        function processSession(&$sessionInfo, $eventDate)
        {
            $result = array();
            $time_bag = array();
            foreach ($sessionInfo as $info) {
                $time_from = date_create_from_format('Y-m-d g:ia', $eventDate . ' ' . $info->session_timefrom);
                //$time_to = date_create_from_format('Y-m-d g:ia', $eventDate . ' ' . $info->session_timeto);
                $holder = ($time_from !== false && $time_from instanceof DateTime) ? $time_from->getTimestamp() : '';
                //$holder2 = ($time_to !== false && $time_to instanceof DateTime) ? $time_to->getTimestamp() : '';
                $leisure = ((int)$info->sort_row === 1) ? true : false;
                unset($info->sort_row);
                $info->leisure = $leisure;
                $time_bag[$holder][] = $info;
            }
            sort($time_bag);
            foreach ($time_bag as $item) {
                foreach ($item as $value) {
                    $result[] = $value;
                }
            }

            $sessionInfo = $result;
        }

        function layoutOne($id, $title, $pLink, $imgSrc, $short_bio, $event_date, $event_d, $event_day, $event_month, $location, $pageposts)
        {
            global $Agenda;
            $settings = get_option($Agenda->options['settings']);
            $html = null;

            $html .= '<div class="single-team-area event-wrapper">';


            /*session table start*/


            $html .= "<div class='tlp-col-lg-12 tlp-col-md-12 tlp-col-sm-12 tlp-col-xs-12 agenda-table'>";
            $html .= '<table class="agenda-pro-table">';
            $html .= '<tbody>';
            foreach ($pageposts as $key => $value) {
                $id = $value->id;
                $session_title = $value->session_title;
                $session_timefrom = $value->session_timefrom;
                $session_timeto = $value->session_timeto;
                $session_desc = $value->session_desc;
                $session_speaker = $value->session_speaker;
                $session_speakerrole = $value->session_speakerrole;
                $session_speakerorg = $value->session_speakerorg;
                $session_orglogo = $value->session_orglogo;
                $session_room = $value->session_room;
                $speaker_id = $value->speaker_id;
                $leisure = $value->sort_order;
                $minus = $Agenda->assetsUrl.'images/minus.png';
                $plus = $Agenda->assetsUrl . 'images/plus.png';
                global $wpdb;
                $post = $wpdb->prefix . 'posts';
                $speakerlink = "SELECT ID FROM $post where ID=$speaker_id";
                $pagepostslink = $wpdb->get_results($speakerlink, OBJECT);
                //$postid = $pagepostslink[0]->ID;

                $post_speaker = get_post($postid);
                $ppLink = get_post_permalink($postid);

                if ($leisure==0) {
                    $html .= '<tr>';
                } else {
                    $html .= '<tr class="leisure-row">';
                }

                $html .= "<th><span>" . $session_timefrom . "<br></span><a href='http://maps.apple.com/?q=".$location.",".$session_room."' target='blank'><span class='session_room'>".$session_room."</span></a></span></th>";
                if ($leisure==0) {
                    $html .= '<td>';
                    $html .= "<span class='ses-title title_style'>{$session_title}</span><a id='speakertoggle'><span class='session_toggle'>";
                    $html .= "<div class='plusicondesc'>";
                    if($session_desc){
                    
                    $html .= "<a class='flip-icon plusimg".$id."' id='".$id."' onClick='fadeinFunction($id)'><div  id='flip-sec'><i class='fa fa-plus'></i></div></a></span></a><br>";
                    }

                    
                    $html .= "<a class='flip-icons minusimg".$id."' id='".$id."' onClick='fadeoutFunction($id)' ><div  id='flip-sec'><i class='fa fa-minus'></i></a>";
                    $html .= "<div class='descagenda agenda-spek-img ".$id."'>";
                    $html .="<div class='session_desc desc_style'>".$session_desc."</div>";
                    $html .= "</div></div></div></div>";

                    $html .= "<div class='minusicondesc'>";
                    if($session_desc){
                
                    $html .= "<a class='flip-icon minusimg".$id."' id='".$id."' onClick='fadeoutFunction($id)' ><div  id='flip-sec'><i class='fa fa-minus'></i></div></a></span></a><br>";
                    $html .= "<div class='agenda-spek-img ".$id."'>";
                    $html .="<div class='session_desc desc_style'>".$session_desc."</div>";
                    $html .= "</div>";
                    }
                    $html .= "<a class='flip-icons plusimg".$id."' id='".$id."' onClick='fadeinFunction($id)'><div  id='flip-sec'><i class='fa fa-plus'></i></div></a></div></div>";

                    if (!empty($session_speaker)) {
                         
                        $html .= "<p ><span class='speaker-text'>{$session_speaker}</span>";
                        if (strlen(trim($session_speakerrole))!=0 && strlen(trim($session_speaker))!=0 ){
                            $html .= ", ";
                        }
                       
                        if (strlen(trim($session_speakerrole))!=0){  
                            $html .= " <span class='speaker-role'>{$session_speakerrole}</span>";
                        }else{ 
                        }
                        if ((!empty($session_speaker) || strlen(trim($session_speakerrole))!=0 )&& strlen(trim($session_speakerorg))!=0 ){
                            //$html.= ",am";
                            $html .= ",";
                        }
                        if (strlen(trim($session_speakerorg))!=0){
                           
                             $html .= " <span class='speaker-org'>{$session_speakerorg}</span></p>";
                       
                        }else{
                            
                        }
                        $return = $wpdb->get_row( "SELECT ID FROM wp_posts WHERE post_title = '" . $session_speaker . "' && post_status = 'publish' && post_type = 'speaker' ", 'ARRAY_N' );
                       /* var_dump($return[0]);
                        echo "</br>";*/
                        $postid = $return[0];
                    if( !empty($postid) ) {
                        
                        $html .= "<a onclick='event_show(" . $postid . ")'' id='speakerinfo'><span class='session_speakerimg'><i class='fa fa-user' aria-hidden='true'></i></span></a></p>";
                    } 
                    }
                    $html .= '</td>';
                } else {
                    $html .= '<td><p class="title_style">'. $session_title . '</p><br>' . $session_desc . '</td>';
                }
                $html .= '</tr>';

                /*Pop-up window start*/

                 $html .= '<div id="textm" class="popup">';
                $html .= '<div id="popupContact">';
                $html .= '<div class="popup-content">';
                $html .= '<button onclick ="event_hide()" class="">X</button>';
                $html .= '<div class="tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-12 tlp-col-xs-12"><div class="pop-speaker" id="speakerimg" ></div></div>';
                $html .= '<div class="tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-12 tlp-col-xs-12">';
                $html .= "<h3 class='widget-heading popup-desc'><span id='namepopup'>";
                $html .= "</span><br><span id='desigpopup'></span>,";
                $html .= " <span id='orgpopup'></span>";
                $html .= "</h3>";
                $html .= '<div id="biopopup"></div>';
                $html .= '<div id="urlpopup"></div><div></div>';
                 $html .= '</div>';
                $html .= '</form>';
                $html .= '</div></div>';
                $html .= '</div>';


                /* Pop-up Window end*/

            }
            $html .= '<tbody>';
            $html .= '</table>';
            return $html;
        }

        function layoutlayout2($id, $title, $pLink, $imgSrc, $short_bio, $event_date, $location, $pageposts, $grid)
        {
            global $Agenda;
            global $wpdb;

            $settings = get_option($Agenda->options['settings']);
            $html = null;

            $html .= "<div class='team-member tlp-col-lg-12 tlp-col-md-12 tlp-col-sm-12 tlp-col-xs-12 tlp-equal-height '>";


            /*session table start*/

            $html .= "<div class='tlp-col-lg-12 tlp-col-md-12 tlp-col-sm-12 tlp-col-xs-12 agenda-table'>";

            $html .= '<table class="agenda-pro-table agenlayout2">';
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td class="iso-th col20"><span><i class="fa fa-clock-o" aria-hidden="true"></i>
                </span>Time</td><td class="iso-th col80"><span style="margin-right:5px;"><i class="fa fa-calendar" aria-hidden="true"></i></span>Session</td>';
            $html .= '</tr>';
            foreach ($pageposts as $key => $value) {
                $id = $value->id;
                $session_title = $value->session_title;
                $session_timefrom = $value->session_timefrom;
                $session_timeto = $value->session_timeto;
                $session_desc = $value->session_desc;
                $session_speaker = $value->session_speaker;
                $session_speakerrole = $value->session_speakerrole;
                $session_speakerorg = $value->session_speakerorg;
                $session_orglogo = $value->session_orglogo;
                $session_room = $value->session_room;
                $speaker_id = $value->speaker_id;
                $leisure = $value->sort_order;
                
                $plus = $Agenda->assetsUrl . 'images/plus.png';
                global $wpdb;
                $post = $wpdb->prefix . 'posts';
                $speakerlink = "SELECT ID FROM $post where ID=$speaker_id";
                $pagepostslink = $wpdb->get_results($speakerlink, OBJECT);
                //$postid = $pagepostslink[0]->ID;

                $post_speaker = get_post($postid);
                $ppLink = get_post_permalink($postid);
                
                if ($leisure==0) {
                    $html .= '<tr>';
                } else {
                    $html .= '<tr class="leisure-layout2">';
                }

                $html .= "<td class='col20'><span>". $session_timefrom . "<br></span><a href='http://maps.apple.com/?q=".$location.",".$session_room."' target='blank'><span class='session_room'>".$session_room."</span></a></span></td>";
                if ($leisure==0) {
                    $html .= '<td>';
                    $html .= "<span class='ses-title title_style'>{$session_title}</span><a id='speakertoggle'><span class='session_toggle'>";
                    $html .= "<div class='plusicondesc'>";
                    if($session_desc){
                    
                    $html .= "<a class='flip-icon plusimg".$id."' id='".$id."' onClick='fadeinFunction($id)'><div  id='flip-sec'><i class='fa fa-plus'></i></div></a></span></a><br>";
                    }

                    
                    $html .= "<a class='flip-icons minusimg".$id."' id='".$id."' onClick='fadeoutFunction($id)' ><div  id='flip-sec'><i class='fa fa-minus'></i></a>";
                    $html .= "<div class='descagenda agenda-spek-img ".$id."'>";
                    $html .="<div class='session_desc desc_style'>".$session_desc."</div>";
                    $html .= "</div></div></div></div>";

                    $html .= "<div class='minusicondesc'>";
                    if($session_desc){
                
                    $html .= "<a class='flip-icon minusimg".$id."' id='".$id."' onClick='fadeoutFunction($id)' ><div  id='flip-sec'><i class='fa fa-minus'></i></div></a></span></a><br>";
                    $html .= "<div class='agenda-spek-img ".$id."'>";
                    $html .="<div class='session_desc desc_style'>".$session_desc."</div>";
                    $html .= "</div>";
                    }
                    $html .= "<a class='flip-icons plusimg".$id."' id='".$id."' onClick='fadeinFunction($id)'><div  id='flip-sec'><i class='fa fa-plus'></i></div></a></div></div>";
                      

                    if (!empty($session_speaker)) {
                        $html .= "<p><span class='speaker-text'>{$session_speaker}</span>";
                        if (strlen(trim($session_speakerrole))!=0 && strlen(trim($session_speaker))!=0 ){
                            $html .= ", ";
                        }
                        
                        if (strlen(trim($session_speakerrole))!=0){  
                            $html .= " <span class='speaker-role'>{$session_speakerrole}</span>";
                        }else{ 
                        }
                        if ((!empty($session_speaker) || strlen(trim($session_speakerrole))!=0 )&& strlen(trim($session_speakerorg))!=0 ){
                            //$html.= ",am";
                            $html .= ",";
                        }
                        if (strlen(trim($session_speakerorg))!=0){
                           
                             $html .= " <span class='speaker-org'>{$session_speakerorg}</span></p>";
                       
                        }else{
                            
                        }

                        $return = $wpdb->get_row( "SELECT ID FROM wp_posts WHERE post_title = '" . $session_speaker . "' && post_status = 'publish' && post_type = 'speaker' ", 'ARRAY_N' );

                        if( !empty($return) ) {
                            $return = $postid;
                            $html .= "<a onclick='event_show(" . $postid . ")'' id='speakerinfo'><span class='session_speakerimg'><i class='fa fa-user' aria-hidden='true'></i></span></a></p>";
                        } 

                    }
                    $html .= '</td>';
                } else {
                    $html .= '<td><p class="title_style">'. $session_title . '</p><br>' . $session_desc . '</td>';
                }
                $html .= '</tr>';

                /*Pop-up window start*/

                $html .= '<div id="textm" class="popup">';
                $html .= '<div id="popupContact">';
                $html .= '<div class="popup-content">';
                $html .= '<button onclick ="event_hide()" class="">X</button>';
                $html .= '<div class="tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-12 tlp-col-xs-12"><div class="pop-speaker" id="speakerimg" ></div></div>';
                $html .= '<div class="tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-12 tlp-col-xs-12">';
                $html .= "<h3 class='widget-heading popup-desc'><span id='namepopup'>";
                $html .= "</span><br><span id='desigpopup'></span>,";
                $html .= " <span id='orgpopup'></span>";
                $html .= "</h3>";
                $html .= '<div id="biopopup"></div>';
                $html .= '<div id="urlpopup"></div><div></div>';
                 $html .= '</div>';
                $html .= '</form>';
                $html .= '</div></div>';
                $html .= '</div>';


                /* Pop-up Window end*/

            }
            $html .= '<tbody>';
            $html .= '</table>';
            return $html;
        }

        function eventinfo()
        {
            global $Speaker;
            $id = $_POST['id'];
            $post_info = get_post($id);
            if (!empty($post_info)){
              $titles = $post_info->post_title;
                $organisations = get_post_meta($id, 'organisation', true);
                $designations = get_post_meta($id, 'designation', true);
                $short_bios = get_post_meta($id, 'short_bio', true);
                $logos = get_post_meta($id, 'meta-image', true);
                if (!empty($logos)) {
                    $logo = $logos;
                } else {
                    $logo = 0;
                }
                if (has_post_thumbnail($id)) {
                    $images = wp_get_attachment_image_src(get_post_thumbnail_id($id));
                    $imgSrcc = $images[0];
                } else {
                    $imgSrcc = $Speaker->assetsUrl . 'images/demo.jpg';
                }
                $speakerinfo = $titles . "**" . $imgSrcc . "**" . $organisations . "**" . $designations . "**" . $short_bios . "**" . $logo;
                echo $speakerinfo;
                die();  
            }
            
        }

        function scriptsjs()
        {

            global $Agenda;
            wp_enqueue_script('fadein_function', $Agenda->assetsUrl . 'js/fadein.js', array('jquery'));

            wp_enqueue_script('fadein_js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js');
            wp_enqueue_script( 'fancyboxAgendaajaxjs', ' http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js
                ' );
            wp_enqueue_script( 'fancyboxAgendajs', 'http://code.jquery.com/jquery-1.11.1.min.js' );
            wp_enqueue_script( 'Agenda-fancybox-js', 'http://www.jqueryscript.net/demo/Basic-Animated-Modal-Popup-Plugin-with-jQuery-stepframemodal/jquery.stepframemodal.js');
            wp_enqueue_script('ajax_testingo', $Agenda->assetsUrl . 'js/fancybox.js', array('jquery'), '2.2.2', true);
            wp_localize_script('ajax_testingo', 'the_ajax_event', array('ajaxurl' => admin_url('admin-ajax.php')));

        }

    }

endif;

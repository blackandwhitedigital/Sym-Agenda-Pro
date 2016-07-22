<?php
if( !class_exists( 'AgendafrontEnd' ) ) :

	class AgendafrontEnd {

        function __construct(){
            add_action( 'wp_enqueue_scripts', array($this, 'agenda_front_end') );
            add_action( 'wp_head', array($this, 'custom_css') );
        }

        function custom_css(){
            $html = null;
            global $Agenda;
            $settings = get_option($Agenda->options['settings']);
            $pc = (isset($settings['primary_color']) ? ($settings['primary_color'] ? $settings['primary_color'] : '#0367bf' ) : '#0367bf');
            $bc = (isset($settings['th_color']) ? ($settings['th_color'] ? $settings['th_color'] : '#3498DB' ) : '#3498DB');
            $sc = (isset($settings['tr_color']) ? ($settings['tr_color'] ? $settings['tr_color'] : '#f2f2f2' ) : '#f2f2f2');
            $td = (isset($settings['td_color']) ? ($settings['td_color'] ? $settings['td_color'] : '#fff' ) : '#fff');
            $imgw = (isset($settings['imgw']) ? ($settings['imgw'] ? $settings['imgw'] : 150 ) : 150);
            $imgh = (isset($settings['imgh']) ? ($settings['imgh'] ? $settings['imgh'] : 150 ) : 150);
            $br = (isset($settings['border_radius']) ? ($settings['border_radius'] ? $settings['border_radius'] : '0%' ) : '0%');
            $bp = (isset($settings['bullet_point']) ? ($settings['bullet_point'] ? $settings['bullet_point'] : 'circle' ) : 'circle');
            $tc = (isset($settings['text_color']) ? ($settings['text_color'] ? $settings['text_color'] : '#4b5159' ) : '#4b5159');
            $ts = (isset($settings['text_size']) ? ($settings['text_size'] ? $settings['text_size'] : '14px' ) : '14px');
            $ta = (isset($settings['text_align']) ? ($settings['text_align'] ? $settings['text_align'] : 'left' ) : 'left');
            
            
            $html .= "<style type='text/css'>";
            $html .= 'event-content a,.contact-info li.event_date, .contact-info li.location, .contact-info .event_date, .contact-info .location ,.agenda .short-desc, .agenda .agenda-isotope .agenda-content, .agenda .button-group .selected, .agenda .layout1 .agenda-content, .agenda .agenda-social a, .agenda .agenda-social li a.fa {';
                $html .= 'background: '.$pc;
            $html .= '}';
            $html .= '.date .day {';
                $html .= 'border-bottom:5px solid '.$pc;
            $html .= '}';
            $html .= '.event-wrapper .location, .event-wrapper .event_date,.agenda .layout1 .single-team-area h3{';
                $html .= 'color: '.$pc.'!important';
            $html .= '}';
            $html .= 'table.agenda-table tr th {';
                $html .= 'background: '.$bc;
            $html .= '}';
            $html .= 'table.agenda-table>tbody>tr:nth-child(odd)>td  {';
                $html .= 'background: '.$sc.'!important';
            $html .= '}';
            $html .= 'table.agenda-table>tbody>tr:nth-child(even)>td {';
                $html .= 'background: '.$td.'!important';
            $html .= '}';
            $html .= '.session_img{';
                $html .= 'width: '.$imgw.'px!important;';
                $html .= 'height: '.$imgh.'px!important;';
                $html .= 'border-radius: '.$br.'!important';
            $html .= '}';
            $html .= 'td.session_desc ul li{';
                $html .= 'list-style: '.$bp.'!important;';
            $html .= '}';
            $html .='.table-text{';
            $html .= 'color: '.$tc.'!important;';
            $html .= 'font-size: '.$ts.'!important;';
            $html .= 'text-align: '.$ta.'!important';
            $html .= '}';
            
            

            $html .= (isset($settings['custom_css']) ? ($settings['custom_css'] ? "{$settings['custom_css']}" : null) : null );

            $html .= "</style>";
             echo $html;
        }

	function agenda_front_end(){
            global $Agenda;
            wp_enqueue_style( 'agenda-fontawsome', $Agenda->assetsUrl .'css/font-awesome/css/font-awesome.min.css' );
            wp_enqueue_style( 'agendastyle', $Agenda->assetsUrl . 'css/agendastyle.css' );
            wp_enqueue_script( 'agenda-isotope-js', $Agenda->assetsUrl . 'js/isotope.pkgd.js', array('jquery'), '2.2.2', true);
            wp_enqueue_script( 'agenda-isotope-imageload-js', $Agenda->assetsUrl . 'js/imagesloaded.pkgd.min.js', array('jquery'), null, true);
            wp_enqueue_script( 'tpl-team-front-end', $Agenda->assetsUrl . 'js/front-end.js', null, null, true);
        }

	}
endif;

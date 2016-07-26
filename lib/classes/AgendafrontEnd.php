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
            $pc = (isset($settings['primary_color']) ? ($settings['primary_color'] ? $settings['primary_color'] : '#4b4b4b' ) : '#4b4b4b');
            $lt = (isset($settings['ltext_color']) ? ($settings['ltext_color'] ? $settings['ltext_color'] : '#fff' ) : '#fff');
            $imgw = (isset($settings['imgw']) ? ($settings['imgw'] ? $settings['imgw'] : 150 ) : 150);
            $imgh = (isset($settings['imgh']) ? ($settings['imgh'] ? $settings['imgh'] : 150 ) : 150);
            $bp = (isset($settings['bullet_point']) ? ($settings['bullet_point'] ? $settings['bullet_point'] : 'circle' ) : 'circle');
             /* heading setting layout1*/
            $tc = (isset($settings['heading_color']) ? ($settings['heading_color'] ? $settings['heading_color'] : '#4a4a4a' ) : '#4a4a4a');
            $ts = (isset($settings['heading_size']) ? ($settings['heading_size'] ? $settings['heading_size'] : '15px' ) : '15px');
            $ta = (isset($settings['heading_align']) ? ($settings['heading_align'] ? $settings['heading_align'] : 'none' ) : 'none');
            $bc = (isset($settings['table_color']) ? ($settings['table_color'] ? $settings['table_color'] : '') : '');
            /* speaker setting layout1*/
            $sc = (isset($settings['speaker_color']) ? ($settings['speaker_color'] ? $settings['speaker_color'] : '' ) : '');
            $ss = (isset($settings['speaker_size']) ? ($settings['speaker_size'] ? $settings['speaker_size'] : '' ) : '');
            $sa = (isset($settings['speaker_align']) ? ($settings['speaker_align'] ? $settings['speaker_align'] : 'none' ) : 'none');
           
             /* description setting layout1*/
            $ddc = (isset($settings['descr_color']) ? ($settings['descr_color'] ? $settings['descr_color'] : '' ) : '');
            $dds = (isset($settings['descr_size']) ? ($settings['descr_size'] ? $settings['descr_size'] : '' ) : '');
            $dda = (isset($settings['descr_align']) ? ($settings['descr_align'] ? $settings['descr_align'] : 'none' ) : 'none');
           

            $html .= "<style type='text/css'>";
            $html .= '.agenda-pro-table.agenisotope tr.leisure-isotope,.agenda-pro-table tr.leisure-row,.roomNo,.agenda-content,.agenda .short-desc, .agenda .agenda-isotope .agenda-content, .agenda .button-group .selected, .agenda .layout1 .agenda-content, .agenda .agenda-social a, .agenda .agenda-social li a.fa {';
            $html .= 'background: '.$pc.'!important';
            $html .= '}';
            $html .= '.leisure-isotope .ses-title,.agenda-pro-table .leisure-row th,.agenda-pro-table tr.leisure-row,.agenda-pro-table.agenisotope tr.leisure-isotope{';
            $html .= 'color: '.$lt;
            $html .= '}';
            $html .= '.agenda-pro-table tr:nth-child(odd),.agenda-pro-table.agenisotope tr:nth-child(odd){';
            $html .= 'background: '.$bc;
            $html .= '}';
            
            /* speaker setting layout1*/
            $html .='.speaker-text,.speaker-org,.speaker-role{';
            $html .= 'color: '.$sc.'!important;';
            $html .= 'font-size: '.$ss.'!important;';
            $html .= 'text-align: '.$sa.'!important;';
            $html .= '}';
            /* speaker setting layout1 */
            /* description setting layout1*/
            $html .='.session_desc{';
            $html .= 'color: '.$ddc.'!important;';
            $html .= 'font-size: '.$dds.'!important;';
            $html .= 'text-align: '.$dda.'!important;';
            $html .= '}';
            /* description setting layout1 */
            $html .= '.agenda-pro-table .ses-title{';
            $html .= 'color: '.$tc.'!important;';
            $html .= 'font-size: '.$ts.'!important;';
            $html .= 'margin: '.$ta.'!important';
            $html .= '}';
            $html .= '.session_desc li,.entry-content ul, .entry-summary ul, .comment-content ul, .entry-content ol, .entry-summary ol, .comment-content ol{';
            $html .= 'list-style-type: '.$bp.'!important;';
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

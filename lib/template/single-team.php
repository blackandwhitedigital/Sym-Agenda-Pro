
<?php
get_header( );
	while ( have_posts() ) : the_post();
	global $post;
?>
<div class="container-fluid tlp-single-container">
	<div class="row">
		<article id="post-<?php the_ID(); ?>" <?php post_class('tlp-member-article'); ?>>

			<div class="tlp-col-lg-12 tlp-col-md-12 tlp-col-sm-12 tlp-col-xs-12">
				
				<?php
			
			$date_ev= get_post_meta( get_the_ID(), 'event_date', true );
			$date=date_create("$date_ev");
			$event_date=date_format($date,"d F Y");
			$location = get_post_meta( get_the_ID(), 'location', true );
			$id = get_the_ID();
			global $wpdb;
	        $sessiont=$wpdb->prefix . 'session_info';
			$active_rows = "SELECT * FROM  $sessiont WHERE post_id=$id ORDER BY session_timefrom";
	        $pageposts = $wpdb->get_results($active_rows, OBJECT);
			$html = null;
			$html .="<div class='tlp-single-details tlp-team'>";
			$html .= '<ul class="contact-info">';
			
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
                $postid = $pagepostslink[0]->ID;

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
                   if($session_desc){
                    $html .= "<a class='flip-icon minusimg".$id."' id='".$id."' onClick='fadeoutFunction($id)' ><div  id='flip-sec'><i class='fa fa-minus'></i></div></a></span></a><br>";
                    $html .= "<div class='agenda-spek-img ".$id."'>";
                    $html .="<div class='session_desc desc_style'>".$session_desc."</div>";
                    $html .= "</div></div>";
                    
                    }

                    $html .= "<a class='flip-icons plusimg".$id."' id='".$id."' onClick='fadeinFunction($id)'><div  id='flip-sec'><i class='fa fa-plus'></i></div></a>";


                    if (!empty($session_speaker)) {
                        $html .= "<p ><span class='speaker-text'>{$session_speaker}</span>";
                       
                        if (strlen(trim($session_speakerrole))!=0){  
                            $html .= ", <span class='speaker-role'>{$session_speakerrole}</span>";
                        }else{ 
                        }
                        
                        if (strlen(trim($session_speakerorg))!=0){
                           
                             $html .= ", <span class='speaker-org'>{$session_speakerorg}</span>";
                       
                        }else{
                            
                        }
                        $html .= "<a onclick='event_show(" . $postid . ")'' id='speakerinfo'><span class='session_speakerimg'><i class='fa fa-user' aria-hidden='true'></i></span></a></p>";

                    }
                    $html .= '</td>';
                } else {
                    $html .= '<td class="title_style">'. $session_title . '<br>' . $session_desc . '</td>';
                }
                $html .= '</tr>';

                /*Pop-up window start*/

                $html .= '<div id="textm" class="popup">';
                $html .= '<div id="popupContact">';
                $html .= '<div class="popup-content">';
                $html .= '<button onclick ="event_hide()" class="">X</button>';
                $html .= '<div class="tlp-col-lg-5 tlp-col-md-5 tlp-col-sm-5 tlp-col-xs-5"><div class="pop-speaker" id="speakerimg" ></div></div>';
                $html .= '<div class="tlp-col-lg-7 tlp-col-md-7 tlp-col-sm-7 tlp-col-xs-7">';
                $html .= "<h3 class='widget-heading popup-desc'><span id='namepopup'>";
                $html .= "</span><br><span id='desigpopup'></span>,";
                $html .= " <span id='orgpopup'></span>";
                $html .= "</h3>";
                $html .= '<div id="biopopup"></div></div>';
                $html .= '<div id="urlpopup"></div><div>';
                 $html .= '</div>';
                $html .= '</form>';
                $html .= '</div></div>';
                $html .= '</div>';

                /* Pop-up Window end*/

            }
            $html .= '<tbody>';
            $html .= '</table>';
				

			echo $html;
			function eventinfo(){
			global $Speaker;
				$id= $_POST['id'];
				$post_info = get_post($id ); 
				$titles = $post_info->post_title;
			    $organisations = get_post_meta( $id, 'organisation', true );
				$designations = get_post_meta( $id, 'designation', true );
				$short_bios = get_post_meta( $id, 'short_bio', true );
				$logos = get_post_meta( $id, 'meta-image', true );

				if (has_post_thumbnail($id)){
	      			$images = wp_get_attachment_image_src( get_post_thumbnail_id($id));
	      			$imgSrcc = $images[0];
	      		}else{
					$imgSrcc = $Speaker->assetsUrl .'images/demo.jpg';
	      		}
	      		$speakerinfo= $titles."&".$imgSrcc."&".$organisations."&".$designations."&".$short_bios."&".$logos;

	      		echo $speakerinfo;
	      		die();
			}
			function scriptsjs() {

                global $Agenda;
                wp_enqueue_script( 'fadein_function', $Agenda->assetsUrl. 'js/fadein.js', array( 'jquery' ) );
                
				wp_enqueue_script('fadein_js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js'); 
				wp_enqueue_script( 'fancyboxagendaajaxjs', ' http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js
            	' );
	            //wp_enqueue_script( 'fancyboxagendajs', 'http://code.jquery.com/jquery-1.11.1.min.js' );
	            wp_enqueue_script( 'event-fancybox-js', 'http://www.jqueryscript.net/demo/Basic-Animated-Modal-Popup-Plugin-with-jQuery-stepframemodal/jquery.stepframemodal.js');
	            wp_enqueue_script( 'ajax_testingo', $Agenda->assetsUrl . 'js/fancybox.js', array('jquery'), '2.2.2', true);
	            wp_localize_script( 'ajax_testingo', 'the_ajax_event', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
                
	        }
	        add_action('wp_ajax_eventinfo',  array($this,'eventinfo'));
            add_action('wp_ajax_nopriv_eventinfo',  array($this,'eventinfo'));
            add_action( 'wp_enqueue_scripts', array($this, 'scriptsjs') );
		
			?>
			</div>

		</article>
	</div>
</div>
<?php endwhile;
get_footer();

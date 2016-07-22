<?php

if(!class_exists('AgendaTemplate')):
    /**
     * Used to create the page template
     */
    class AgendaTemplate
    {
        function __construct()
        {
            add_filter( 'template_include', array( $this, 'template_loader' ) );
            add_filter( 'page_template', array( $this,'speaker_page_template' ));
        }

        public static function template_loader( $template ) {
            $find = array();
            $file = null;
            global $Agenda;
            if ( is_single() && get_post_type() == $Agenda->post_type ) {

                $file 	= 'single-team.php';
                $find[] = $file;
                $find[] = $Agenda->templatePath . $file;

            }
               if ( @$file ) {

                $template = locate_template( array_unique( $find ) );
                if ( ! $template ) {
                    $template = $Agenda->templatePath  . $file;
                }
            }

            return $template;
        }

        public static function speaker_page_template( $page_template )
        {
            $find = array();
             $file = null;
            if ( is_page( 'my-custom-page-slug' ) ) {
                  $file = 'speakerinfo.php';
                  $find[] = $file;
                  $find[] = $Agenda->templatePath . $file;
              }
            if ( @$file ) {

                $page_template = locate_template( array_unique( $find ) );
                if ( ! $page_template ) {
                    $page_template = $Agenda->templatePath  . $file;
                }

            }
             return $page_template;

        }

    }

endif;

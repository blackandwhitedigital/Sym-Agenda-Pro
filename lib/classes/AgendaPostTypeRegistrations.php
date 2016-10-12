<?php

if(!class_exists('AgendaPostTypeRegistrations')):

	class AgendaPostTypeRegistrations {
		public function __construct() {
			// Add the event post type and taxonomies
			add_action( 'init', array( $this, 'register' ) );
		}
		/**
		 * Initiate registrations of post type and taxonomies.
		 *
		 * @uses Portfolio_Post_Type_Registrations::register_post_type()
		 */
		public function register() {
			$this->register_post_type();
		}

		/**
		 * Register the custom post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		protected function register_post_type() {
			global $Agenda;
			$agenda_labels = array(
			    'name'                => _x( 'Symposium Agenda Pro', AGENDA_SLUG ),
			    'singular_name'       => _x( 'Agendas', AGENDA_SLUG ),
			    'menu_name'           => __( 'Agenda Pro', AGENDA_SLUG ),
			    'name_admin_bar'      => __( 'Agenda', AGENDA_SLUG ),
			    'parent_item_colon'   => __( 'Parent Agenda:', AGENDA_SLUG ),
			    'all_items'           => __( 'All Agendas', AGENDA_SLUG ),
			    'add_new_item'        => __( 'Add New Agenda', AGENDA_SLUG ),
			    'add_new'             => __( 'Add Agenda', AGENDA_SLUG ),
			    'new_item'            => __( 'New Agenda', AGENDA_SLUG ),
			    'edit_item'           => __( 'Edit Agenda', AGENDA_SLUG ),
			    'update_item'         => __( 'Update Agenda', AGENDA_SLUG ),
			    'view_item'           => __( 'View Agenda', AGENDA_SLUG ),
			    'search_items'        => __( 'Search Agenda', AGENDA_SLUG ),
			    'not_found'           => __( 'Not found', AGENDA_SLUG ),
			    'not_found_in_trash'  => __( 'Not found in Trash', AGENDA_SLUG ),
			);
			$agenda_args = array(
			    'label'               => __( 'Agenda Pro', AGENDA_SLUG ),
			    'description'         => __( 'Agenda', AGENDA_SLUG ),
			    'labels'              => $agenda_labels,
			    'supports'            => array( 'title', 'editor','thumbnail', 'page-attributes' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'rewrite'			  => array('slug' => $Agenda->post_type_slug),
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'			=> $Agenda->assetsUrl.'images/Agenda-Icon_new.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( $Agenda->post_type, $agenda_args );
			flush_rewrite_rules();
		}
	}

endif;

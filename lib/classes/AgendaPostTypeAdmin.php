<?php

if(!class_exists('AgendaPostTypeAdmin')):

	/**
	*
	*/
	class AgendaPostTypeAdmin
	{

		function __construct()
		{
			add_filter( 'manage_edit-agenda_columns', array($this, 'arrange_agenda_columns'));
			add_action( 'manage_agenda_posts_custom_column', array($this,'manage_agenda_columns'), 10, 2);
			add_filter( "manage_edit-agenda_sortable_columns", array($this,'agenda_column_sort'));
		}


		public function add_taxonomy_filters() {
			global $typenow, $Agenda;
			// Must set this to the post type you want the filter(s) displayed on
			if ( $Agenda->post_type !== $typenow ) {
				return;
			}
			$taxonomies = array();
			foreach ( $taxonomies as $tax_slug ) {
				echo $this->build_taxonomy_filter( $tax_slug );
			}
		}

		/**
		 * Build an individual dropdown filter.
		 *
		 * @param  string $tax_slug Taxonomy slug to build filter for.
		 *
		 * @return string Markup, or empty string if taxonomy has no terms.
		 */
		protected function build_taxonomy_filter( $tax_slug ) {
			$terms = get_terms( $tax_slug );
			if ( 0 == count( $terms ) ) {
				return '';
			}
			$tax_name = $this->get_taxonomy_name_from_slug( $tax_slug );
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			$filter  = '<select name="' . esc_attr( $tax_slug ) . '" id="' . esc_attr( $tax_slug ) . '" class="postform">';
			$filter .= '<option value="0">' . esc_html( $tax_name ) .'</option>';
			$filter .= $this->build_term_options( $terms, $current_tax_slug );
			$filter .= '</select>';
			return $filter;
		}

		/**
		 * Get the friendly taxonomy name, if given a taxonomy slug.
		 *
		 * @param  string $tax_slug Taxonomy slug.
		 *
		 * @return string Friendly name of taxonomy, or empty string if not a valid taxonomy.
		 */
		protected function get_taxonomy_name_from_slug( $tax_slug ) {
			$tax_obj = get_taxonomy( $tax_slug );
			if ( ! $tax_obj )
				return '';
			return $tax_obj->labels->name;
		}

		/**
		 * Build a series of option elements from an array.
		 *
		 * Also checks to see if one of the options is selected.
		 *
		 * @param  array  $terms            Array of term objects.
		 * @param  string $current_tax_slug Slug of currently selected term.
		 *
		 * @return string Markup.
		 */
		protected function build_term_options( $terms, $current_tax_slug ) {
			$options = '';
			foreach ( $terms as $term ) {
				$options .= sprintf(
					"<option value='%s' %s />%s</option>",
					esc_attr( $term->slug ),
					selected( $current_tax_slug, $term->slug, false ),
					esc_html( $term->name . '(' . $term->count . ')' )
				);
				
			}
			return $options;
		}


		public function arrange_agenda_columns( $columns ) {
			$column_designation = array( 'event_date' => __( 'Agenda Date', AGENDA_SLUG ) );
			$column_ID = array( 'ID' => __( 'Agendaid', AGENDA_SLUG ) );
			$column_location = array( 'location' => __( 'Location', AGENDA_SLUG ) );
			return array_slice( $columns, 0, 2, true )  +$column_ID+ $column_designation + $column_location + array_slice( $columns, 1, null, true );
		}

		public function manage_agenda_columns( $column ) {
			
			switch ( $column ) {
				
				case 'event_date':
				    echo get_post_meta( get_the_ID() , 'event_date' , true );
				    break;
				
				case 'location':
				    echo get_post_meta( get_the_ID() , 'location' , true );
				    break;
				case 'ID':
				    echo get_the_ID() ;
				    break;
				default:
				    break;
			}
		}

		function agenda_column_sort($columns){
		    $custom = array(
		        'event_date'     => 'event_date',
		        'ID'     => 'eventid',
		        'location'		=> 'location'
		    );
		    return wp_parse_args($custom, $columns);
		}
	}

endif;

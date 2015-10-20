<?php
/*
Plugin Name: CAHNRSWP CSANR Grants
Description: Enables a showcase of the research funded through CSANR.
Author: CAHNRS, philcable
Version: 0.1.0
*/

class CAHNRSWP_CSANR_Grants {

	/**
	 * @var string Content type slug.
	 */
	var $grants_content_type = 'grants';

	/**
	 * @var string Taxonomy slugs.
	 */
	var $grants_investigators_taxonomy = 'investigators';
	var $grants_status_taxonomy = 'status';
	var $grants_topics_taxonomy = 'topic';
	var $grants_types_taxonomy = 'type';

	/**
	 * Start the plugin and apply associated hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ) );
		add_action( 'edit_form_after_editor', array( $this, 'edit_form_after_editor' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 1 );
		add_action( 'save_post_grants', array( $this, 'save_post' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_filter( 'template_include', array( $this, 'template_include' ), 1 );
		//add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class'), 100, 3 );
	}

	/**
	 * Register content type and taxonomies.
	 */
	public function init() {

		$grants = array(
			'description'   => 'Research funded through CSANR.',
			'public'        => true,
			'hierarchical'  => false,
			'menu_position' => 20,
			'menu_icon'     => 'dashicons-awards',
			'has_archive'   => true,
			'labels'        => array(
				'name'               => 'Grants',
				'singular_name'      => 'Grant',
				'all_items'          => 'All Grants',
				'view_item'          => 'View Grant',
				'add_new_item'       => 'Add New Grant',
				'add_new'            => 'Add New',
				'edit_item'          => 'Edit Grant',
				'update_item'        => 'Update Grant',
				'search_items'       => 'Search grants',
				'not_found'          => 'No grants found',
				'not_found_in_trash' => 'No grants found in Trash',
			),
			'supports'      => array(
				'title',
				'editor',
				'revisions',
			),
			'rewrite'       => array(
				'slug'       => $this->grants_content_type,
				'with_front' => false
			),
		);
		register_post_type( $this->grants_content_type, $grants );

		$investigators = array(
			'labels'            => array(
				'name'          => 'Investigators',
				'singular_name' => 'Investigator',
				'search_items'  => 'Search Investigators',
				'all_items'     => 'All Investigators',
				'edit_item'     => 'Edit Investigator',
				'update_item'   => 'Update Investigator',
				'add_new_item'  => 'Add New Investigator',
				'new_item_name' => 'New Investigator Name',
				'menu_name'     => 'Investigators',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_investigators_taxonomy, $this->grants_content_type, $investigators );

		$status = array(
			'labels'            => array(
				'name'          => 'Status',
				'singular_name' => 'Status',
				'search_items'  => 'Search Status',
				'all_items'     => 'All Status',
				'edit_item'     => 'Edit Status',
				'update_item'   => 'Update Status',
				'add_new_item'  => 'Add New Status',
				'new_item_name' => 'New Status Name',
				'menu_name'     => 'Status',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_status_taxonomy, $this->grants_content_type, $status );

		$topics = array(
			'labels'            => array(
				'name'          => 'Topics',
				'singular_name' => 'Topic',
				'search_items'  => 'Search Topic',
				'all_items'     => 'All Topics',
				'edit_item'     => 'Edit Topic',
				'update_item'   => 'Update Topic',
				'add_new_item'  => 'Add New Topic',
				'new_item_name' => 'New Topic Name',
				'menu_name'     => 'Topics',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_topics_taxonomy, $this->grants_content_type, $topics );

		$types = array(
			'labels'            => array(
				'name'          => 'Types',
				'singular_name' => 'Type',
				'search_items'  => 'Search Type',
				'all_items'     => 'All Types',
				'edit_item'     => 'Edit Type',
				'update_item'   => 'Update Type',
				'add_new_item'  => 'Add New Type',
				'new_item_name' => 'New Type Name',
				'menu_name'     => 'Types',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_types_taxonomy, $this->grants_content_type, $types );

	}

	/**
	 * Enqueue scripts and styles for the admin interface.
	 */
	public function admin_enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( ( 'post-new.php' === $hook || 'post.php' === $hook ) && $this->grants_content_type === $screen->post_type ) {
			wp_enqueue_style( 'grants-admin', plugins_url( 'css/admin-grants.css', __FILE__ ), array() );
			wp_enqueue_script( 'grants-admin', plugins_url( 'js/admin-grants.js', __FILE__ ), array( 'jquery' ) );
		}
	}

	/**
	 * Add a metabox context after the title.
	 *
	 * @param WP_Post $post
	 */
	public function edit_form_after_title( $post ) {
		if ( $this->grants_content_type !== $post->post_type ) {
			return;
		}
		do_meta_boxes( get_current_screen(), 'after_title', $post );
	}

	/**
	 * Add a metabox context after the editor.
	 *
	 * @param WP_Post $post
	 */
	public function edit_form_after_editor( $post ) {
		if ( $this->grants_content_type !== $post->post_type ) {
			return;
		}
		do_meta_boxes( get_current_screen(), 'after_editor', $post );
		$this->additional_wp_editors( $post->ID );
	}

	/**
	 * Add custom meta boxes.
	 *
	 * @param string $post_type The slug of the current post type.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( $this->grants_content_type !== $post_type ) {
			return;
		}
		add_meta_box(
			'csanr_grant_information',
			'Grant Information',
			array( $this, 'csanr_grant_information' ),
			$this->grants_content_type,
			'after_title',
			'high'
		);
		add_meta_box(
			'csanr_grant_annual_entry',
			'Annual Entries',
			array( $this, 'csanr_grant_annual_entry' ),
			$this->grants_content_type,
			'after_editor',
			'high'
		);
	}

	/**
	 * Grant information input markup.
	 */
	public function csanr_grant_information( $post ) {
		wp_nonce_field( 'grants_meta', 'grants_meta_nonce' );
		$project_id = get_post_meta( $post->ID, '_csanr_grant_project_id', true );
		echo '<p><label for="csanr-grant-project-id">Permanent Project ID</label>';
		echo '<input type="text" name="_csanr_grant_project_id" id="csanr-grant-project-id" value="'. esc_attr( $project_id ) .'"></p>';
		$funds = get_post_meta( $post->ID, '_csanr_grant_funds', true );
		echo '<p><label for="csanr-grant-funds">CSANR Funds</label>';
		echo '<input type="text" name="_csanr_grant_funds" id="csanr-grant-funds" value="'. esc_attr( $funds ) .'"></p>';
		$arc_funds = get_post_meta( $post->ID, '_csanr_grant_arc_funds', true );
		echo '<p><label for="csanr-grant-arc-funds">ARC Funds</label>';
		echo '<input type="text" name="_csanr_grant_arc_funds" id="csanr-grant-arc-funds" value="'. esc_attr( $arc_funds ) .'"></p>';
	}

	/**
	 * Grant annual entries input markup.
	 */
	public function csanr_grant_annual_entry( $post ) {
		$entries = get_post_meta( $post->ID, '_csanr_grant_annual_entries', true );
		if ( $entries && is_array( $entries ) ) {
			$i = 0;
			foreach ( $entries as $index => $entry ) {
				$this->annual_entry_markup( $i, $index, $entry );
				$i++;
			}
		} else {
			$this->annual_entry_markup( 0, NULL, NULL );
		}
		?><p class="cahnrswp-add-repeatable-meta"><a href="#">+ Add New Annual Entry</a></p><?php
	}

	/**
	 * Grant annual entry markup.
	 */
	private function annual_entry_markup( $i, $index, $entry ) {
		$years = array( '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020' );
		$investigators = get_terms( $this->grants_investigators_taxonomy, array( 'hide_empty' => 0 ) );
		$investigator_fields = array(
			'principal_investigators' => 'Principal Investigator(s)',
			'additional_investigators' => 'Additional Investigator(s)',
			'student_investigators' => 'Student Investigator(s)',
		);
		$text_fields  = array(
			'progress_report'            => 'Progress Report URL',
			'additional_progress_report' => 'Additional Progress Report URL',
			'amount'                     => 'Grant Amount',
		);
		?>
		<div class="cahnrswp-repeatable-meta">

			<div class="grant-entry-select-fields">
				<p>
        	<label>Year<br />
						<select class="grant-entry-year" name="_csanr_grant_annual_entry[<?php echo $i; ?>][year]">
							<option value="">Select</option>
							<?php foreach ( $years as $year ) : ?>
							<option value="<?php echo $year; ?>" <?php selected( $index, $year ); ?>><?php echo $year; ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</p>

				<?php foreach ( $investigator_fields as $investigator_field_key => $investigator_field_name ) : ?>
				<p>
					<?php $investigator_value = $entry[$investigator_field_key]; ?>
					<label><?php echo $investigator_field_name; ?><br />
						<select class="investigators" multiple="multiple" name="_csanr_grant_annual_entry[<?php echo $i; ?>][<?php echo $investigator_field_key; ?>]">
							<?php foreach ( $investigators as $investigator ) : ?>
							<option value="<?php echo $investigator->term_id; ?>" <?php
								if ( $investigator_value ) {
									selected( in_array( $investigator->term_id, $investigator_value ) );
								}
								?>><?php echo $investigator->description; ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</p>
				<?php endforeach; ?>

			</div>

			<div class="grant-entry-text-fields">

				<?php foreach ( $text_fields as $text_field_key => $text_field_name ) : ?>
				<p class="<?php echo $text_field_key; ?>">
					<?php $text_field_value = $entry[$text_field_key]; ?>
					<label><?php echo $text_field_name; ?><br />
						<input type="text" name="_csanr_grant_annual_entry[<?php echo $i; ?>][<?php echo $text_field_key; ?>]" value="<?php echo esc_attr( $text_field_value ); ?>" class="widefat" />
					</label>
				</p><?php endforeach; ?><p class="cahnrswp-remove-repeatable-meta"><a href="#">Remove Entry</a></p>

			</div>

		</div>

		<?php
	}

	/**
	 * Grant wp_editors.
	 */
	public function additional_wp_editors( $post_id ) {
		$editors = array(
			'_csanr_grant_publications'     => 'Publications',
			'_csanr_grant_additional_funds' => 'Additional Funds Leveraged',
			'_csanr_grant_impacts'          => 'Impacts and Outcomes',
			'_csanr_grant_admin_comments'   => 'Administrative Comments',
		);
		$editor_settings = array(
			'media_buttons' => false,
			'textarea_rows' => 5,
		);
		foreach ( $editors as $editor_id => $editor_name ) {
			?><h3 class="grant-editor-label"><?php echo $editor_name; ?></h3><?php
			$editor_value = get_post_meta( $post_id, $editor_id, true );
			wp_editor( $editor_value, $editor_id, $editor_settings );
		}
	}

	/**
	 * Save data associated with a Grant.
	 *
	 * @param int $post_id
	 *
	 * @return mixed
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['grants_meta_nonce'] ) ) {
			return $post_id;
		}
		$nonce = $_POST['grants_meta_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'grants_meta' ) ) {
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		// Text inputs.
		$text_fields = array( '_csanr_grant_project_id', '_csanr_grant_funds', '_csanr_grant_arc_funds' );
		foreach( $text_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
			} else {
				delete_post_meta( $post_id, $field );
			}
		}
		// WP editors.
		$wp_editor_fields = array( '_csanr_grant_publications', '_csanr_grant_additional_funds', '_csanr_grant_impacts', '_csanr_grant_admin_comments' );
		foreach( $wp_editor_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, wp_kses_post( $_POST[ $field ] ) );
			} else {
				delete_post_meta( $post_id, $field );
			}
		}
		// Annual entries.
		if ( isset( $_POST[ $field ] ) && '' != $_POST[ $field ] ) {
			$annual_entries = array();
			$all_investigators = array();
			foreach ( $_POST['_csanr_grant_annual_entry'] as $entry ) {
				$pi_array = array();
				$ai_array = array();
				$si_array = array();
				/*if ( $entry['principal_investigators'] ) {
					foreach ( $entry['principal_investigators'] as $pi ) {
						$pi_array[] = sanitize_text_field( $pi );
						if ( ! in_array( $pi, $all_investigators ) ) {
							$all_investigators[] = sanitize_text_field( $pi );
						}
					}
				}
				if ( $entry['additional_investigators'] ) {
					foreach ( $entry['additional_investigators'] as $ai ) {
						$ai_array[] = sanitize_text_field( $ai );
						if ( ! in_array( $pi, $all_investigators ) ) {
							$all_investigators[] = sanitize_text_field( $ai );
						}
					}
				}
				if ( $entry['student_investigators'] ) {
					foreach ( $entry['student_investigators'] as $si ) {
						$si_array[] = sanitize_text_field( $si );
						if ( ! in_array( $pi, $all_investigators ) ) {
							$all_investigators[] = sanitize_text_field( $si );
						}
					}
				}*/
				if ( $entry['year'] ) {
					$year = sanitize_text_field( $entry['year'] );
					$annual_entries[ $year ] = array();
					if ( ! empty( $pi_array ) ) {
						$annual_entries[ $year ]['principal_investigators'] = array();
						$annual_entries[ $year ]['principal_investigators'] = $pi_array;
					}
					if ( ! empty( $ai_array ) ) {
						$annual_entries[ $year ]['additional_investigators'] = array();
						$annual_entries[ $year ]['additional_investigators'] = $ai_array;
					}
					if ( ! empty( $si_array ) ) {
						$annual_entries[ $year ]['student_investigators'] = array();
						$annual_entries[ $year ]['student_investigators'] = $si_array;
					}
					if ( $entry['progress_report'] ) {
						$annual_entries[ $year ]['progress_report'] = sanitize_text_field( $entry['progress_report'] );
					}
					if ( $entry['additional_progress_report'] ) {
						$annual_entries[ $year ]['additional_progress_report'] = sanitize_text_field( $entry['additional_progress_report'] );
					}
					if ( $entry['amount'] ) {
						$annual_entries[ $year ]['amount'] = sanitize_text_field( $entry['amount'] );
					}
					
				}
				/*$annual_entries[ sanitize_text_field( $entry['year'] ) ] = array(
					'principal_investigators' => $pi_array,
					'additional_investigators' => $ai_array,
					'student_investigators' => $si_array,
					'progress_report' => sanitize_text_field( $entry['progress_report'] ),
					'additional_progress_report' => sanitize_text_field( $entry['additional_progress_report'] ),
					'amount' => sanitize_text_field( $entry['amount'] )
				);*/
			}
			if ( ! empty( $annual_entries ) ) {
				update_post_meta( $post_id, '_csanr_grant_annual_entries', $annual_entries );
			} else {
				delete_post_meta( $post_id, '_csanr_grant_annual_entries' );
			}
			if ( ! empty( $all_investigators ) ) {
				
			}
		}
		/* // For each selected investigator, set the taxonomy term
		$topics = array();
		foreach ( $categories as $category ) {
			if ( 'Agriculture' === $category ) {
				$topics[] = 143;
			}
			if ( 'Food and Nutrition' === $category ) {
				$topics[] = 64;
			}
			//if ( 'Youth and Families' === $category ) {
			//	$topic_term = get_term_by( 'id', 143, 'topic' );
			//	$topics[] = $topic_term->term_id;
			//}
		}
		if ( !empty( $topics ) ) {
			\wp_set_object_terms( $post_id, $topics, 'topic' );
		}*/
	}

	/**
	 * Enqueue the scripts and styles used on the front end.
	 */
	public function wp_enqueue_scripts() {
		if ( is_single() && $this->grants_content_type == get_post_type() ) {
			wp_enqueue_style( 'grant', plugins_url( 'css/grant.css', __FILE__ ), array( 'wsu-spine' ) );
			//wp_enqueue_script( 'grant', plugins_url( 'js/grant.js', __FILE__ ), array( 'jquery' ), '', true );
		}
		if ( is_post_type_archive( $this->grants_content_type ) ) {
			wp_enqueue_style( 'grants-archive', plugins_url( 'css/grant-archive.css', __FILE__ ), array( 'spine-theme' ) );
			//wp_enqueue_script( 'grants-archive', plugins_url( 'js/grant-archive.js', __FILE__ ), array( 'jquery' ), '', true );
		}
	}

	/**
	 * Add templates for the Grants content type.
	 *
	 * @param string $template
	 *
	 * @return string template path
	 */
	public function template_include( $template ) {
		if ( is_single() && $this->grants_content_type == get_post_type() ) {
			$template = plugin_dir_path( __FILE__ ) . 'templates/single.php';
		}
		if ( is_post_type_archive( $this->grants_content_type ) ) {
			$template = plugin_dir_path( __FILE__ ) . 'templates/index.php';
		}
		return $template;
	}

	/**
	 * Apply 'dogeared' class to the Impact Report menu item when viewing an impact report.
	 *
	 * @param array $classes Current list of nav menu classes.
	 * @param WP_Post $item Post object representing the menu item.
	 * @param stdClass $args Arguments used to create the menu.
	 *
	 * @return array Modified list of nav menu classes.
	 */
	public function nav_menu_css_class( $classes, $item, $args ) {
		$url = site_url() . '/' . $this->grants_content_type . '/';
		if ( 'site' === $args->theme_location && $this->grants_content_type == get_post_type() && $item->url == $url ) {
			$classes[] = 'dogeared';
		}
		return $classes;
	}

}

new CAHNRSWP_CSANR_Grants();
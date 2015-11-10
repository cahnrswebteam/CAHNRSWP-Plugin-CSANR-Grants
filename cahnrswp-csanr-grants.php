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
	var $grants_post_type = 'grants';

	/**
	 * @var string Taxonomy slugs.
	 */
	var $grants_investigators_taxonomy = 'investigator';
	var $grants_status_taxonomy = 'status';
	var $grants_topics_taxonomy = 'topic';
	var $grants_types_taxonomy = 'type';

	/**
	 * Start the plugin and apply associated hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 11 );
		add_action( 'init', array( $this, 'register_taxonomies' ), 10 );
		add_action( 'init', array( $this, 'grants_rewrite_rules' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_filter( 'manage_edit-grants_columns', array( $this, 'grants_columns' ), 10, 1 );
		add_action( 'manage_grants_posts_custom_column', array( $this, 'grant_columns_data' ), 10, 2 );
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ) );
		add_action( 'edit_form_after_editor', array( $this, 'edit_form_after_editor' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 1 );
		add_action( 'save_post_grants', array( $this, 'save_post' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_filter( 'template_include', array( $this, 'template_include' ), 1 );
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class'), 100, 3 );
		add_shortcode( 'csanr_grants_browse', array( $this, 'csanr_grants_browse' ) );
	}

	/**
	 * Register content type.
	 */
	public function register_post_type() {
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
				'slug'       => $this->grants_post_type,
				'with_front' => false
			),
		);
		register_post_type( $this->grants_post_type, $grants );
	}

	/**
	 * Register taxonomies.
	 */
	public function register_taxonomies() {

		$investigators = array(
			'labels'            => array(
				'name'              => 'Investigators',
				'singular_name'     => 'Investigator',
				'all_items'         => 'All Investigators',
				'edit_item'         => 'Edit Investigator',
				'view_item'         => 'View Investigator',
				'update_item'       => 'Update Investigator',
				'add_new_item'      => 'Add New Investigator',
				'new_item_name'     => 'New Investigator Name',
				'parent_item'       => 'Parent Investigator',
				'parent_item_colon' => 'Parent Investigator:',
				'search_items'      => 'Search Investigators',
				'not_found'         => 'No Investigators found.',
			),
			'rewrite'      			=> array(
				'slug'       => $this->grants_post_type . '/' . $this->grants_investigators_taxonomy,
				'with_front' => false
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_investigators_taxonomy, $this->grants_post_type, $investigators );

		$status = array(
			'labels'            => array(
				'name'              => 'Status',
				'singular_name'     => 'Status',
				'all_items'         => 'All Status',
				'edit_item'         => 'Edit Status',
				'view_item'         => 'View Status',
				'update_item'       => 'Update Status',
				'add_new_item'      => 'Add New Status',
				'new_item_name'     => 'New Status Name',
				'parent_item'       => 'Parent Status',
				'parent_item_colon' => 'Parent Status:',
				'search_items'      => 'Search Status',
				'not_found'         => 'No Status found.',
			),
			'rewrite'      			=> array(
				'slug'       => $this->grants_post_type . '/' . $this->grants_status_taxonomy,
				'with_front' => false
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_status_taxonomy, $this->grants_post_type, $status );

		$topics = array(
			'labels'            => array(
				'name'              => 'Topics',
				'singular_name'     => 'Topic',
				'all_items'         => 'All Topics',
				'edit_item'         => 'Edit Topic',
				'view_item'         => 'View Topic',
				'update_item'       => 'Update Topic',
				'add_new_item'      => 'Add New Topic',
				'new_item_name'     => 'New Topic Name',
				'parent_item'       => 'Parent Topic',
				'parent_item_colon' => 'Parent Topic:',
				'search_items'      => 'Search Topics',
				'not_found'         => 'No Topics found.',
			),
			'rewrite'      			=> array(
				'slug'       => $this->grants_post_type . '/' . $this->grants_topics_taxonomy,
				'with_front' => false
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_topics_taxonomy, $this->grants_post_type, $topics );

		$types = array(
			'labels'            => array(
				'name'              => 'Types',
				'singular_name'     => 'Type',
				'all_items'         => 'All Types',
				'edit_item'         => 'Edit Type',
				'view_item'         => 'View Type',
				'update_item'       => 'Update Type',
				'add_new_item'      => 'Add New Type',
				'new_item_name'     => 'New Type Name',
				'parent_item'       => 'Parent Type',
				'parent_item_colon' => 'Parent Type:',
				'search_items'      => 'Search Types',
				'not_found'         => 'No Types found.',
			),
			'rewrite'      			=> array(
				'slug'       => $this->grants_post_type . '/' . $this->grants_types_taxonomy,
				'with_front' => false
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
		register_taxonomy( $this->grants_types_taxonomy, $this->grants_post_type, $types );

	}

	/**
	 * Add rewrite rule for grants/{year} URLs.
	 */
	public function grants_rewrite_rules() {
		add_rewrite_rule(
			$this->grants_post_type . '/([0-9]{4})/?$',
			'index.php?post_type=' . $this->grants_post_type . '&year=$matches[1]',
			'top'
		);
	}

	/**
	 * Enqueue scripts and styles for the admin interface.
	 */
	public function admin_enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( ( 'post-new.php' === $hook || 'post.php' === $hook ) && $this->grants_post_type === $screen->post_type ) {
			wp_enqueue_style( 'grant-admin', plugins_url( 'css/admin-grant.css', __FILE__ ), array() );
			wp_enqueue_script( 'grant-admin', plugins_url( 'js/admin-grant.js', __FILE__ ), array( 'jquery' ) );
		}
		if ( 'edit.php' === $hook && $this->grants_post_type === $screen->post_type ) {
			wp_enqueue_style( 'grants-admin', plugins_url( 'css/admin-grants.css', __FILE__ ), array() );
		}
	}

	/**
	 * Add options page link to the menu.
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=' . $this->grants_post_type, 'Grants Database Settings', 'Settings', 'manage_options', 'grants_settings', array( $this, 'grants_settings_page' ) );
	}

	/**
	 * Options page settings.
	 */
	public function admin_init() {
		register_setting( 'grants_options', 'grants_menu_item' );
	}

	/**
	 * Options page content.
	 */
	public function grants_settings_page() {
		?>
		<div class="wrap">
			<h2>Grants Database Settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'grants_options' ); ?>
				<?php do_settings_sections( 'grants_options' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Grant Database Menu Item</th>
						<td>
							<p>Select a menu item to mark as active when viewing a grant or grant archive.</p>
							<?php
								$menu_name = 'site';
								$locations = get_nav_menu_locations();
								if ( isset( $locations[ $menu_name ] ) ) :
									$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
									$menu_items = wp_get_nav_menu_items( $menu->term_id );
									?>
									<select name="grants_menu_item">
									<?php foreach ( $menu_items as $menu_item ) : ?>
										<option value="<?php echo $menu_item->ID; ?>" <?php selected( get_option( 'grants_menu_item' ), $menu_item->ID ); ?>><?php echo $menu_item->title; ?></option>
									<?php endforeach; ?>
									</select>
								<?php endif; ?>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Replace the list of columns to print on the All Grants screen.
	 *
	 * @param array $columns Default columns.
	 *
	 * @return array Columns to display.
	 */
	public function grants_columns( $columns ) {
		return array(
			'cb' => '<input type="checkbox" />',
			'id' => __( 'ID' ),
			'title' => __( 'Title' ),
			'taxonomy-' . $this->grants_investigators_taxonomy => 'Investigators',
			'taxonomy-' . $this->grants_status_taxonomy => 'Status',
			'taxonomy-' . $this->grants_topics_taxonomy => 'Topics',
			'taxonomy-' . $this->grants_types_taxonomy => 'Types',
			'admin-comments' =>  __( 'Admin Comments' ),
			'date' => __( 'Date' ),
		);
	}

	/**
	 * Output values for custom Grants columns.
	 *
	 * @param string $column_name The name of the column to display.
	 * @param int $post_id The ID of the current post.
	 */
	public function grant_columns_data( $column_name, $post_id ) {
		$grant_id = get_post_meta( $post_id, '_csanr_grant_project_id', true );
		$grant_admin_comments = get_post_meta( $post_id, '_csanr_grant_admin_comments', true );
		switch( $column_name ) {
			case 'id' :
				if ( $grant_id ) {
					echo esc_html( $grant_id );
				}
				break;
			case 'admin-comments' :
				if ( $grant_admin_comments ) {
					echo esc_html( $grant_admin_comments );
				}
				break;
		}
	}

	/**
	 * Add a metabox context after the title.
	 *
	 * @param WP_Post $post
	 */
	public function edit_form_after_title( $post ) {
		if ( $this->grants_post_type !== $post->post_type ) {
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
		if ( $this->grants_post_type !== $post->post_type ) {
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
		if ( $this->grants_post_type !== $post_type ) {
			return;
		}
		add_meta_box(
			'csanr_grant_information',
			'Grant Information',
			array( $this, 'csanr_grant_information' ),
			$this->grants_post_type,
			'after_title',
			'high'
		);
		add_meta_box(
			'csanr_grant_annual_entry',
			'Annual Entries',
			array( $this, 'csanr_grant_annual_entry' ),
			$this->grants_post_type,
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
		$funds = get_post_meta( $post->ID, '_csanr_grant_funds', true );
		$arc_funds = get_post_meta( $post->ID, '_csanr_grant_arc_funds', true );
		?>
		<p>
			<label for="csanr-grant-project-id">Permanent Project ID</label>
			<input type="text" name="_csanr_grant_project_id" id="csanr-grant-project-id" value="<?php echo esc_attr( $project_id ); ?>">
		</p>
		<p>
  	  <label for="csanr-grant-funds">CSANR Funds</label>
			<input type="text" name="_csanr_grant_funds" id="csanr-grant-funds" value="<?php echo esc_attr( $funds ); ?>">
		</p>
		<p>
			<label for="csanr-grant-arc-funds">ARC Funds</label>
			<input type="text" name="_csanr_grant_arc_funds" id="csanr-grant-arc-funds" value="<?php esc_attr( $arc_funds ); ?>">
		</p>
		<?php
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
			'student_investigators' => 'Graduate Student(s)',
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
						<select class="investigators" multiple="multiple" name="_csanr_grant_annual_entry[<?php echo $i; ?>][<?php echo $investigator_field_key; ?>][]">
							<?php foreach ( $investigators as $investigator ) : ?>
							<option value="<?php echo $investigator->slug; ?>" <?php
								if ( $investigator_value ) {
									selected( in_array( $investigator->slug, $investigator_value ) );
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
		// Check nonce.
		if ( ! isset( $_POST['grants_meta_nonce'] ) ) {
			return $post_id;
		}
		$nonce = $_POST['grants_meta_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'grants_meta' ) ) {
			return $post_id;
		}
		// Bail if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// Bail if user doesn't have adequate permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		// Sanitize and save text inputs.
		$text_fields = array( '_csanr_grant_project_id', '_csanr_grant_funds', '_csanr_grant_arc_funds' );
		foreach( $text_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
			} else {
				delete_post_meta( $post_id, $field );
			}
		}
		// Sanitize and save wp_editors.
		$wp_editor_fields = array( '_csanr_grant_publications', '_csanr_grant_additional_funds', '_csanr_grant_impacts', '_csanr_grant_admin_comments' );
		foreach( $wp_editor_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, wp_kses_post( $_POST[ $field ] ) );
			} else {
				delete_post_meta( $post_id, $field );
			}
		}
		// Annual entries. (There may well be some inefficiencies ahead.)
		if ( isset( $_POST['_csanr_grant_annual_entry'] ) ) {
			// Sanitize data and build arrays to work with.
			$annual_entries = array();
			$investigators = array();
			foreach ( $_POST['_csanr_grant_annual_entry'] as $entry ) {
				$pi_array = array();
				$ai_array = array();
				$si_array = array();
				// Sanitize each selected investigator, push to arrays for entry and taxonomy term setting.
				if ( $entry['principal_investigators'] ) {
					foreach ( $entry['principal_investigators'] as $pi ) {
						$pi_array[] = sanitize_text_field( $pi );
						if ( ! in_array( $pi, $investigators ) ) {
							$investigators[] = sanitize_text_field( $pi );
						}
					}
				}
				if ( $entry['additional_investigators'] ) {
					foreach ( $entry['additional_investigators'] as $ai ) {
						$ai_array[] = sanitize_text_field( $ai );
						if ( ! in_array( $ai, $investigators ) ) {
							$investigators[] = sanitize_text_field( $ai );
						}
					}
				}
				if ( $entry['student_investigators'] ) {
					foreach ( $entry['student_investigators'] as $si ) {
						$si_array[] = sanitize_text_field( $si );
						if ( ! in_array( $si, $investigators ) ) {
							$investigators[] = sanitize_text_field( $si );
						}
					}
				}
				// Build an entry for each year.
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
			}
			// Save.
			if ( ! empty( $annual_entries ) ) {
				update_post_meta( $post_id, '_csanr_grant_annual_entries', $annual_entries );
			} else {
				delete_post_meta( $post_id, '_csanr_grant_annual_entries' );
			}
			// Set the "Investigator" taxonomy terms.
			if ( ! empty( $investigators ) ) {
				wp_set_object_terms( $post_id, $investigators, $this->grants_investigators_taxonomy );
			}
		}
	}

	/**
	 * Enqueue the scripts and styles used on the front end.
	 */
	public function wp_enqueue_scripts() {
		$post = get_post();
		if ( is_singular() && has_shortcode( $post->post_content, 'csanr_grants_browse' ) ) {
			wp_enqueue_style( 'grant-browse-shortcode', plugins_url( 'css/grant-browse-shortcode.css', __FILE__ ) );
		}
		if ( is_single() && $this->grants_post_type == get_post_type() ) {
			wp_enqueue_style( 'grant', plugins_url( 'css/grant.css', __FILE__ ) );
		}
		if ( is_post_type_archive( $this->grants_post_type ) || is_tax( array( $this->grants_investigators_taxonomy, $this->grants_status_taxonomy, $this->grants_topics_taxonomy, $this->grants_types_taxonomy ) ) ) {
			wp_enqueue_style( 'grants-archive', plugins_url( 'css/grants.css', __FILE__ ), array( 'spine-theme' ) );
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
		if ( is_single() && $this->grants_post_type == get_post_type() ) {
			$template = plugin_dir_path( __FILE__ ) . 'templates/single.php';
		}
		if ( is_post_type_archive( $this->grants_post_type ) || is_tax( array( $this->grants_investigators_taxonomy, $this->grants_status_taxonomy, $this->grants_topics_taxonomy, $this->grants_types_taxonomy ) ) ) {
			$template = plugin_dir_path( __FILE__ ) . 'templates/index.php';
		}
		return $template;
	}

	/**
	 * Apply 'dogeared' class to the Grants menu item when viewing a grant.
	 *
	 * @param array $classes Current list of nav menu classes.
	 * @param WP_Post $item Post object representing the menu item.
	 * @param stdClass $args Arguments used to create the menu.
	 *
	 * @return array Modified list of nav menu classes.
	 */
	public function nav_menu_css_class( $classes, $item, $args ) {
		$id = esc_attr( get_option( 'grants_menu_item' ) );
		$grant = $this->grants_post_type == get_post_type();
		$grant_archive = is_post_type_archive( $this->grants_post_type );
		$grant_taxonomy_archive = is_tax( array( $this->grants_investigators_taxonomy, $this->grants_status_taxonomy, $this->grants_topics_taxonomy, $this->grants_types_taxonomy ) );
		if ( 'site' === $args->theme_location && $item->ID == $id && ( $grant || $grant_archive || $grant_taxonomy_archive ) ) {
			$classes[] = 'dogeared';
		}
		return $classes;
	}

	/**
	 * Filter for grant year archives.
	 */
	public function grants_archive_filter( $where ) {
		$where = 'WHERE post_type = "' . $this->grants_post_type . '" AND post_status = "publish"';
		return $where;
	}

	/**
	 * Function (leveraging grant year archive filter) to display year archive links for grants.
	 */
	public function get_grants_archives() {
		add_filter( 'getarchives_where', array( $this, 'grants_archive_filter' ), 10, 1 );
		$html = wp_get_archives( array(
			'type'		        => 'yearly',
			'format'	        => 'html', 
			'show_post_count' => 1,
			'echo'		        => 0,
			'order'           => 'ASC',
		) );
		$html = str_replace( "href='" . get_bloginfo('url') . "/", "href='" . get_bloginfo('url') . "/grants/", $html );
		echo $html;
		remove_filter( 'getarchives_where', array( $this, 'grants_archive_filter' ), 10, 1 );
	}

	/**
	 * Display a list of ways to browse Grants.
	 *
	 * @param array $atts Attributes passed to the shortcode.
	 *
	 * @return string Content to display in place of the shortcode.
	 */
	public function csanr_grants_browse( $atts ) {
		ob_start();
		?>
		<dl class="cahnrs-accordion slide">
			<dt>
				<h3>Year</h3>
			</dt>
			<dd>
				<ul class="grant-years">
					<?php $this->get_grants_archives(); ?>
				</ul>
			</dd>
		</dl>
		<?php
			$grant_taxonomies = array(
				'Researcher' => $this->grants_investigators_taxonomy,
				'Type'       => $this->grants_types_taxonomy,
				'Topic'      => $this->grants_topics_taxonomy,
			);
		?>
		<?php foreach ( $grant_taxonomies as $name => $grant_taxonomy ) : ?>
		<dl class="cahnrs-accordion slide">
			<dt>
				<h3><?php echo $name; ?></h3>
			</dt>
			<dd>
				<?php $grant_taxonomy_terms = get_terms( $grant_taxonomy ); ?>
				<ul>
					<?php
						foreach( $grant_taxonomy_terms as $term ) {
							$term_link = get_term_link( $term, $grant_taxonomy );
							?><li><a href="<?php echo $term_link; ?>"><?php echo $term->name; ?></a> (<?php echo $term->count; ?>)</li><?php
						}
					?>
				</ul>
			</dd>
		</dl>
		<?php endforeach; ?>
    <h3 class="all-grants"><a href="<?php echo get_post_type_archive_link( $this->grants_post_type ); ?>">All Grants &raquo;</a></h3>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

}

new CAHNRSWP_CSANR_Grants();
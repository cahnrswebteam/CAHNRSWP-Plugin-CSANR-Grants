<?php get_header(); ?>

<main class="grants-archive">

	<?php get_template_part('parts/headers'); ?>

	<section class="row single gutter pad-ends">

		<div class="column one">

			<?php
      	if ( is_year() ) {
        	$title_prefix = get_the_date( 'Y' ) . ' ';
    		} elseif ( is_tax() ) {
        	$title_prefix = single_term_title( '', false ) . ' ';
				} else {
					$title_prefix = '';
				}
			?>

			<header class="archive-header">
				<h1 class="archive-title"><?php echo $title_prefix; ?>Grants</h1>
			</header>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<thead>
      		<tr>
          	<th>ID</th>
          	<th>Title</th>
          	<th>Principal Investigator(s)</th>
						<?php if ( is_tax( 'investigator' ) ) : ?>
						<th>Additional Personnel</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
				<?php
					global $query_string;
					query_posts( $query_string . '&posts_per_page=-1&order=ASC&orderby=meta_value_num&meta_key=_csanr_grant_project_id' );
				?>
				<?php while ( have_posts() ) : the_post(); ?>
					<tr>
						<td>
							<?php $grant_id = get_post_meta( get_the_ID(), '_csanr_grant_project_id', true ); ?>
							<?php echo esc_html( $grant_id ); ?>
						</td>
						<td>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</td>
						<td><?php
							$grant_annual_entries = get_post_meta( get_the_ID(), '_csanr_grant_annual_entries', true );
							if ( $grant_annual_entries ) {
								$unique_pis = array();
								foreach ( $grant_annual_entries as $year => $entry ) {
									if ( $entry['principal_investigators'] ) {
										foreach ( $entry['principal_investigators'] as $pi ) {
											if ( ! in_array( $pi, $unique_pis ) ) {
												$unique_pis[] = $pi;
												$investigator_object = get_term_by( 'slug', $pi, 'investigator' );
												echo $investigator_object->name . '<br />';
											}
										}
									}
								}
							}
						?></td>
						<?php if ( is_tax( 'investigator' ) && $grant_annual_entries ) : ?>
						<td><?php
							if ( $grant_annual_entries ) {
								$unique_ais = array();
								foreach ( $grant_annual_entries as $year => $entry ) {
									if ( $entry['additional_investigators'] ) {
										foreach ( $entry['additional_investigators'] as $ai ) {
											$investigator_object = get_term_by( 'slug', $ai, 'investigator' );
											if ( ! in_array( $investigator_object->name, $unique_ais ) ) {
												$unique_ais[] = $investigator_object->name;
											}
										}
									}
									if ( $entry['student_investigators'] ) {
										foreach ( $entry['student_investigators'] as $si ) {
											$investigator_object = get_term_by( 'slug', $si, 'investigator' );
											if ( ! in_array( $investigator_object->name, $unique_ais ) ) {
												$unique_ais[] = $investigator_object->name;
											}
										}
									}
								}
								if ( ! empty( $unique_ais ) ) {
									sort( $unique_ais );
									echo implode( '<br />', $unique_ais );
								}
							}
						?></td>
						<?php endif; ?>
					</tr>
				<?php endwhile; // end of the loop. ?>
				<?php wp_reset_query(); ?>
				</tbody>
			</table>

		</div><!--/column-->

	</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main>

<?php

get_footer();
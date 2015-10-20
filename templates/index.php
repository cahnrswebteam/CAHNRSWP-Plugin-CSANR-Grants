<?php get_header(); ?>

<main class="<?php echo $main_class; ?>">

	<?php get_template_part('parts/headers'); ?>

	<section class="row single gutter pad-ends">

		<div class="column one">

			<h2>CSANR Grants</h2>

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
				query_posts( $query_string . '&posts_per_page=-1' ); ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php
						$investigator = wp_get_post_terms( $post_id, 'investigator' );
					?>
					<tr>
						<td>
							<?php $grant_id = get_post_meta( get_the_ID(), '_csanr_grant_project_id', true ); ?>
							<p><?php echo esc_html( $grant_id ); ?></p>
						</td>
						<td><p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
            <?php
/*
$grant = get_post_meta( get_the_ID(), '_grant' );
$entries = $grant[0]['annual_entries'];
if ( $entries ) {
	$new_entries = array();
	foreach ( $entries as $entry ) {
		$pi_array = array();
		$ai_array = array();
		$si_array = array();

		$new_entries[ $entry['year'] ] = array();

		$pis = $entry['pi'];
		if ( $pis ) { foreach ( $pis as $pi ) { $pi_array[] = $pi; } }
		if ( ! empty( $pi_array ) ) {
			$new_entries[ $entry['year'] ]['principal_investigators'] = array();
			$new_entries[ $entry['year'] ]['principal_investigators'] = $pi_array;
		}

		$ais = $entry['additional'];
		if ( $ais ) { foreach ( $ais as $ai ) { $ai_array[] = $ai; } }
		if ( ! empty( $ai_array ) ) {
			$new_entries[ $entry['year'] ]['additional_investigators'] = array();
			$new_entries[ $entry['year'] ]['additional_investigators'] = $ai_array;
		}

		$sis = $entry['students'];
		if ( $sis ) { foreach ( $sis as $si ) { $si_array[] = $si; } }
		if ( ! empty( $si_array ) ) {
			$new_entries[ $entry['year'] ]['student_investigators'] = array();
			$new_entries[ $entry['year'] ]['student_investigators'] = $si_array;
		}

		if ( $entry['progress'] ) {
			$new_entries[ $entry['year'] ]['progress_report'] = $entry['progress'];
		}

		if ( $entry['additional_progress'] ) {
			$new_entries[ $entry['year'] ]['additional_progress_report'] = $entry['additional_progress'];
		}

		if ( $entry['amount'] ) {
			$new_entries[ $entry['year'] ]['amount'] = $entry['amount'];
		}

	}

	//print_r($new_entries);
	//add_post_meta( get_the_ID(), '_csanr_grant_annual_entries', $new_entries );
}
*/
						//delete_post_meta( get_the_ID(), '_grant' );

						?>

						</td>
						<td>
						<?php
							
						?>
						</td>
						<?php if ( is_tax( 'investigator' ) ) : ?>

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
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
						$grant = get_post_meta( get_the_ID(), '_grant' );
/*
						$grant_id = $grant[0]['project_id'];
						if ( $grant_id ) {
							echo '<p>' . $grant_id . '</p>';
							//add_post_meta( get_the_ID(), '_csanr_grant_project_id', $grant_id );
						}

						$grant_funds = $grant[0]['csanr_funds'];
						if ( $grant_funds ) {
							echo '<p><strong>Grant Funds</strong>: ' . $grant_funds . '</p>';
							//add_post_meta( get_the_ID(), '_csanr_grant_funds', $grant_funds );
						}

						$grant_arc_funds = $grant[0]['arc_funds'];
						if ( $grant_arc_funds ) {
							echo '<p><strong>ARC Funds</strong>: ' . $grant_arc_funds . '</p>';
							//add_post_meta( get_the_ID(), '_csanr_grant_arc_funds', $grant_arc_funds );
						}

						$grant_publications = $grant[0]['publications'];
						if ( $grant_publications ) {
							echo '<p><strong>Publications</strong>: ' . $grant_publications . '</p>';
							//add_post_meta( get_the_ID(), '_csanr_grant_publications', $grant_publications );
						}

						$grant_additional_funds = $grant[0]['additional_funds'];
						if ( $grant_additional_funds ) {
							echo '<p><strong>Additional Funds</strong>: ' . $grant_additional_funds . '</p>';
							//add_post_meta( get_the_ID(), '_csanr_grant_additional_funds', $grant_additional_funds );
						}

						$grant_impacts = $grant[0]['impacts'];
						if ( $grant_impacts ) {
							echo '<p><strong>Impacts</strong>: ' . $grant_impacts . '</p>';
							//add_post_meta( get_the_ID(), '_csanr_grant_impacts', $grant_impacts );
						}

						$grant_admin_comments = $grant[0]['admin_comments'];
						if ( $grant_admin_comments ) {
							echo '<p><strong>Admin Comments</strong>: ' . $grant_admin_comments . '</p>';
							//add_post_meta( get_the_ID(), '_csanr_grant_admin_comments', $grant_admin_comments );
						}

						$entries = $grant[0]['annual_entries'];
						if ( $entries ) {
							foreach ( $entries as $entry ) {
								$year = $entry['year'];
								$pis = $entry['pi'];
								if ( $pis ) { foreach ( $pis as $pi ) { $pi_array[] = $pi; } }
								$ais = $entry['additional'];
								if ( $ais ) { foreach ( $ais as $ai ) { $ai_array[] = $ai; } }
								$sis = $entry['students'];
								if ( $sis ) { foreach ( $sis as $si ) { $si_array[] = $si; } }

								$new_entries[ $year ] = array(
									'principal_investigators' => $pi_array,
									'additional_investigators' => $ai_array,
									'student_investigators' => $si_array,
									'progress_report' => $entry['progress'],
									'additional_progress_report' => $entry['additional_progress'],
									'amount' => $entry['amount']
								);
							}
							add_post_meta( get_the_ID(), '_csanr_grant_annual_entries', $new_entries );
						}
*/
						//delete_post_meta( get_the_ID(), '_grant' );

						?>

						</td>
						<td>
						
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